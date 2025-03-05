<?php
session_start();
include '../include/db.php';

if (!isset($_SESSION['username']) || !isset($_GET['lab'])) {
    exit("Unauthorized access");
}

$lab = $_GET['lab'];
$conn->select_db($lab);
$search = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

$query = "SELECT * FROM `{$lab}_issues` WHERE status='Pending' 
          AND (device_type LIKE '%$search%' OR description LIKE '%$search%')";

$result = $conn->query($query);

while ($issue = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$issue['id']}</td>
            <td>" . htmlspecialchars($issue['device_type']) . "</td>
            <td>" . htmlspecialchars($issue['description']) . "</td>
            <td>{$issue['status']}</td>
            <td>
                <form method='post' style='display:inline;'>
                    <input type='hidden' name='issue_id' value='{$issue['id']}'>
                    <button type='submit' name='resolve_issue' class='resolve-btn'>Resolve</button>
                </form>
            </td>
          </tr>";
}
?>
