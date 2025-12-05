<?php
session_start();
// Security Check: Kick user out if not logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../includes/db.php';

// Handle Delete Action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Get file names to delete from folder
    $query = $conn->query("SELECT before_image, after_image FROM projects WHERE id = $id");
    $row = $query->fetch_assoc();
    
    if($row){
        // Delete files from folder
        if(file_exists("../uploads/projects/" . $row['before_image'])) {
            unlink("../uploads/projects/" . $row['before_image']);
        }
        if(file_exists("../uploads/projects/" . $row['after_image'])) {
            unlink("../uploads/projects/" . $row['after_image']);
        }
        // Delete entry from DB
        $conn->query("DELETE FROM projects WHERE id = $id");
        header("Location: dashboard.php"); // Refresh page
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Rayland Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        .admin-header { background: #1f2933; color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .admin-container { max-width: 1000px; margin: 2rem auto; padding: 0 1rem; }
        .project-table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-top: 20px; }
        .project-table th, .project-table td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
        .project-table th { background: #f4b400; color: #1f2933; }
        .thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; }
        .action-btn { color: white; padding: 5px 10px; border-radius: 4px; font-size: 0.9rem; text-decoration: none; }
        .btn-danger { background: #e3342f; }
        
        /* New Quick Actions Area */
        .quick-actions { display: flex; gap: 15px; margin-bottom: 30px; }
        .qa-card { flex: 1; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: space-between; text-decoration: none; color: inherit; transition: transform 0.2s; border-left: 5px solid var(--primary); }
        .qa-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .qa-icon { font-size: 2rem; color: var(--primary); }
    </style>
</head>
<body>

<div class="admin-header">
    <h3>Rayland Dashboard</h3>
    <div>
        <span style="margin-right: 15px;">Welcome, Admin</span>
        <a href="logout.php" class="btn btn-outline" style="color:white; border-color:white;">Logout</a>
    </div>
</div>

<div class="admin-container">
    
    <div class="quick-actions">
        <a href="add-project.php" class="qa-card">
            <div>
                <h4>Add New Project</h4>
                <p style="font-size:0.9rem; color:#666;">Upload Before & After photos</p>
            </div>
            <i class="fas fa-camera qa-icon"></i>
        </a>

        <a href="testimonials.php" class="qa-card">
            <div>
                <h4>Manage Testimonials</h4>
                <p style="font-size:0.9rem; color:#666;">Add or Delete Client Reviews</p>
            </div>
            <i class="fas fa-star qa-icon"></i>
        </a>
    </div>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-top: 40px;">
        <h2>Existing Projects</h2>
    </div>

    <table class="project-table">
        <thead>
            <tr>
                <th>Preview (After)</th>
                <th>Title</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM projects ORDER BY created_at DESC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><img src='../uploads/projects/".$row['after_image']."' class='thumb'></td>";
                    echo "<td><strong>".$row['title']."</strong></td>";
                    echo "<td>".substr($row['description'], 0, 50)."...</td>";
                    echo "<td>
                            <a href='edit-project.php?id=".$row['id']."' class='action-btn' style='background:#3490dc; margin-right:5px;'>Edit</a>
                            <a href='dashboard.php?delete=".$row['id']."' class='action-btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No projects found. Use the button above to add one!</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>