<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_GET['lab'])) {
    header("Location: ../public/login.php");
    exit();
}
include '../include/db.php';
$lab = $_GET['lab'];
$conn->select_db($lab); // ✅ Ensure the correct database is selected

$logs = $conn->query("SELECT * FROM `{$lab}_logs` ORDER BY timestamp DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>System Logs - <?php echo htmlspecialchars($lab); ?></title>
    <link rel="stylesheet" href="../assests/style.css">
</head>
<body>
    <h2>System Logs for <?php echo htmlspecialchars($lab); ?></h2>
    
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Timestamp</th>
            <th>Activity</th>
            <th>Username</th>
        </tr>
        <?php while ($log = $logs->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $log['id']; ?></td>
            <td><?php echo $log['timestamp']; ?></td>
            <td><?php echo $log['activity']; ?></td>
            <td><?php echo $log['username']; ?></td>
        </tr>
        <?php } ?>
    </table>
    
    <a href="lab_panel.php?lab=<?php echo $lab; ?>">Back to Lab Panel</a>
</body>
</html>
