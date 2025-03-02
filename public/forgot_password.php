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

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure all fields are set
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

    if (!empty($username) && !empty($email) && !empty($new_password) && !empty($confirm_password)) {
        if ($new_password === $confirm_password) {
            $query = "SELECT * FROM users WHERE username=? AND rmail=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database
                $update_query = "UPDATE users SET password_hash=? WHERE username=? AND rmail=?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("sss", $hashed_password, $username, $email);
                if ($stmt->execute()) {
                    $message = "Password has been reset successfully!";
                } else {
                    $message = "Error updating password. Please try again.";
                }
            } else {
                $message = "User not found. Please check your details.";
            }
        } else {
            $message = "Passwords do not match!";
        }
    } else {
        $message = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: white; padding: 20px; width: 350px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); text-align: center; }
        h2 { margin-bottom: 15px; }
        input, button { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; }
        button { background: #007bff; border: none; color: white; cursor: pointer; }
        button:hover { background: #0056b3; }
        .message { color: red; margin-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Reset Password</h2>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Enter your username" required>
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="password" name="new_password" placeholder="Enter new password" required>
        <input type="password" name="confirm_password" placeholder="Confirm new password" required>
        <button type="submit">Change Password</button>
    </form>
    <p class="message"><?php echo $message; ?></p>
    <a href="login.php">Back to Login</a>
</div>

</body>
</html>
