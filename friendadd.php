<?php
/**
 * Add Friend Page - Add new friends from registered users
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

// Handle add friend action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_friend_id'])) {
    $addFriendId = intval($_POST['add_friend_id']);
    
    $conn = getDBConnection();
    
    // Add friendship in both directions
    $insertQuery1 = "INSERT INTO myfriends (friend_id1, friend_id2) VALUES ($friendId, $addFriendId)";
    $insertQuery2 = "INSERT INTO myfriends (friend_id1, friend_id2) VALUES ($addFriendId, $friendId)";
    
    mysqli_query($conn, $insertQuery1);
    mysqli_query($conn, $insertQuery2);
    
    // Update friend counts
    $updateCount1 = "UPDATE friends SET num_of_friends = (SELECT COUNT(*) FROM myfriends WHERE friend_id1 = $friendId) WHERE friend_id = $friendId";
    $updateCount2 = "UPDATE friends SET num_of_friends = (SELECT COUNT(*) FROM myfriends WHERE friend_id1 = $addFriendId) WHERE friend_id = $addFriendId";
    
    mysqli_query($conn, $updateCount1);
    mysqli_query($conn, $updateCount2);
    
    closeDBConnection($conn);
    
    // Refresh page maintaining pagination
    $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
    header('Location: friendadd.php?page=' . $currentPage);
    exit();
}

// Pagination settings
$perPage = 10;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($currentPage < 1) $currentPage = 1;
$offset = ($currentPage - 1) * $perPage;

// Get current user's friend count
$conn = getDBConnection();

$countQuery = "SELECT num_of_friends FROM friends WHERE friend_id = $friendId";
$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$numFriends = $countRow['num_of_friends'];

// Get total count of available users for pagination
$totalCountQuery = "SELECT COUNT(*) as total
                   FROM friends f
                   WHERE f.friend_id != $friendId
                   AND f.friend_id NOT IN (
                       SELECT mf.friend_id2 
                       FROM myfriends mf 
                       WHERE mf.friend_id1 = $friendId
                   )";
$totalResult = mysqli_query($conn, $totalCountQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalUsers = $totalRow['total'];
$totalPages = ceil($totalUsers / $perPage);

// Get list of users who are NOT friends (excluding self) with pagination
$availableQuery = "SELECT f.friend_id, f.profile_name 
                  FROM friends f
                  WHERE f.friend_id != $friendId
                  AND f.friend_id NOT IN (
                      SELECT mf.friend_id2 
                      FROM myfriends mf 
                      WHERE mf.friend_id1 = $friendId
                  )
                  ORDER BY f.profile_name ASC
                  LIMIT $perPage OFFSET $offset";

$availableResult = mysqli_query($conn, $availableQuery);

// Store users in array to calculate mutual friends
$users = array();
while ($user = mysqli_fetch_assoc($availableResult)) {
    $users[] = $user;
}

// Calculate mutual friends for each user
foreach ($users as $key => $user) {
    $userId = $user['friend_id'];
    
    // Query to find mutual friends
    $mutualQuery = "SELECT COUNT(*) as mutual_count
                   FROM myfriends mf1
                   INNER JOIN myfriends mf2 ON mf1.friend_id2 = mf2.friend_id2
                   WHERE mf1.friend_id1 = $friendId
                   AND mf2.friend_id1 = $userId";
    
    $mutualResult = mysqli_query($conn, $mutualQuery);
    $mutualRow = mysqli_fetch_assoc($mutualResult);
    $users[$key]['mutual_friends'] = $mutualRow['mutual_count'];
}

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Friends - My Friend System</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <?php 
    require_once 'navigation.php';
    renderNavigation('friendadd');
    ?>
    <div class="container">
        <header>
            <h1>My Friend System</h1>
            <h2><?php echo htmlspecialchars($profileName); ?>'s Add Friend Page</h2>
        </header>
        
        <main>
            <div class="friend-count">
                Total number of friends is <?php echo $numFriends; ?>
            </div>
            
            <div class="add-friend-list">
                <?php if (count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                        <div class="friend-item">
                            <div class="friend-info">
                                <span class="friend-name"><?php echo htmlspecialchars($user['profile_name']); ?></span>
                                <?php if ($user['mutual_friends'] > 0): ?>
                                    <span class="mutual-friends">(<?php echo $user['mutual_friends']; ?> mutual friend<?php echo $user['mutual_friends'] != 1 ? 's' : ''; ?>)</span>
                                <?php endif; ?>
                            </div>
                            <form method="POST" action="friendadd.php?page=<?php echo $currentPage; ?>" style="margin: 0;">
                                <input type="hidden" name="add_friend_id" value="<?php echo $user['friend_id']; ?>">
                                <button type="submit" class="btn btn-success">Add as friend</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Pagination Controls -->
                    <?php if ($totalPages > 1): ?>
                        <div class="pagination">
                            <?php if ($currentPage > 1): ?>
                                <a href="friendadd.php?page=<?php echo $currentPage - 1; ?>" class="btn btn-secondary">Previous</a>
                            <?php else: ?>
                                <span class="btn btn-secondary disabled">Previous</span>
                            <?php endif; ?>
                            
                            <span class="page-info">Page <?php echo $currentPage; ?> of <?php echo $totalPages; ?></span>
                            
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="friendadd.php?page=<?php echo $currentPage + 1; ?>" class="btn btn-secondary">Next</a>
                            <?php else: ?>
                                <span class="btn btn-secondary disabled">Next</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-center">You are already friends with everyone!</p>
                <?php endif; ?>
            </div>
            
            <div class="navigation mt-20">
                <a href="friendlist.php" class="btn">Friend List</a>
                <a href="logout.php" class="btn btn-secondary">Log out</a>
            </div>
        </main>
        
        <footer>
            <p>&copy; 2025 My Friend System | COS30020 Advanced Web Development</p>
        </footer>
    </div>
</body>
</html>
