<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_GET['lab'])) {
    header("Location: ../public/login.php");
    exit();
}

include '../include/db.php';
$lab = $_GET['lab'];
$conn->select_db($lab);

// Ensure the lab locations table exists
$conn->query("CREATE TABLE IF NOT EXISTS `{$lab}_locations` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lab_name VARCHAR(255) NOT NULL UNIQUE,
    building VARCHAR(255) NOT NULL,
    floor VARCHAR(50) NOT NULL,
    room_number VARCHAR(50) NOT NULL
)");


// Fetch stock items
$query = "SELECT * FROM `{$lab}_stocks`";
$result = $conn->query($query);

// Check if the query ran successfully
if (!$result) {
    die("Error fetching stock: " . $conn->error);
}

// Fetch total quantity
$total_items = 0;
$total_quantity = 0;
$items_data = [];

while ($row = $result->fetch_assoc()) {
    $items_data[] = $row;
    $total_items++;
    $total_quantity += $row['quantity'];
}

// Generate unique receipt number
$receipt_number = strtoupper(substr($lab, 0, 3)) . "-" . date("YmdHis") . "-" . rand(100, 999);

// Fetch current user
$username = $_SESSION['username'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Receipt - <?php echo htmlspecialchars($lab); ?></title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .receipt-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        .print-button, .primary-button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .print-button {
            background-color: #28a745;
        }
        .print-button:hover {
            background-color: #218838;
        }
        .primary-button {
            background-color: rgb(238, 68, 17);
        }
        .primary-button:hover {
            background-color: rgb(195, 45, 0);
        }
        .primary-button a {
            text-decoration: none;
            color: #fff;
        }
        .receipt-header {
            font-size: 18px;
            font-weight: bold;
        }
        .summary {
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="receipt-container" id="receipt">
        <h2>Lab Management System - Receipt</h2>
        <p class="receipt-header">Lab: <?php echo htmlspecialchars($lab); ?></p>
        <p><strong>Issued By:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p><strong>Date:</strong> <?php echo date("d-m-Y H:i:s"); ?></p>
        <p><strong>Receipt No:</strong> <?php echo $receipt_number; ?></p>
        <hr>

        <table>
            <tr>
                <th>ID</th>
                <th>Item Name</th>
                <th>Quantity</th>
            </tr>
            <?php foreach ($items_data as $row) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                <td><?php echo $row['quantity']; ?></td>
            </tr>
            <?php } ?>
        </table>

        <p class="summary">
            Total Items: <?php echo $total_items; ?> | Total Quantity: <?php echo $total_quantity; ?>
        </p>

        <hr>
        <p><strong>Contact:</strong> +91-9876543210 | Email: clmsbest@gmail.com</p>
    </div>

    <button class="print-button" onclick="window.print();">Print Receipt</button>
    <button class="primary-button">
        <a href="lab_panel.php?lab=<?php echo $lab; ?>" class="back-btn">Back to Lab Panel</a>
    </button>
</body>
</html>
