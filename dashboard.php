<?php
session_start();
include('config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM posts WHERE author_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$posts = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Dashboard</title>
    <link href="dashboard.css" rel="stylesheet">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container mt-4">
    <h2>Welcome to Your <span style="color: #007bff; font-weight: bold;">Dashboard</span></h2>
    
    <div class="row">
        <div class="col-md-8">
            <h3>Your Posts</h3>
            <?php if ($posts->num_rows > 0): ?>
                <div class="list-group">
                    <?php while ($post = $posts->fetch_assoc()): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5><?php echo htmlspecialchars($post['title']); ?></h5>
                                <p><?php echo htmlspecialchars(substr($post['content'], 0, 100)); ?>...</p>
                                
                                <!-- Display Media (Image, Video, Link) -->
                                <?php
                                $media_path = $post['media_path'];
                                $media_type = $post['media_type'];

                                // Handle media display
                                if ($media_type == 'image' && !empty($media_path)): ?>
                                    <img src="<?php echo htmlspecialchars($media_path); ?>" class="card-img-top" alt="Post Image" style="height: 200px; object-fit: cover;">
                                <?php elseif ($media_type == 'video' && !empty($media_path)): ?>
                                    <!-- Video Handling -->
                                    <div class="video-container" style="width: 100%; height: 300px; overflow: hidden; display: block; margin: 10px 0;">
                                        <video controls style="width: 100%; height: 100%; object-fit: cover;">
                                            <source src="<?php echo htmlspecialchars($media_path); ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                <?php elseif ($media_type == 'link' && !empty($media_path)): ?>
                                    <div class="card-img-top p-3">
                                        <a href="<?php echo htmlspecialchars($media_path); ?>" class="btn btn-link" target="_blank">View Link</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <span class="badge bg-primary"><?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                            <div>
                                <!-- Edit Button -->
                                <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                
                                <!-- Delete Button -->
                                <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>You have no posts yet. Start creating one!</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add logout button -->
    <div class="mt-3">
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

</body>
</html>
