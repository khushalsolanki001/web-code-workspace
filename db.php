<?php
$host = 'localhost';
$username = 'root';          // Change to root
$password = '';              // Empty password (default in XAMPP)
$database = 'chat_db';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>