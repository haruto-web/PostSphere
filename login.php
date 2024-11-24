    <?php
    session_start();
    include 'config/db.php';

    $error_message = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];

                header('Location: index.php'); 
                exit();
            } else {
                $error_message = 'Incorrect password.';
            }
        } else {
            $error_message = 'No user found with that email.';
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PostSphere - Login</title>

        <link rel="stylesheet" href="login.css">
    </head>
    <body>
        <div class="container">
            <!-- Left section with the welcoming message -->
            <div class="left">
                <h1>PostSphere</h1>
                <h2>Content Management System</h2>
                <p>Manage and organize your content with ease.</p>
            </div>

            <!-- Right section with the login form -->
            <div class="right">
                <div class="login-content">
                    <h1>Welcome to PostSphere</h1>
                    <form method="POST" action="login.php">
                        <input type="email" name="email" placeholder="Enter your email" required>
                        <input type="password" name="password" placeholder="Enter your password" required>
                        <button type="submit">Log In</button>
                    </form>
                    <?php if ($error_message): ?>
                        <p id="login-message"><?= $error_message ?></p>
                    <?php endif; ?>
                    <p>Don't have an account? <a href="register.php">Create one</a></p>
                </div>
            </div>
        </div>
    </body>
    </html>
