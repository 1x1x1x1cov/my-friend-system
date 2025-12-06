<?php
/**
 * Logout Page - Clear session and redirect
 * COS30020 Assignment 2 - My Friend System
 */

session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to home page
header('Location: index.php');
exit();
?>
