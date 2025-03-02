/* db.php - Database Connection */
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "main_auth";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

/* logout.php - User Logout */
<?php
session_start();
session_destroy();
header("Location: login.php");
exit();
?>
