<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_GET['lab'])) {
    exit("Unauthorized access");
}

include '../include/db.php';
$lab = $_GET['lab'];
$query = $_GET['query'] ?? '';

$conn->select_db($lab);

if (!empty($query)) {
    $stmt = $conn->prepare("SELECT * FROM devices WHERE device_name LIKE ? ORDER BY time_added DESC");
    $search = "%$query%";
    $stmt->bind_param("s", $search);
} else {
    $stmt = $conn->prepare("SELECT * FROM devices ORDER BY time_added DESC");
}

$stmt->execute();
$result = $stmt->get_result();

while ($device = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$device['id']}</td>
            <td>{$device['device_name']}</td>
            <td>{$device['working_condition']}</td>
            <td>{$device['time_added']}</td>
            <td class='actions'>
                <form method='POST'>
                    <input type='hidden' name='device_id' value='{$device['id']}'>
                    <input type='text' name='device_name' value='" . htmlspecialchars($device['device_name']) . "' required>
                    <select name='working_condition'>
                        <option value='Good' " . ($device['working_condition'] == 'Good' ? 'selected' : '') . ">Good</option>
                        <option value='Needs Repair' " . ($device['working_condition'] == 'Needs Repair' ? 'selected' : '') . ">Needs Repair</option>
                        <option value='Not Working' " . ($device['working_condition'] == 'Not Working' ? 'selected' : '') . ">Not Working</option>
                    </select>
                    <button type='submit' name='update_device'><i class='fas fa-edit'></i> Update</button>
                </form>
                <form method='POST'>
                    <input type='hidden' name='device_id' value='{$device['id']}'>
                    <button type='submit' name='delete_device' class='delete-btn' onclick='return confirm(\"Are you sure?\")'><i class='fas fa-trash'></i> Delete</button>
                </form>
            </td>
          </tr>";
}
?>
