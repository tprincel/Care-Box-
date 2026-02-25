<?php
// Database Configuration File
// This file connects to your MySQL database

// Database credentials - UPDATE THESE FOR INFINITYFREE
// For InfinityFree, use the credentials provided in your control panel
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');  // Change to your InfinityFree DB username
define('DB_PASSWORD', '');      // Change to your InfinityFree DB password
define('DB_NAME', 'carebox_db'); // Change to your InfinityFree DB name

// NOTE: For InfinityFree hosting:
// 1. Create database in InfinityFree control panel
// 2. Import carebox_db.sql to that database
// 3. Update the credentials above with your InfinityFree DB details

// Create database connection
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to handle special characters
    $conn->set_charset("utf8mb4");
    
    return $conn;
}

// Helper function to close connection
function closeDBConnection($conn) {
    $conn->close();
}
?>
