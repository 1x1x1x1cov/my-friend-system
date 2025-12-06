<?php
/**
 * Login Page - User Authentication
 * COS30020 Assignment 2 - My Friend System
 */

require_once 'settings.php';
session_start();

// Redirect if already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: friendlist.php');
    exit();
}

$email = '';
$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } else {
        $conn = getDBConnection();
        
        $emailEsc = mysqli_real_escape_string($conn, $email);
        $passwordEsc = mysqli_real_escape_string($conn, $password);
        
        $loginQuery = "SELECT * FROM friends WHERE friend_email = '$emailEsc' AND password = '$passwordEsc'";
        $result = mysqli_query($conn, $loginQuery);
        
        if (mysqli_num_rows($result) == 1) {
            // Login successful
            $user = mysqli_fetch_assoc($result);
            
            // Set session variables
            $_SESSION['friend_id'] = $user['friend_id'];
            $_SESSION['friend_email'] = $user['friend_email'];
            $_SESSION['profile_name'] = $user['profile_name'];
            $_SESSION['logged_in'] = true;
            
            closeDBConnection($conn);
            
            // Redirect to friendlist.php
            header('Location: friendlist.php');
            exit();
        } else {
            $error = "Invalid email or password.";
        }
        
        closeDBConnection($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - My Friend System</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <?php 
    require_once 'navigation.php';
    renderNavigation('login');
    ?>
    <div class="container">
        <header>
            <h1>My Friend System</h1>
            <h2>Log in Page</h2>
        </header>
        
        <main>
            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <div class="form-section">
                <form method="POST" action="login.php">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn">Log in</button>
                        <button type="reset" class="btn btn-secondary">Clear</button>
                    </div>
                </form>
                
                <div class="navigation mt-20 text-center">
                    <a href="index.php">Home</a>
                </div>
            </div>
        </main>
        
        <footer>
            <p>&copy; 2025 My Friend System | COS30020 Advanced Web Development</p>
        </footer>
    </div>
</body>
</html>
