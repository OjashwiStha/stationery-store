<?php
// Database credentials
$host = "localhost";        // Host where the DB is running (localhost for XAMPP)
$username = "root";         // Default MySQL username in XAMPP
$password = "";             // Default password is empty in XAMPP
$database = "ecommerce";  // The database name you created

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Optional: Set charset to utf8 for special characters
mysqli_set_charset($conn, "utf8");
?>
