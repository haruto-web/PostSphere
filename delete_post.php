<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config/db.php';

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Fetch the post to delete
    $query = "SELECT * FROM posts WHERE id = ? AND author_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $post_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Confirm before deletion
        if (isset($_POST['confirm_delete'])) {
            $delete_query = "DELETE FROM posts WHERE id = ? AND author_id = ?";
            $stmt = $conn->prepare($delete_query);
            $stmt->bind_param("ii", $post_id, $_SESSION['user_id']);
            $stmt->execute();

            header("Location: dashboard.php");  
            exit();
        }
    } else {
        echo "Post not found or you do not have permission to delete this post.";
        exit();
    }
} else {
    echo "No post ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Post</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <h1>Are you sure you want to delete this post?</h1>
        <form method="POST">
            <button type="submit" name="confirm_delete" class="btn btn-danger">Yes, delete it</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="assets/js/scripts.js"></script>
</body>
</html>
