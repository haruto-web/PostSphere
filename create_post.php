<?php
require_once 'config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$user_id = $_SESSION['user_id'];
$query = "SELECT id FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->store_result();

// Check if user exists
if ($stmt->num_rows == 0) {
    die('User ID does not exist in the users table.');
}

// Handle file upload and post creation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $media_type = $_POST['media_type'];
    $media_path = '';

    // Handle file upload (if applicable)
    if (isset($_FILES['media']) && $_FILES['media']['error'] == 0) {
        $media_tmp_name = $_FILES['media']['tmp_name'];
        $media_name = basename($_FILES['media']['name']);
        $upload_dir = 'uploads/';
        
        // Check the media type and create appropriate directories
        $media_path = '';
        if ($media_type == 'image') {
            $media_dir = $upload_dir . 'images/';
            $media_path = $media_dir . $media_name;
        } elseif ($media_type == 'video') {
            $media_dir = $upload_dir . 'videos/';
            $media_path = $media_dir . $media_name;
        }

        // Ensure the directories exist
        if (!file_exists($media_dir)) {
            mkdir($media_dir, 0777, true); 
        }

        // Move uploaded file to the target directory
        if (move_uploaded_file($media_tmp_name, $media_path)) {
            echo "File uploaded successfully!";
        } else {
            echo "Error uploading file.";
        }
    }

    // Insert the post into the database
    $query = "INSERT INTO posts (title, content, author_id, media_type, media_path) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssiss', $title, $content, $user_id, $media_type, $media_path); 

    if ($stmt->execute()) {
        header("Location: index.php"); // Redirect after post creation
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link href="bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <h1>Create a New Post</h1>

        <form action="create_post.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Post Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Post Content</label>
                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <label for="media_type" class="form-label">Media Type</label>
                <select class="form-select" id="media_type" name="media_type" required>
                    <option value="text">Text</option> <!-- Added the Text option -->
                    <option value="image">Image</option>
                    <option value="video">Video</option>
                    <option value="link">Link</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="media" class="form-label">Upload Media</label>
                <input type="file" class="form-control" id="media" name="media" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Post</button>
        </form>
    </div>

    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
