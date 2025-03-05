<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_GET['lab'])) {
    header("Location: ../public/login.php");
    exit();
}
include '../include/db.php';
$lab = $_GET['lab'];
$conn->select_db($lab); // ✅ Ensure the correct database is selected

$query = "SELECT * FROM `{$lab}_stocks`";
$result = $conn->query($query);
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
        .print-button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .print-button:hover {
            background-color: #218838;
        }
        .primary-button
        {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color:rgb(238, 68, 17);
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .primary-button:hover
        {
            background-color: rgb(195, 45, 0);
        }
        .primary-button a
        {
            text-decoration: none;
            color : #fff;
        }
    </style>
</head>
<body>
    <div class="receipt-container" id="receipt">
        <h2>Lab Management System - Receipt</h2>
        <p><strong>Lab:</strong> <?php echo htmlspecialchars($lab); ?></p>
        <table>
            <tr>
                <th>ID</th>
                <th>Item Name</th>
                <th>Quantity</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                <td><?php echo $row['quantity']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
    <button class="print-button" onclick="window.print();">Print Receipt</button>
    <button class="primary-button"><a href="lab_panel.php?lab=<?php echo $lab; ?>" class="back-btn">Back to Lab Panel</a></button>
    <!-- <script>
        function printReceipt() {
            let printContent = document.getElementById("receipt").innerHTML;
            window.print();
            button.className('print-button').style.display === "None";
            document.body.innerHTML = printContent;
            
            location.reload();
            button.className('print-button').style.display === "Block";
        }
    </script> -->
</body>
</html>
