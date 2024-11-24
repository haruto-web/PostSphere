<?php
require_once 'config/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit();
    }

    if ($password === $confirm_password) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password_hash);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PostSphere</title>
</head>
<style>
    /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f7fc;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: #333;
}

/* Container */
.container {
    width: 100%;
    max-width: 1200px; 
    background-color: #fff;
    padding: 60px 30px; 
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap; 
}

.left {
    width: 50%;
    padding-right: 40px; 
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: left;
}

.left h2 {
    font-size: 36px; 
    font-weight: bold;
    color: #343a40;
    margin-bottom: 25px; 
}

.left p {
    font-size: 18px;
    color: #343a40;
}

.right {
    width: 50%;
    padding: 40px; 
}

.registration-form {
    text-align: center;
}

.registration-form h2 {
    font-size: 28px; 
    font-weight: bold;
    color: #343a40;
    margin-bottom: 25px; 
}

form {
    display: flex;
    flex-direction: column;
    gap: 20px; 
}

input[type="text"],
input[type="email"],
input[type="password"],
button {
    width: 100%;
    padding: 15px 20px; 
    margin: 10px 0;
    font-size: 16px; 
    border-radius: 8px;
    border: 1px solid #ddd;
    outline: none;
    transition: all 0.3s ease;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    background-color: #f9f9f9;
    color: #333;
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus {
    border-color: #4e54c8;
    background-color: #eaf4ff;
}

button {
    background: #4e54c8;
    color: #fff;
    font-weight: bold;
    border: none;
    cursor: pointer;
    transition: background 0.3s ease;
}

button:hover {
    background: #8f94fb;
}

a {
    color: #4c6ef5;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

p {
    font-size: 16px; 
    margin-top: 25px; 
    color: #777;
}

/* Media Queries for Responsiveness */
@media (max-width: 768px) {
    .container {
        flex-direction: column;
        padding: 40px;
    }

    .left, .right {
        width: 100%;
    }

    .left {
        text-align: center;
        padding: 20px 0;
    }

    .right {
        padding: 30px;
    }
}

</style>
<body>
    <div class="container">
        <div class="left">
            <h2>Welcome to Your Content Management Hub (PostSphere)</h2>
            <p>Streamline your content creation and management. Join us today and take control of your digital presence!</p>
        </div>

        <div class="right">
            <div class="registration-form">
                <h2>Create an account</h2>
                <form method="POST" action="register.php">
                    <input type="text" name="username" class="form-control" placeholder="Username/Full Name" required>
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                    <button type="submit" name="register" class="btn">Register</button>
                </form>
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>

    <script src="assets/js/scripts.js"></script>
</body>
</html>
