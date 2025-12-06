<?php
/**
 * Sign Up Page - User Registration
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
$profileName = '';
$errors = array();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $profileName = isset($_POST['profile_name']) ? trim($_POST['profile_name']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        // Check if email already exists
        $conn = getDBConnection();
        $emailCheck = mysqli_real_escape_string($conn, $email);
        $checkQuery = "SELECT * FROM friends WHERE friend_email = '$emailCheck'";
        $result = mysqli_query($conn, $checkQuery);
        
        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Email already exists in the system.";
        }
        closeDBConnection($conn);
    }
    
    // Validate profile name (only letters)
    if (empty($profileName)) {
        $errors[] = "Profile name is required.";
    } elseif (!preg_match("/^[a-zA-Z ]+$/", $profileName)) {
        $errors[] = "Profile name must contain only letters.";
    }
    
    // Validate password (only letters and numbers)
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
        $errors[] = "Password must contain only letters and numbers.";
    }
    
    // Validate password confirmation
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }
    
    // If no errors, insert into database and redirect
    if (empty($errors)) {
        $conn = getDBConnection();
        
        $emailEsc = mysqli_real_escape_string($conn, $email);
        $passwordEsc = mysqli_real_escape_string($conn, $password);
        $profileNameEsc = mysqli_real_escape_string($conn, $profileName);
        $currentDate = date('Y-m-d');
        
        $insertQuery = "INSERT INTO friends (friend_email, password, profile_name, date_started, num_of_friends) 
                       VALUES ('$emailEsc', '$passwordEsc', '$profileNameEsc', '$currentDate', 0)";
        
        if (mysqli_query($conn, $insertQuery)) {
            // Get the new friend_id
            $friendId = mysqli_insert_id($conn);
            
            // Set session variables
            $_SESSION['friend_id'] = $friendId;
            $_SESSION['friend_email'] = $email;
            $_SESSION['profile_name'] = $profileName;
            $_SESSION['logged_in'] = true;
            
            closeDBConnection($conn);
            
            // Redirect to friendadd.php
            header('Location: friendadd.php');
            exit();
        } else {
            $errors[] = "Error creating account. Please try again.";
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
    <title>Sign Up - My Friend System</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <?php 
    require_once 'navigation.php';
    renderNavigation('signup');
    ?>
    <div class="container">
        <header>
            <h1>My Friend System</h1>
            <h2>Registration Page</h2>
        </header>
        
        <main>
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <strong>Please fix the following errors:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="form-section">
                <form method="POST" action="signup.php">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="profile_name">Profile Name</label>
                        <input type="text" id="profile_name" name="profile_name" value="<?php echo htmlspecialchars($profileName); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn">Register</button>
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
