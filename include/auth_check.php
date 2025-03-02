<?php
session_start();

// Restrict access to logged-in users
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
