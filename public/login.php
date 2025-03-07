<?php
session_start();
include '../include/db.php';

$host = "localhost";  
$user = "root";       
$pass = "";           
$dbname = "main_auth"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'], $_POST['password'])) {
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

                // ✅ Log login activity
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $timestamp = date('Y-m-d H:i:s');
                $log_query = "INSERT INTO system_logs (username, action, ip_address, timestamp) VALUES (?, 'Login', ?, ?)";
                $log_stmt = $conn->prepare($log_query);
                $log_stmt->bind_param("sss", $username, $ip_address, $timestamp);
                $log_stmt->execute();

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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['qr_data'])) {
    $qr_data = $_POST['qr_data']; 

    $query = "SELECT * FROM users WHERE qr_code=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $qr_data);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];

        // ✅ Log login activity
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $timestamp = date('Y-m-d H:i:s');
        $log_query = "INSERT INTO system_logs (username, action, ip_address, timestamp) VALUES (?, 'QR Login', ?, ?)";
        $log_stmt = $conn->prepare($log_query);
        $log_stmt->bind_param("sss", $row['username'], $ip_address, $timestamp);
        $log_stmt->execute();

        header("Location: ../admin/dashboard.php");
        exit();
    } else {
        $error = "Invalid QR Code!";
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
    <script src="https://unpkg.com/html5-qrcode"></script> 
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 25px;
            width: 380px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
            color: white;
        }

        h2 {
            margin-bottom: 15px;
        }

        .error {
            color: white;
            background: rgba(255, 0, 0, 0.7);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: none;
            outline: none;
            transition: 0.3s;
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        input::placeholder {
            color: #ddd;
        }

        input:focus {
            background: rgba(255, 255, 255, 0.3);
        }

        .input-group {
            position: relative;
            width: 100%;
        }

        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: white;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #ff9800;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border-radius: 8px;
            transition: 0.3s;
        }

        button:hover {
            background: #e68900;
            transform: scale(1.05);
        }

        .qr-scanner {
            margin-top: 20px;
            border: 2px dashed #ff9800;
            width: 100%;
            height: 250px;
            border-radius: 8px;
        }

        .link-container {
            margin-top: 15px;
        }

        .link-container a {
            color: #ffcc80;
            text-decoration: none;
            transition: 0.3s;
        }

        .link-container a:hover {
            text-decoration: underline;
        }

        a {
            color: #fff;
            font-size: 14px;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    
    <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>

    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <div class="input-group">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <span class="eye-icon" id="togglePassword">👁️</span>
        </div>
        <button type="submit">Login</button>
    </form>

    <h3>OR Scan QR Code</h3>
    <div id="qr-reader" class="qr-scanner"></div>
    <form method="post" id="qrForm">
        <input type="hidden" name="qr_data" id="qr_data">
    </form>

    <br>
    <div class="link-container">
        <a href="register.php">Create an account</a> | 
        <a href="forgot_password.php">Forgot password?</a>
    </div>
    <div class="link-container">
        <a href="../index.html">Landing Page</a>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        let passwordField = document.getElementById('password');
        passwordField.type = (passwordField.type === "password") ? "text" : "password";
        this.textContent = (passwordField.type === "password") ? "👁️" : "🔒";
    });

    // ✅ Initialize QR Scanner Properly
    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('qr_data').value = decodedText;
        document.getElementById('qrForm').submit();
    }

    function onScanFailure(error) {
        console.warn(`QR Code scan error: ${error}`);
    }

    // ✅ Ensure Scanner Loads After Page Loads
    window.addEventListener('load', function () {
        let qrScanner = new Html5QrcodeScanner("qr-reader", {
            fps: 10,
            qrbox: 250
        });

        qrScanner.render(onScanSuccess, onScanFailure);
    });
</script>


</body>
</html>
