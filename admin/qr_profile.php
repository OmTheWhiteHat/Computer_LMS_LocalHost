<?php
session_start();
include '../include/db.php';

// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Database credentials
    $servername = "localhost";
    $db_user = "root"; // Your database username
    $db_password = ""; // Your database password (default is empty for XAMPP)
    $dbname = "main_auth"; // Your actual database name

    // Establish connection
    $conn = new mysqli($servername, $db_user, $db_password, $dbname);
    
    // Set charset to avoid encoding issues
    $conn->set_charset("utf8mb4");

    // Check if user is logged in
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    $username = $_SESSION['username'];

    // Prepare and execute query
    $query = "SELECT qr_code FROM users WHERE username=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // QR Code path
    $qr_file = "../qr_codes/" . $username . ".png"; 

} catch (mysqli_sql_exception $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My QR Code</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Google Font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
        }

        h2 {
            color: #333;
            font-weight: 600;
        }

        .qr-container {
            margin-top: 15px;
        }

        img {
            width: 200px;
            height: 200px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border: 4px solid #007bff;
            padding: 10px;
            background: white;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            color: white;
            background: #007bff;
            border-radius: 8px;
            transition: 0.3s ease-in-out;
            box-shadow: 0px 3px 10px rgba(0, 123, 255, 0.3);
            cursor: pointer;
            border: none;
        }

        .button:hover {
            background: #0056b3;
            box-shadow: 0px 3px 15px rgba(0, 123, 255, 0.5);
        }

        .button i {
            margin-right: 8px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Welcome, <strong><?php echo htmlspecialchars($username); ?></strong>!</h2>
        <p>Scan this QR Code to Login</p>
        <div class="qr-container">
            <img src="<?php echo $qr_file; ?>" alt="QR Code" id="qrImage">
        </div>

        <!-- Download Button -->
        <a href="<?php echo $qr_file; ?>" download="<?php echo $username; ?>_QR.png" class="button">
            <i class="fas fa-download"></i> Download QR Code
        </a>

        <br>

        <!-- Back Button -->
        <a href="dashboard.php" class="button">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

</body>
</html>
