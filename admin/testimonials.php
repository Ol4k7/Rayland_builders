<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: index.php"); exit; }
require_once '../includes/db.php';

// Handle New Review Submission
if (isset($_POST['add_review'])) {
    $name = htmlspecialchars($_POST['client_name']);
    $text = htmlspecialchars($_POST['review_text']);
    $rating = (int)$_POST['rating'];

    $stmt = $conn->prepare("INSERT INTO testimonials (client_name, review_text, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $name, $text, $rating);
    $stmt->execute();
    header("Location: testimonials.php");
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM testimonials WHERE id=$id");
    header("Location: testimonials.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Testimonials</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .split-layout { display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .admin-card { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .review-item { border-bottom: 1px solid #eee; padding: 10px 0; display: flex; justify-content: space-between; }
    </style>
</head>
<body style="background: #f3f4f6;">

<div class="split-layout">
    <div class="admin-card">
        <h3>Add Review</h3>
        <form method="POST">
            <div style="margin-bottom:10px;">
                <label>Client Name</label>
                <input type="text" name="client_name" required style="width:100%; padding:8px;">
            </div>
            <div style="margin-bottom:10px;">
                <label>Rating (1-5)</label>
                <select name="rating" style="width:100%; padding:8px;">
                    <option value="5">⭐⭐⭐⭐⭐ (5 Stars)</option>
                    <option value="4">⭐⭐⭐⭐ (4 Stars)</option>
                </select>
            </div>
            <div style="margin-bottom:10px;">
                <label>Review</label>
                <textarea name="review_text" rows="4" required style="width:100%; padding:8px;"></textarea>
            </div>
            <button type="submit" name="add_review" class="btn" style="width:100%;">Add Review</button>
            <br><br><a href="dashboard.php">Back to Dashboard</a>
        </form>
    </div>

    <div class="admin-card">
        <h3>Existing Reviews</h3>
        <?php
        $res = $conn->query("SELECT * FROM testimonials ORDER BY created_at DESC");
        while ($row = $res->fetch_assoc()) {
            echo "<div class='review-item'>";
            echo "<div><strong>{$row['client_name']}</strong> <span style='color:gold;'>".str_repeat("★", $row['rating'])."</span><br><small>{$row['review_text']}</small></div>";
            echo "<a href='testimonials.php?delete={$row['id']}' style='color:red;'>[Delete]</a>";
            echo "</div>";
        }
        ?>
    </div>
</div>

</body>
</html>