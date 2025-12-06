<?php
/**
 * Index Page - Home Page
 * COS30020 Assignment 2 - My Friend System
 */

require_once 'settings.php';

$message = '';

// Start session to check login status for navigation
session_start();

// Create tables and populate with sample data if they don't exist
$conn = getDBConnection();

// Create friends table
$createFriendsTable = "CREATE TABLE IF NOT EXISTS friends (
    friend_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    friend_email VARCHAR(40) NOT NULL,
    password VARCHAR(15) NOT NULL,
    profile_name VARCHAR(35) NOT NULL,
    date_started DATE NOT NULL,
    num_of_friends INT UNSIGNED DEFAULT 0
)";

// Create myfriends table
$createMyFriendsTable = "CREATE TABLE IF NOT EXISTS myfriends (
    friend_id1 INT NOT NULL,
    friend_id2 INT NOT NULL
)";

$tablesCreated = false;
$recordsPopulated = false;

// Execute table creation
if (mysqli_query($conn, $createFriendsTable)) {
    $tablesCreated = true;
    
    // Check if friends table is empty
    $checkRecords = "SELECT COUNT(*) as count FROM friends";
    $result = mysqli_query($conn, $checkRecords);
    $row = mysqli_fetch_assoc($result);
    
    if ($row['count'] == 0) {
        // Populate friends table with 25 sample records for pagination demo
        // Note: Assignment requires minimum of 10, expanded to 25 to properly demonstrate
        // Task 8 (Pagination - Extra Challenge) which displays 10 users per page
        $sampleFriends = array(
            array('emma.wilson@email.com', 'pass123', 'Emma Wilson', '2024-01-15'),
            array('james.brown@email.com', 'pass456', 'James Brown', '2024-02-20'),
            array('sophia.taylor@email.com', 'pass789', 'Sophia Taylor', '2024-03-10'),
            array('oliver.davis@email.com', 'pass101', 'Oliver Davis', '2024-03-25'),
            array('ava.martinez@email.com', 'pass202', 'Ava Martinez', '2024-04-05'),
            array('william.garcia@email.com', 'pass303', 'William Garcia', '2024-04-18'),
            array('isabella.rodriguez@email.com', 'pass404', 'Isabella Rodriguez', '2024-05-12'),
            array('lucas.anderson@email.com', 'pass505', 'Lucas Anderson', '2024-06-08'),
            array('mia.thomas@email.com', 'pass606', 'Mia Thomas', '2024-07-14'),
            array('ethan.jackson@email.com', 'pass707', 'Ethan Jackson', '2024-08-22'),
            array('charlotte.white@email.com', 'pass808', 'Charlotte White', '2024-09-05'),
            array('benjamin.harris@email.com', 'pass909', 'Benjamin Harris', '2024-09-18'),
            array('amelia.martin@email.com', 'pass111', 'Amelia Martin', '2024-10-01'),
            array('henry.thompson@email.com', 'pass222', 'Henry Thompson', '2024-10-12'),
            array('harper.moore@email.com', 'pass333', 'Harper Moore', '2024-10-25'),
            array('alexander.lee@email.com', 'pass444', 'Alexander Lee', '2024-11-03'),
            array('evelyn.walker@email.com', 'pass555', 'Evelyn Walker', '2024-11-15'),
            array('daniel.hall@email.com', 'pass666', 'Daniel Hall', '2024-11-28'),
            array('ella.allen@email.com', 'pass777', 'Ella Allen', '2024-12-05'),
            array('matthew.young@email.com', 'pass888', 'Matthew Young', '2024-12-18'),
            array('grace.king@email.com', 'pass999', 'Grace King', '2024-12-22'),
            array('jack.wright@email.com', 'pass1010', 'Jack Wright', '2024-12-28'),
            array('lily.lopez@email.com', 'pass1111', 'Lily Lopez', '2025-01-05'),
            array('samuel.hill@email.com', 'pass1212', 'Samuel Hill', '2025-01-15'),
            array('chloe.green@email.com', 'pass1313', 'Chloe Green', '2025-01-25')
        );
        
        foreach ($sampleFriends as $friend) {
            $insertQuery = "INSERT INTO friends (friend_email, password, profile_name, date_started, num_of_friends) 
                           VALUES ('{$friend[0]}', '{$friend[1]}', '{$friend[2]}', '{$friend[3]}', 0)";
            mysqli_query($conn, $insertQuery);
        }
        $recordsPopulated = true;
    }
}

if (mysqli_query($conn, $createMyFriendsTable)) {
    // Check if myfriends table is empty
    $checkMyFriends = "SELECT COUNT(*) as count FROM myfriends";
    $result = mysqli_query($conn, $checkMyFriends);
    $row = mysqli_fetch_assoc($result);
    
    if ($row['count'] == 0) {
        // Populate myfriends table with 50+ sample friendship records
        // Note: Assignment requires minimum of 20, expanded to 80+ to properly demonstrate
        // Task 9 (Mutual Friends - Extra Challenge) with diverse friendship networks
        // Creating diverse friendship networks to demonstrate mutual friends feature
        $sampleFriendships = array(
            // User 1 (Emma) - 6 friends
            array(1, 2), array(1, 3), array(1, 4), array(1, 5), array(1, 6), array(1, 7),
            // User 2 (James) - 7 friends (shares 4 mutual with Emma)
            array(2, 1), array(2, 3), array(2, 4), array(2, 5), array(2, 8), array(2, 9), array(2, 10),
            // User 3 (Sophia) - 6 friends
            array(3, 1), array(3, 2), array(3, 6), array(3, 11), array(3, 12), array(3, 13),
            // User 4 (Oliver) - 5 friends
            array(4, 1), array(4, 2), array(4, 7), array(4, 14), array(4, 15),
            // User 5 (Ava) - 5 friends
            array(5, 1), array(5, 2), array(5, 8), array(5, 16), array(5, 17),
            // User 6 (William) - 4 friends
            array(6, 1), array(6, 3), array(6, 18), array(6, 19),
            // User 7 (Isabella) - 4 friends
            array(7, 1), array(7, 4), array(7, 20), array(7, 21),
            // User 8 (Lucas) - 5 friends
            array(8, 2), array(8, 5), array(8, 9), array(8, 22), array(8, 23),
            // User 9 (Mia) - 4 friends
            array(9, 2), array(9, 8), array(9, 24), array(9, 25),
            // User 10 (Ethan) - 3 friends
            array(10, 2), array(10, 11), array(10, 12),
            // User 11 (Charlotte) - 4 friends
            array(11, 3), array(11, 10), array(11, 13), array(11, 14),
            // User 12 (Benjamin) - 3 friends
            array(12, 3), array(12, 10), array(12, 15),
            // User 13 (Amelia) - 3 friends
            array(13, 3), array(13, 11), array(13, 16),
            // User 14 (Henry) - 3 friends
            array(14, 4), array(14, 11), array(14, 17),
            // User 15 (Harper) - 3 friends
            array(15, 4), array(15, 12), array(15, 18),
            // User 16 (Alexander) - 3 friends
            array(16, 5), array(16, 13), array(16, 19),
            // User 17 (Evelyn) - 3 friends
            array(17, 5), array(17, 14), array(17, 20),
            // User 18 (Daniel) - 3 friends
            array(18, 6), array(18, 15), array(18, 21),
            // User 19 (Ella) - 3 friends
            array(19, 6), array(19, 16), array(19, 22),
            // User 20 (Matthew) - 3 friends
            array(20, 7), array(20, 17), array(20, 23),
            // User 21 (Grace) - 2 friends
            array(21, 7), array(21, 18),
            // User 22 (Jack) - 2 friends
            array(22, 8), array(22, 19),
            // User 23 (Lily) - 2 friends
            array(23, 8), array(23, 20),
            // User 24 (Samuel) - 1 friend
            array(24, 9),
            // User 25 (Chloe) - 1 friend
            array(25, 9)
        );
        
        foreach ($sampleFriendships as $friendship) {
            $insertQuery = "INSERT INTO myfriends (friend_id1, friend_id2) VALUES ({$friendship[0]}, {$friendship[1]})";
            mysqli_query($conn, $insertQuery);
        }
        
        // Update num_of_friends count for each user
        $updateCountQuery = "UPDATE friends f 
                            SET num_of_friends = (
                                SELECT COUNT(*) 
                                FROM myfriends mf 
                                WHERE mf.friend_id1 = f.friend_id
                            )";
        mysqli_query($conn, $updateCountQuery);
        
        $recordsPopulated = true;
    }
}

if ($tablesCreated && $recordsPopulated) {
    $message = "Tables successfully created and populated.";
} elseif ($tablesCreated) {
    $message = "Tables successfully created and populated.";
} else {
    $message = "Error creating tables.";
}

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Friend System - Home</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <?php 
    require_once 'navigation.php';
    renderNavigation('index');
    ?>
    <div class="container">
        <header>
            <h1>My Friend System</h1>
            <h2>Assignment Home Page</h2>
        </header>
        
        <main>
            <div class="info-section">
                <p><strong>Name:</strong> Minh Hieu Ong</p>
                <p><strong>Student ID:</strong> 104680710</p>
                <p><strong>Email:</strong> <a href="mailto:ongminhhieu12@gmail.com">ongminhhieu12@gmail.com</a></p>
            </div>
            
            <div class="declaration">
                <p>I declare that this assignment is my individual work. I have not worked collaboratively, 
                nor have I copied from any other student's work or from any other source.</p>
            </div>
            
            <div class="status-message">
                <p><?php echo htmlspecialchars($message); ?></p>
            </div>
            
            <div class="navigation">
                <a href="signup.php" class="btn">Sign Up</a>
                <a href="login.php" class="btn">Log In</a>
                <a href="about.php" class="btn">About</a>
            </div>
        </main>
        
        <footer>
            <p>&copy; 2025 My Friend System | COS30020 Advanced Web Development</p>
        </footer>
    </div>
</body>
</html>
