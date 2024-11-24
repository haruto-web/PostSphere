<?php
require_once 'config/db.php';

if (isset($_GET['id'])) {
    $postId = intval($_GET['id']);
    
    // Prepare the SQL query to fetch the post along with the author's username
    $stmt = $conn->prepare("SELECT p.*, u.username FROM posts p JOIN users u ON p.author_id = u.id WHERE p.id = ?");
    $stmt->bind_param("i", $postId);  // Bind the postId to the query
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();

    if (!$post) {
        echo "Post not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - View Post</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'includes/header.php'; ?> 

<div class="container mt-5">
    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
    <p><strong>Created by:</strong> <?php echo htmlspecialchars($post['username']); ?></p> <!-- Display author's username -->
    <p><strong>Created at:</strong> <?php echo htmlspecialchars($post['created_at']); ?></p>
    <hr>
    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
    
    <?php if ($post['media_path']): ?>
        <div>
            <?php if ($post['media_type'] == 'image'): ?>
                <img src="<?php echo htmlspecialchars($post['media_path']); ?>" class="img-fluid" alt="Post Image">
            <?php elseif ($post['media_type'] == 'video'): ?>
                <video controls class="w-100">
                    <source src="<?php echo htmlspecialchars($post['media_path']); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php elseif ($post['media_type'] == 'link'): ?>
                <a href="<?php echo htmlspecialchars($post['media_path']); ?>" target="_blank">View Link</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
