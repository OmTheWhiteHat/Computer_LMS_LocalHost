<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_GET['lab'])) {
    header("Location: ../public/login.php");
    exit();
}
include '../include/db.php';

$conn->select_db('main_auth'); // ✅ Ensure the correct database is selected

$lab = $_GET['lab'];
$labs = ["lab_A", "lab_B", "lab_C", "lab_D"]; // Available labs

$logs = $conn->query("SELECT * FROM `system_logs` ORDER BY timestamp DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Logs</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
            font-size: 1.1rem;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e0f7ff;
        }

        /* Back Button Styles */
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #218838;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            table {
                font-size: 0.9rem;
            }

            th, td {
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>System Logs</h2>

    <!-- Log Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Timestamp</th>
                <th>Action</th>
                <th>Username</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($log = $logs->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $log['id']; ?></td>
                <td><?php echo $log['timestamp']; ?></td>
                <td><?php echo isset($log['action']) ? htmlspecialchars($log['action']) : 'N/A'; ?></td>
                <td><?php echo htmlspecialchars($log['username']); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="lab_panel.php?lab=<?php echo $lab; ?>" class="back-btn">Back to Lab Panel</a>
</div>

</body>
</html>
