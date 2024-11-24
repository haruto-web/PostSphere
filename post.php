<?php
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $media_type = $_POST['media_type'] ?? '';
    $media_path = ''; 

    if (!empty($_FILES['media']['name'])) {
        $uploadDir = 'uploads/';
        $fileName = basename($_FILES['media']['name']);
        $targetFilePath = $uploadDir . $media_type . 's/' . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'webm', 'mp3', 'pdf', 'txt']; 

        if (in_array(strtolower($fileType), $allowedTypes)) {
            if (!is_dir($uploadDir . $media_type . 's/')) {
                mkdir($uploadDir . $media_type . 's/', 0777, true);
            }

            if (move_uploaded_file($_FILES['media']['tmp_name'], $targetFilePath)) {
                $media_path = $targetFilePath; 
            } else {
                echo "Error uploading the file.";
            }
        } else {
            echo "Invalid file type. Only images, videos, and documents are allowed.";
        }
    }

    $author_id = 3;

    if ($title && $content && $media_type) {
        // Prepare and insert the post into the database
        $stmt = $conn->prepare("INSERT INTO posts (title, content, media_type, media_path, author_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssi", $title, $content, $media_type, $media_path, $author_id);

        // Execute the query and redirect or show error
        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            echo "Error creating post: " . $conn->error;
        }
    } else {
        echo "Please fill in all the required fields.";
    }
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
        <form action="post.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <label for="content">Content</label>
                <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
            </div>
            <div class="form-group mt-3">
                <label for="media_type">Media Type</label>
                <select name="media_type" id="media_type" class="form-control" required>
                    <option value="image">Image</option>
                    <option value="video">Video</option>
                    <option value="link">Link</option>
                    <option value="text">Text</option>
                </select>
            </div>
            <div class="form-group mt-3">
                <label for="media">Upload Media</label>
                <input type="file" name="media" id="media" class="form-control-file" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Create Post</button>
        </form>
    </div>

    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
