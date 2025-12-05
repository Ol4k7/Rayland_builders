<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: index.php"); exit; }
require_once '../includes/db.php';

$msg = "";
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: dashboard.php");
    exit;
}

// 1. Fetch Current Data
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();

if (!$project) {
    die("Project not found.");
}

// 2. Handle Update Logic
if (isset($_POST['update'])) {
    $title = htmlspecialchars($_POST['title']);
    $desc = htmlspecialchars($_POST['description']);
    
    // Default to existing images
    $before_name = $project['before_image'];
    $after_name = $project['after_image'];
    $target_dir = "../uploads/projects/";

    // Logic: Did user upload a NEW Before Image?
    if (!empty($_FILES['before_image']['name'])) {
        // Delete old file
        if(file_exists($target_dir . $project['before_image'])) {
            unlink($target_dir . $project['before_image']);
        }
        // Upload new file
        $before_name = time() . '_before_' . basename($_FILES["before_image"]["name"]);
        move_uploaded_file($_FILES["before_image"]["tmp_name"], $target_dir . $before_name);
    }

    // Logic: Did user upload a NEW After Image?
    if (!empty($_FILES['after_image']['name'])) {
        // Delete old file
        if(file_exists($target_dir . $project['after_image'])) {
            unlink($target_dir . $project['after_image']);
        }
        // Upload new file
        $after_name = time() . '_after_' . basename($_FILES["after_image"]["name"]);
        move_uploaded_file($_FILES["after_image"]["tmp_name"], $target_dir . $after_name);
    }

    // Update Database
    $update_stmt = $conn->prepare("UPDATE projects SET title=?, description=?, before_image=?, after_image=? WHERE id=?");
    $update_stmt->bind_param("ssssi", $title, $desc, $before_name, $after_name, $id);

    if ($update_stmt->execute()) {
        header("Location: dashboard.php");
        exit;
    } else {
        $msg = "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Project - Rayland Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .form-container { max-width: 600px; margin: 3rem auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 1.2rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .current-img { width: 100px; height: 80px; object-fit: cover; margin-top: 10px; border-radius: 4px; border: 1px solid #ddd; }
        textarea { resize: vertical; height: 100px; }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2>Edit Project</h2>
        <?php if($msg): ?><p style="color:red;"><?php echo $msg; ?></p><?php endif; ?>
        
        <form action="" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Project Title</label>
                <input type="text" name="title" class="form-control" required value="<?php echo $project['title']; ?>">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" required><?php echo $project['description']; ?></textarea>
            </div>

            <div class="form-group">
                <label>Update Before Image (Optional)</label>
                <input type="file" name="before_image" class="form-control" accept="image/*">
                <p style="font-size:0.8rem; color:#666;">Current:</p>
                <img src="../uploads/projects/<?php echo $project['before_image']; ?>" class="current-img">
            </div>

            <div class="form-group">
                <label>Update After Image (Optional)</label>
                <input type="file" name="after_image" class="form-control" accept="image/*">
                <p style="font-size:0.8rem; color:#666;">Current:</p>
                <img src="../uploads/projects/<?php echo $project['after_image']; ?>" class="current-img">
            </div>

            <button type="submit" name="update" class="btn" style="width:100%;">Save Changes</button>
            <p style="text-align:center; margin-top:15px;"><a href="dashboard.php">Cancel</a></p>
        </form>
    </div>
</div>

</body>
</html>