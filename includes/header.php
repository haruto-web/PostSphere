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
    <title>PostSphere</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">  
    
    <?php if (isset($page_css)) { echo "<link href='assets/css/$page_css' rel='stylesheet'>"; } ?>
</head>
<style>
/* Base Styles */
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
    height: auto;
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
    font-size: 1rem;
    font-weight: 500;
    margin-right: 10px;
    transition: color 0.3s ease;
}

.navbar .navbar-nav .nav-link:hover {
    color: #00c851;
}

.navbar .navbar-nav .nav-link.active {
    color: #007bff;
    font-weight: bold;
}

/* Form Styling */
.form-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #e9ecef, #f8f9fa);
    padding: 20px;
}

.form-container .form {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    width: 90%;
    max-width: 400px;
}

/* Media Queries */
@media (max-width: 1024px) {
    .navbar {
        padding: 10px;
    }

    .navbar .navbar-brand {
        font-size: 1.5rem;
    }

    .form-container .form {
        max-width: 350px;
        padding: 20px;
    }
}

@media (max-width: 768px) {
    .navbar .navbar-brand {
        font-size: 1.3rem;
    }

    .navbar .navbar-nav .nav-link {
        font-size: 0.9rem;
    }

    .form-container {
        padding: 10px;
    }

    .form-container .form {
        max-width: 300px;
        padding: 15px;
    }
}

@media (max-width: 576px) {
    .navbar .navbar-brand {
        font-size: 1.1rem;
    }

    .navbar .navbar-nav .nav-link {
        font-size: 0.8rem;
    }
}
</style>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <!-- Logo without transitions -->
            <a class="navbar-brand" href="index.php">
                <h1 class="m-0">PostSphere</h1>
            </a>

            <form class="d-flex me-auto" role="search" id="search-form">
                <input class="form-control me-2" type="search" placeholder="Search" id="search-input" aria-label="Search">
                <button class="btn btn-outline-light" type="submit">Search</button>
            </form>

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

    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">Search Results</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="search-results">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#search-input').on('keyup', function () {
                const query = $(this).val().trim();
                if (query !== '') {
                    $.ajax({
                        url: 'search.php',
                        type: 'GET',
                        data: { search: query },
                        success: function (data) {
                            $('#search-dropdown').html(data).show();
                        },
                        error: function () {
                            $('#search-dropdown').html('<p class="dropdown-item">Error loading search results.</p>').show();
                        }
                    });
                } else {
                    $('#search-dropdown').hide();
                }
            });

            $(document).click(function (e) {
                if (!$(e.target).closest('#search-form').length) {
                    $('#search-dropdown').hide();
                }
            });
        });
    </script>
    <div class="dropdown-menu w-100 position-absolute" id="search-dropdown" style="display: none;"></div>
</body>
</html>
