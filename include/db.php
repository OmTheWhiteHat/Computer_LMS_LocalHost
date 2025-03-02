<?php
$servername = "localhost";
$username = "root";
$password = "";

// Connect to MySQL (no specific database selected)
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
