<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: index.php"); exit; }
require_once '../includes/db.php';

$msg = "";

// IMAGE COMPRESSION FUNCTION
function compressImage($source, $destination, $quality) {
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source);
    elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source);
    elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source);
    else return false;

    // Resize if width is greater than 1000px
    $width = imagesx($image);
    $height = imagesy($image);
    $maxWidth = 1000;
    
    if ($width > $maxWidth) {
        $newWidth = $maxWidth;
        $newHeight = floor($height * ($maxWidth / $width));
        $tmp = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG
        if($info['mime'] == 'image/png'){
            imagealphablending($tmp, false);
            imagesavealpha($tmp, true);
        }
        
        imagecopyresampled($tmp, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        $image = $tmp;
    }

    // Save as JPEG (or original format) to destination
    // We convert everything to JPEG for consistent compression, except PNGs with transparency
    if($info['mime'] == 'image/png') {
        imagepng($image, $destination, 8); // Compression level 0-9
    } else {
        imagejpeg($image, $destination, $quality); // Quality 0-100
    }
    
    return true;
}

// Handle Form Submission
if (isset($_POST['submit'])) {
    $title = htmlspecialchars($_POST['title']);
    $desc = htmlspecialchars($_POST['description']);
    
    $target_dir = "../uploads/projects/";
    
    // Generate unique names
    $time = time();
    $before_name = $time . '_before.jpg'; // We force jpg extension for simplicity in this example
    $after_name = $time . '_after.jpg';
    
    $target_before = $target_dir . $before_name;
    $target_after = $target_dir . $after_name;
    
    // Compress and Move Files
    // 80 is a good balance of quality vs file size
    if (compressImage($_FILES["before_image"]["tmp_name"], $target_before, 80) && 
        compressImage($_FILES["after_image"]["tmp_name"], $target_after, 80)) {
        
        // Insert into DB
        $stmt = $conn->prepare("INSERT INTO projects (title, description, before_image, after_image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $desc, $before_name, $after_name);
        
        if ($stmt->execute()) {
            header("Location: dashboard.php");
        } else {
            $msg = "Database Error: " . $conn->error;
        }
    } else {
        $msg = "Error uploading or compressing images.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Project - Rayland Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .form-container { max-width: 600px; margin: 3rem auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 1.2rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        textarea { resize: vertical; height: 100px; }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container">
        <h2>Add New Project</h2>
        <?php if($msg): ?><p style="color:red;"><?php echo $msg; ?></p><?php endif; ?>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Project Title</label>
                <input type="text" name="title" class="form-control" required placeholder="e.g. Kitchen Renovation">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" required placeholder="Details about the work done..."></textarea>
            </div>
            <div class="form-group">
                <label>Before Image</label>
                <input type="file" name="before_image" class="form-control" required accept="image/*">
            </div>
            <div class="form-group">
                <label>After Image</label>
                <input type="file" name="after_image" class="form-control" required accept="image/*">
            </div>
            <button type="submit" name="submit" class="btn" style="width:100%;">Upload & Compress</button>
            <p style="text-align:center; margin-top:15px;"><a href="dashboard.php">Cancel</a></p>
        </form>
    </div>
</div>
</body>
</html>