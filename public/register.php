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
                    $success = "Registration successful! <a href='login.php' class='text-blue-500'>Login here</a>";
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
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-xl p-6 w-full max-w-md">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-4">Create an Account</h2>

        <?php if ($error): ?>
            <p class="text-red-500 text-center"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class="text-green-500 text-center"><?php echo $success; ?></p>
        <?php endif; ?>

        <form method="post" class="space-y-4">
            <div>
                <label class="block text-gray-700">Username</label>
                <input type="text" name="username" required
                       class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" name="rmail" required
                       class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="relative">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                       class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <span class="absolute right-3 top-9 cursor-pointer text-gray-500" onclick="togglePassword('password')">👁️</span>
            </div>

            <div class="relative">
                <label class="block text-gray-700">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required
                       class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <span class="absolute right-3 top-9 cursor-pointer text-gray-500" onclick="togglePassword('confirm_password')">👁️</span>
            </div>

            <button type="submit"
                    class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-200">
                Register
            </button>
        </form>

        <p class="text-center text-gray-600 mt-4">Already have an account? <a href="login.php" class="text-blue-500">Login here</a></p>
        <p class="text-center text-gray-600 mt-2">Register via QR Code? <a href="qr_reg.php" class="text-blue-500">Click here</a></p>
    </div>

    <script>
        function togglePassword(id) {
            var input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>

</body>
</html>
