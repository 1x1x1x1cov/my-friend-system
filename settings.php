<?php
/**
 * Database Connection Settings
 * COS30020 Assignment 2 - My Friend System
 */

// Database configuration
define('DB_HOST', 'XXX');
define('DB_USER', 'XXX');
define('DB_PASS', 'XXX');
define('DB_NAME', 'XXX');

/**
 * Get database connection
 * @return mysqli Database connection object
 */
function getDBConnection() {
    $conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }
    
    return $conn;
}

/**
 * Close database connection
 * @param mysqli $conn Database connection object
 */
function closeDBConnection($conn) {
    if ($conn) {
        mysqli_close($conn);
    }
}
?>
