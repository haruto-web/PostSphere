<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config/db.php';

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Fetch the post to edit
    $query = "SELECT * FROM posts WHERE id = ? AND author_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $post_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
    } else {
        echo "Post not found or you do not have permission to edit this post.";
        exit();
    }
} else {
    echo "No post ID provided.";
    exit();
}

// Handle post update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Check if fields are empty
    if (empty($title) || empty($content)) {
        echo "Title and content are required.";
        exit();
    }

    // Update post in the database
    $update_query = "UPDATE posts SET title = ?, content = ? WHERE id = ? AND author_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssii", $title, $content, $post_id, $_SESSION['user_id']);
    $stmt->execute();

    header("Location: dashboard.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container mt-5">
        <h1>Edit Post</h1>
        <form action="edit_post.php?id=<?php echo $post['id']; ?>" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Update Post</button>
        </form>
    </div>

    <script src="assets/js/scripts.js"></script>
</body>
</html>
