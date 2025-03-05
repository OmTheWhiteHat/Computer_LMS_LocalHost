<?php
include '../include/db.php';
$conn->select_db('main_auth');

$query = isset($_GET['query']) ? $_GET['query'] : '';

$sql = "SELECT * FROM system_logs WHERE username LIKE '%$query%' ORDER BY timestamp DESC";
$result = $conn->query($sql);

while ($log = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$log['id']}</td>
        <td>{$log['timestamp']}</td>
        <td>" . (isset($log['action']) ? htmlspecialchars($log['action']) : 'N/A') . "</td>
        <td>" . htmlspecialchars($log['username']) . "</td>
    </tr>";
}
?>
