<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_GET['lab'])) {
    header("Location: ../public/login.php");
    exit();
}
$lab = $_GET['lab'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Management - <?php echo htmlspecialchars($lab); ?></title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
        .container {
            background: white;
            padding: 25px;
            max-width: 700px;
            margin:0 20px 0 20px ;
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            background: #007bff;
            margin: 10px 0;
            border-radius: 5px;
            padding: 12px;
            transition: 0.3s;
        }
        ul li:hover {
            background: #0056b3;
        }
        ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        ul li a i {
            margin-right: 10px;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background: #28a745;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }
        .back-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Managing <?php echo htmlspecialchars($lab); ?></h2>
    <ul>
        <li><a href="manage_devices.php?lab=<?php echo $lab; ?>"><i class="fas fa-desktop"></i> Manage Devices</a></li>
        <li><a href="manage_systems.php?lab=<?php echo $lab; ?>"><i class="fas fa-server"></i> Manage Systems</a></li>
        <li><a href="manage_stocks.php?lab=<?php echo $lab; ?>"><i class="fas fa-box"></i> Manage Stocks</a></li>
        <li><a href="print_receipt.php?lab=<?php echo $lab; ?>"><i class="fas fa-print"></i> Print Receipt</a></li>
        <li><a href="resolved_issues.php?lab=<?php echo $lab; ?>"><i class="fas fa-check-circle"></i> Resolved Issues</a></li>
        <li><a href="system_logs.php?lab=<?php echo $lab; ?>"><i class="fas fa-list-alt"></i> System Logs</a></li>
    </ul>
    <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>

</body>
</html>
