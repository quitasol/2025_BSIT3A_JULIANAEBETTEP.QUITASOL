<?php
$host = "localhost"; // Change if needed
$username = "root"; // Database username
$password = ""; // Database password
$database = "btog"; // Your database name

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
