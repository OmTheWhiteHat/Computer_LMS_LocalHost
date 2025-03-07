<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../include/db.php';
include '../include/phpqrcode/qrlib.php';

$host = "localhost";  
$user = "root";       
$pass = "";           
$dbname = "main_auth"; 

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure QR code directory exists & is writable
$qr_dir = "../qr_codes/";
if (!is_dir($qr_dir)) {
    if (!mkdir($qr_dir, 0777, true)) {
        die("Failed to create QR code directory.");
    }
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // ✅ Check if the username exists and fetch the hashed password
    $check_query = "SELECT password_hash FROM users WHERE username = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        $message = "Error: Username does not exist. Please enter a valid username.";
    } else {
        $stmt->bind_result($stored_hash);
        $stmt->fetch();

        // ✅ Verify the entered password with the stored hash
        if (!password_verify($password, $stored_hash)) {
            $message = "Error: Incorrect password. Please try again.";
        } else {
            // ✅ Generate unique QR data
            $qr_data = uniqid("user_" . $username . "_");
            $qr_filename = $username . ".png"; 
            $qr_path = $qr_dir . $qr_filename;

            // ✅ Generate QR code
            QRcode::png($qr_data, $qr_path, 'L', 5);
            chmod($qr_path, 0777); // Ensure it's readable

            // ✅ Update QR code in the database for the existing user
            $update_query = "UPDATE users SET qr_code = ? WHERE username = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ss", $qr_data, $username);

            if ($update_stmt->execute()) {
                header("Location: register.php?success=1&qr=" . urlencode($qr_filename));
                exit();
            } else {
                die("Database Error: " . $update_stmt->error);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-xl p-6 w-full max-w-md">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-4">QR Code Registration</h2>

        <?php if (isset($_GET['success']) && isset($_GET['qr'])): ?>
            <p class="text-green-600 text-center">QR registration successful! Scan your QR code to log in:</p>
            <div class="flex justify-center my-4">
                <img src="qr_codes/<?php echo htmlspecialchars($_GET['qr']); ?>" alt="QR Code" class="border rounded-lg shadow-md">
            </div>
            <div class="text-center">
                <a href="login.php" class="text-blue-600 hover:underline">Go to Login</a>
            </div>
        <?php else: ?>
            <?php if ($message): ?>
                <p class="text-red-500 text-center"><?php echo $message; ?></p>
            <?php endif; ?>

            <form method="post" class="space-y-4">
                <div>
                    <label class="block text-gray-700">Username (must be an existing user):</label>
                    <input type="text" name="username" required
                           class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-gray-700">Password:</label>
                    <input type="password" name="password" required
                           class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <button type="submit"
                        class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-200">
                    Register QR
                </button>
                <div class="text-center">
                <a href="login.php" class="text-blue-600 hover:underline">Go to Login</a>
            </div>
            </form>
        <?php endif; ?>
    </div>

</body>
</html>
