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
<html>
<head>
    <title>System Logs</title>
    <link rel="stylesheet" href="../assests/style.css">
</head>
<body>
    <h2>System Logs</h2>
    
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Timestamp</th>
            <th>Action</th>
            <th>Username</th>
        </tr>
        <?php while ($log = $logs->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $log['id']; ?></td>
            <td><?php echo $log['timestamp']; ?></td>
            <td><?php echo isset($log['action']) ? $log['action'] : 'N/A'; ?></td>
            <td><?php echo $log['username']; ?></td>
        </tr>
        <?php } ?>
    </table>
    <br>
    <a href="lab_panel.php?lab=<?php echo $lab; ?>">Back to Lab Panel</a>
</body>
</html>
