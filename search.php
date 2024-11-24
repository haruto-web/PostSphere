<?php
session_start();
include('config/db.php'); 

if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    
    function searchUsers($searchTerm, $conn) {
        $searchTerm = "%" . strtolower($searchTerm) . "%"; 
        $stmt = $conn->prepare("SELECT * FROM users WHERE LOWER(username) LIKE ?");
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        return $stmt->get_result(); 
    }

    $users = searchUsers($searchTerm, $conn);

    if ($users->num_rows > 0) {
        echo "<ul class='list-group'>";
        while ($user = $users->fetch_assoc()) {
            echo "<li class='list-group-item'><a href='user_profile.php?id=" . htmlspecialchars($user['id']) . "'>" . htmlspecialchars($user['username']) . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No results found for '" . htmlspecialchars($searchTerm) . "'</p>";
    }
}
?>
