<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - PostSphere</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <?php if (isset($page_css)) { echo "<link href='assets/css/$page_css' rel='stylesheet'>"; } ?>
</head>
<style>
    /* Global Styles */
    body {
        font-family: 'Arial', sans-serif;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        color: #212529;
        margin: 0;
        padding: 0;
        transition: background 0.3s ease, color 0.3s ease;
    }

    /* Navbar Styling */
    .navbar {
        background-color: #343a40; 
        padding: 15px 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        height: 80px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .navbar .navbar-brand {
        font-size: 1.8rem;
        font-weight: bold;
        color: #ffffff;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .navbar .navbar-nav .nav-link {
        color: #dee2e6;
        font-size: 1.1rem;
        font-weight: 500;
        margin-right: 15px;
        transition: color 0.3s ease;
    }

    .navbar .navbar-nav .nav-link:hover {
        color: #00c851;
    }

    .navbar .navbar-nav .nav-link.active {
        color: #007bff;
        font-weight: bold;
    }

    /* About Section */
    .about-container {
        padding: 40px 20px;
        text-align: center;
    }

    .about-container h1 {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .about-content {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        align-items: center;
    }

    .about-content .col-md-6 {
        flex: 1 1 300px;
        max-width: 500px;
    }

    .about-content img {
        width: 100%;
        border-radius: 8px;
    }

    .about-content p {
        font-size: 1rem;
        line-height: 1.5;
        text-align: justify;
    }

    /* Footer Styling */
    .footer {
        background-color: #343a40;
        color: #fff;
        padding: 20px 10px;
        text-align: center;
    }

    .footer h5 {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .footer ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer ul li {
        margin-bottom: 10px;
    }

    .footer ul li a {
        color: #ddd;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer ul li a:hover {
        color: #fff;
    }
</style>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <h1 class="m-0">PostSphere</h1>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'create_post.php' ? 'active' : ''; ?>" href="create_post.php">Create Post</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'about.php' ? 'active' : ''; ?>" href="about.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'login.php' ? 'active' : ''; ?>" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'register.php' ? 'active' : ''; ?>" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- About Section -->
    <div class="about-container">
        <h1>About PostSphere</h1>
        <div class="about-content">
            <div class="col-md-6">
                <img src="logo.jpg" alt="About PostSphere">
            </div>
            <div class="col-md-6">
                <h3>What is PostSphere?</h3>
                <p>
                    PostSphere is a modern content management system (CMS) designed to help individuals and businesses
                    streamline their content creation and management processes. With an intuitive interface and powerful tools,
                    PostSphere ensures that managing your posts, users, and analytics is both efficient and enjoyable.
                </p>
                <h3>Our Mission</h3>
                <p>
                    Our mission is to empower creators and businesses by providing a user-friendly platform for organizing
                    and publishing content. We believe in simplicity, flexibility, and innovation to meet the growing needs
                    of content creators worldwide.
                </p>
                <h3>Meet the Creators</h3>
                <p>
                    PostSphere was built with dedication and creativity by <strong>Rome</strong> and <strong>Ven</strong>. Their passion for web development and technology 
                    has brought this platform to life, ensuring it meets modern standards and provides a seamless experience for users.
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <ul>
                        <li>Email: support@postsphere.com</li>
                        <li>Phone: +1 (123) 456-7890</li>
                        <li>Address: Manila, Philippines</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Privacy & Policies</h5>
                    <ul>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                        <li><a href="term_service.php">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <p class="mt-3">&copy; <?php echo date('Y'); ?> PostSphere. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
