<?php
session_start();
include '../include/db.php';

$host = "localhost";  
$user = "root";       
$pass = "";           
$dbname = "main_auth"; 

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $query = "SELECT * FROM users WHERE username=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password_hash'])) {
                $_SESSION['username'] = $username;
                header("Location: ../admin/dashboard.php");
                exit();
            } else {
                $error = "Invalid password!";
            }
        } else {
            $error = "User not found!";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 20px;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .error {
            color: red;
            background: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .input-group { position: relative; width: 100%; }
        input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
        .eye-icon { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; }
        button { width: 100%; padding: 10px; background: #007bff; border: none; color: white; cursor: pointer; }
        .link-container {
            margin-top: 15px;
        }
        .link-container a {
            text-decoration: none;
            color: #007bff;
            font-size: 14px;
        }
        .link-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    
    <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>

    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required>
        <div class="input-group">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <span class="eye-icon" id="password-icon" onclick="togglePassword('password')">👁️‍🗨️</span>
        </div>
        <button type="submit">Login</button>
    </form>

    <div class="link-container">
        <a href="register.php">Create an account</a> | 
        <a href="forgot_password.php">Forgot password?</a>
    </div>
    <br>
    <a href="../index.html">Landing Page</a>
</div>
<script>
        function togglePassword(id) {
            var input = document.getElementById(id);
            var icon = document.getElementById(id + "-icon");
            if (input.type === "password") {
                input.type = "text";
                icon.textContent = "👁️";
            } else {
                input.type = "password";
                icon.textContent = "👁️‍🗨️";
            }
        }
    </script>
</body>
</html>
