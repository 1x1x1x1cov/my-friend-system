<?php
/**
 * Friend List Page - Display current user's friends
 * COS30020 Assignment 2 - My Friend System
 */

require_once 'settings.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$friendId = $_SESSION['friend_id'];
$profileName = $_SESSION['profile_name'];

// Handle unfriend action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['unfriend_id'])) {
    $unfriendId = intval($_POST['unfriend_id']);
    
    $conn = getDBConnection();
    
    // Remove friendship in both directions
    $deleteQuery1 = "DELETE FROM myfriends WHERE friend_id1 = $friendId AND friend_id2 = $unfriendId";
    $deleteQuery2 = "DELETE FROM myfriends WHERE friend_id1 = $unfriendId AND friend_id2 = $friendId";
    
    mysqli_query($conn, $deleteQuery1);
    mysqli_query($conn, $deleteQuery2);
    
    // Update friend counts
    $updateCount1 = "UPDATE friends SET num_of_friends = (SELECT COUNT(*) FROM myfriends WHERE friend_id1 = $friendId) WHERE friend_id = $friendId";
    $updateCount2 = "UPDATE friends SET num_of_friends = (SELECT COUNT(*) FROM myfriends WHERE friend_id1 = $unfriendId) WHERE friend_id = $unfriendId";
    
    mysqli_query($conn, $updateCount1);
    mysqli_query($conn, $updateCount2);
    
    closeDBConnection($conn);
    
    // Refresh page
    header('Location: friendlist.php');
    exit();
}

// Get current user's friend count
$conn = getDBConnection();

$countQuery = "SELECT num_of_friends FROM friends WHERE friend_id = $friendId";
$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$numFriends = $countRow['num_of_friends'];

// Get list of friends
$friendsQuery = "SELECT f.friend_id, f.profile_name 
                FROM friends f
                INNER JOIN myfriends mf ON f.friend_id = mf.friend_id2
                WHERE mf.friend_id1 = $friendId
                ORDER BY f.profile_name ASC";

$friendsResult = mysqli_query($conn, $friendsQuery);

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friend List - My Friend System</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <?php 
    require_once 'navigation.php';
    renderNavigation('friendlist');
    ?>
    <div class="container">
        <header>
            <h1>My Friend System</h1>
            <h2><?php echo htmlspecialchars($profileName); ?>'s Friend List Page</h2>
        </header>
        
        <main>
            <div class="friend-count">
                Total number of friends is <?php echo $numFriends; ?>
            </div>
            
            <div class="friend-list">
                <?php if (mysqli_num_rows($friendsResult) > 0): ?>
                    <?php while ($friend = mysqli_fetch_assoc($friendsResult)): ?>
                        <div class="friend-item">
                            <span class="friend-name"><?php echo htmlspecialchars($friend['profile_name']); ?></span>
                            <form method="POST" action="friendlist.php" style="margin: 0;">
                                <input type="hidden" name="unfriend_id" value="<?php echo $friend['friend_id']; ?>">
                                <button type="submit" class="btn btn-danger">Unfriend</button>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">You have no friends yet. Start adding friends!</p>
                <?php endif; ?>
            </div>
            
            <div class="navigation mt-20">
                <a href="friendadd.php" class="btn">Add Friends</a>
                <a href="logout.php" class="btn btn-secondary">Log out</a>
            </div>
        </main>
        
        <footer>
            <p>&copy; 2025 My Friend System | COS30020 Advanced Web Development</p>
        </footer>
    </div>
</body>
</html>
