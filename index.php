<?php
require_once 'config/db.php';
session_start(); // Start the session to check login status

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']); // Assuming user_id is set when logged in
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS - Home</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <h1>Welcome to <span style="color: #007bff;">PostSphere</span> - Content Management System</h1>
        <p class="lead">Manage your content effectively with this simple and intuitive Content Management System.</p>
        
        <h3>Latest Posts</h3>
        <div class="row">
            <?php
            // Fetch the latest posts from the database
            $query = "SELECT id, title, content, media_path, media_type, user_id FROM posts ORDER BY created_at DESC LIMIT 5";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $post_url = "view_post.php?id=" . $row['id']; 
                    $media_path = $row['media_path']; 
                    $media_type = $row['media_type']; 
                    ?>

                    <div class="col-md-6 mb-4"> 
                        <div class="card">
                            <?php
                            // Display media based on type
                            if ($media_type == 'image' && $media_path): ?>
                                <img src="<?php echo $media_path; ?>" class="card-img-top" alt="Post Image" style="height: 200px; object-fit: cover;">
                            <?php elseif ($media_type == 'video' && $media_path): ?>
                                <!-- Video player  -->
                                <video class="card-img-top" style="height: 200px; object-fit: cover;" controls>
                                    <source src="<?php echo $media_path; ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            <?php elseif ($media_type == 'link' && $media_path): ?>
                                <div class="card-img-top p-3">
                                    <a href="<?php echo $media_path; ?>" class="btn btn-link" target="_blank">View Link</a>
                                </div>
                            <?php endif; ?>

                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars(substr($row['content'], 0, 100)); ?>...</p>
                                <a href="<?php echo $post_url; ?>" class="btn btn-primary mt-2">Read More</a> 
                            </div>
                        </div>
                    </div>
                <?php
                }
            } else {
                echo "<p>No posts found. Create your first post!</p>";
            }
            ?>
        </div>

        <div class="text-center mt-5">
            <?php
            // Direct to login page if not logged in
            $create_post_link = $is_logged_in ? "create_post.php" : "login.php";
            ?>
            <a href="<?php echo $create_post_link; ?>" class="btn btn-success btn-lg">Create a Post</a>
        </div>
    </div>

    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
