<?php
/**
 * About Page - Assignment Report
 * COS30020 Assignment 2 - My Friend System
 */
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - My Friend System</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <?php 
    require_once 'navigation.php';
    renderNavigation('about');
    ?>
    <div class="container">
        <header>
            <h1>My Friend System</h1>
            <h2>About This Assignment</h2>
        </header>
        
        <main>
            <div class="about-section">
                <h3>Assignment Completion Status</h3>
                <ul>
                    <li><strong>What tasks you have not attempted or not completed?</strong>
                        <ul>
                            <li>All tasks from Part 1 through Part 4 have been successfully completed, including the Extra Challenge tasks.</li>
                        </ul>
                    </li>
                    
                    <li><strong>What special features have you done, or attempted, in creating the site that we should know about?</strong>
                        <ul>
                            <li>Implemented comprehensive server-side validation for all user inputs including email format validation, duplicate email checking, and password matching verification.</li>
                            <li>Created a reusable database connection module (settings.php) for consistent database access across all pages.</li>
                            <li>Developed a clean and responsive user interface with custom CSS styling that provides clear visual feedback for different actions.</li>
                            <li>Implemented bidirectional friendship relationships ensuring that when User A adds User B as a friend, User B also has User A as a friend automatically.</li>
                            <li>Added automatic friend count updates that recalculate whenever friendships are added or removed.</li>
                            <li>Used proper session management to maintain user authentication state throughout the application.</li>
                            <li><strong>Task 8 - Pagination:</strong> Implemented pagination on the Add Friends page that displays 10 users per page with Previous/Next navigation buttons. The pagination maintains state when adding friends.</li>
                            <li><strong>Task 9 - Mutual Friends:</strong> Added mutual friend count display for each user in the Add Friends list, showing how many friends you have in common with potential friends.</li>
                        </ul>
                    </li>
                    
                    <li><strong>Which parts did you have trouble with?</strong>
                        <ul>
                            <li>Initially had challenges with the bidirectional friendship logic in the myfriends table, ensuring that both friend_id1 and friend_id2 relationships were properly maintained.</li>
                            <li>Worked through the proper SQL queries to exclude already-friended users from the "Add Friends" list while also excluding the logged-in user themselves.</li>
                            <li>Ensured that the num_of_friends field stayed synchronized with actual friendship records after add/remove operations.</li>
                        </ul>
                    </li>
                    
                    <li><strong>What would you like to do better next time?</strong>
                        <ul>
                            <li>Implement password hashing for better security instead of storing plain text passwords in the database.</li>
                            <li>Create a more sophisticated search and filter system for finding friends, especially as the user base grows.</li>
                            <li>Add profile pictures and more detailed user profiles to make the system more engaging.</li>
                            <li>Implement better error logging and handling for database operations.</li>
                            <li>Add email verification during registration to ensure valid email addresses.</li>
                            <li>Improve the pagination UI with page numbers instead of just Previous/Next.</li>
                            <li>Add sorting options for the friend list (by name, date added, mutual friends, etc.).</li>
                        </ul>
                    </li>
                </ul>
            </div>
            
            <div class="about-section">
                <h3>Testing Instructions for Markers</h3>
                <p><strong>For demonstrating the Extra Challenge features (Tasks 8 & 9):</strong></p>
                <ul>
                    <li><strong>Sample Login Credentials:</strong>
                        <ul>
                            <li>Email: <code>emma.wilson@email.com</code> | Password: <code>pass123</code> (Has 6 friends)</li>
                            <li>Email: <code>james.brown@email.com</code> | Password: <code>pass456</code> (Has 7 friends)</li>
                            <li>Email: <code>sophia.taylor@email.com</code> | Password: <code>pass789</code> (Has 6 friends)</li>
                        </ul>
                    </li>
                    <li><strong>Pagination Demo (Task 8):</strong> Log in with any account above and navigate to "Add Friends" page. The system displays 10 users per page with Previous/Next buttons to navigate through multiple pages.</li>
                    <li><strong>Mutual Friends Demo (Task 9):</strong> When viewing the "Add Friends" page, each potential friend displays their mutual friend count in parentheses (e.g., "Oliver Davis (2 mutual friends)").</li>
                    <li><strong>Database Population:</strong> The system is populated with 25 sample users and 80+ friendship relationships to properly demonstrate all features including pagination and varied mutual friend counts.</li>
                </ul>
            </div>
            
            <div class="about-section">
                <h3>Navigation Links</h3>
                <ul>
                    <li><a href="friendlist.php">Friend List</a> - View and manage your current friends</li>
                    <li><a href="friendadd.php">Add Friends</a> - Discover and add new friends</li>
                    <li><a href="index.php">Home Page</a> - Return to the homepage</li>
                </ul>
            </div>
            
            <div class="about-section">
                <h3>Discussion Board Contribution</h3>
                <div class="screenshot">
                    <img src="1.png">
                    <img src="2.png">
                </div>
            </div>
            
            <div class="navigation mt-20">
                <a href="index.php" class="btn">Back to Home</a>
            </div>
        </main>
        
        <footer>
            <p>&copy; 2025 My Friend System | COS30020 Advanced Web Development</p>
        </footer>
    </div>
</body>
</html>
