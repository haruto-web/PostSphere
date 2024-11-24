<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/db.php';

function getUserPosts($userId, $conn) {
    $stmt = $conn->prepare("SELECT * FROM posts WHERE author_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result();
}

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        header("Location: search.php");
        exit();
    }

    $posts = getUserPosts($userId, $conn);
} else {
    header("Location: search.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['username']); ?>'s Dashboard</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/dashboard.css" rel="stylesheet"> 
</head>
<body>

<?php include 'includes/header.php'; ?> 

<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <div class="container mt-4">
                <h2><?php echo htmlspecialchars($user['username']); ?>'s Profile</h2>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

                <h3>Posts by <?php echo htmlspecialchars($user['username']); ?></h3>
                <?php if ($posts->num_rows > 0): ?>
                    <div class="list-group">
                        <?php while ($post = $posts->fetch_assoc()): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h5><?php echo htmlspecialchars($post['title']); ?></h5>
                                    <p><?php echo htmlspecialchars(substr($post['content'], 0, 100)); ?>...</p>
                                    <a href="view_post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">View Post</a>
                                </div>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($post['created_at']); ?></span>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p>No posts found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
