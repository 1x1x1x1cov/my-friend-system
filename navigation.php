<?php
/**
 * Navigation Bar Component
 * Displays appropriate navigation based on login status
 */

function renderNavigation($currentPage = '') {
    $isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    $profileName = $isLoggedIn ? $_SESSION['profile_name'] : '';
    
    echo '<nav class="main-nav">';
    echo '<div class="nav-container">';
    
    // Left side - Logo/Home
    echo '<div class="nav-brand">';
    echo '<a href="index.php">My Friend System</a>';
    echo '</div>';
    
    // Right side - Navigation links
    echo '<div class="nav-links">';
    
    if ($isLoggedIn) {
        // Logged in navigation
        echo '<a href="friendlist.php"' . ($currentPage == 'friendlist' ? ' class="active"' : '') . '>Friend List</a>';
        echo '<a href="friendadd.php"' . ($currentPage == 'friendadd' ? ' class="active"' : '') . '>Add Friends</a>';
        echo '<a href="about.php"' . ($currentPage == 'about' ? ' class="active"' : '') . '>About</a>';
        echo '<span class="nav-user">Hello, ' . htmlspecialchars($profileName) . '</span>';
        echo '<a href="logout.php" class="nav-logout">Log Out</a>';
    } else {
        // Not logged in navigation
        echo '<a href="index.php"' . ($currentPage == 'index' ? ' class="active"' : '') . '>Home</a>';
        echo '<a href="friendlist.php"' . ($currentPage == 'friendlist' ? ' class="active"' : '') . '>Friend List</a>';
        echo '<a href="friendadd.php"' . ($currentPage == 'friendadd' ? ' class="active"' : '') . '>Add Friends</a>';
        echo '<a href="about.php"' . ($currentPage == 'about' ? ' class="active"' : '') . '>About</a>';
        echo '<a href="login.php"' . ($currentPage == 'login' ? ' class="active"' : '') . '>Log In</a>';
        echo '<a href="signup.php"' . ($currentPage == 'signup' ? ' class="active"' : '') . '>Sign Up</a>';
    }
    
    echo '</div>'; // nav-links
    echo '</div>'; // nav-container
    echo '</nav>';
}
?>
