<?php
session_start();

// Function to display user information
function display_user_info()
{
    // Check if the user is logged in
    if (isset($_SESSION['user']) && isset($_SESSION['user']['nama'])) {
        echo '<div class="nav-dropdown1">';
        echo '<button class="nav-dropbtn1"><i class="fas fa-user"></i> ' . $_SESSION['user']['nama'] . ' <i class="fas fa-caret-down"></i></button>';
        echo '<div class="nav-dropdown-content1">';
        echo '<a href="Login.php">Logout</a>'; // Add logout link
        echo '</div>';
        echo '</div>';
        
        // Check if the user has the role of admin
        if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') {
            echo '<p class="welcome-message">Selamat datang, Admin!</p>';
        }
    } else {
        echo '<a href="login.php">Login</a>'; // Add login link
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS\navbar.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="CSS/navbar.css">
    <title></title>

</head>
<body>

    <div class="container">
        <nav>
            <img class="logo" src="Indibiz-Putih.png" alt="">
        </nav>

        
        <div class="content">
            <!-- Add your content here -->
        </div>
    </div>

</body>
</html>
