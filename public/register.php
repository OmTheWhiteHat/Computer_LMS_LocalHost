<?php
session_start();
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
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['rmail']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password !== $confirm_password) {
            $error = "Passwords do not match!";
        } else {
            // Check if username or email already exists
            $query = "SELECT * FROM users WHERE username=? OR rmail=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "Username or Email already taken!";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert user into database
                $insert_query = "INSERT INTO users (username, password_hash, rmail) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("sss", $username, $hashed_password, $email);
                if ($stmt->execute()) {
                    $success = "Registration successful! <a href='login.php'>Login here</a>";
                } else {
                    $error = "Registration failed!";
                }
            }
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
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="../assets/style.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: white; padding: 20px; width: 350px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); text-align: center; }
        h2 { margin-bottom: 15px; }
        .input-group { position: relative; width: 100%; }
        input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
        .eye-icon { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; }
        button { width: 100%; padding: 10px; background: #007bff; border: none; color: white; cursor: pointer; }
        button:hover { background: #0056b3; }
        .message { color: red; margin-top: 10px; }
        .success { color: green; }
    </style>
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
</head>
<body>

<div class="container">
    <h2>Register</h2>
    <?php if ($error) { echo "<p class='message'>$error</p>"; } ?>
    <?php if ($success) { echo "<p class='success'>$success</p>"; } ?>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="rmail" placeholder="Email" required>

        <div class="input-group">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <span class="eye-icon" id="password-icon" onclick="togglePassword('password')">👁️‍🗨️</span>
        </div>

        <div class="input-group">
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
            <span class="eye-icon" id="confirm_password-icon" onclick="togglePassword('confirm_password')">👁️‍🗨️</span>
        </div>

        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>
