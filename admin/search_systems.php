<?php
session_start();
include '../include/db.php';

if (!isset($_POST['lab'])) {
    exit();
}

$lab = $_POST['lab'];
$conn->select_db($lab);

// Handle System Deletion
if (isset($_POST['delete_system'])) {
    $system_id = $_POST['system_id'];
    $stmt = $conn->prepare("DELETE FROM `{$lab}_systems` WHERE id=?");
    $stmt->bind_param("i", $system_id);
    $stmt->execute();
}

// Handle System Update
if (isset($_POST['update_system'])) {
    $system_id = $_POST['system_id'];
    $cpu_id = $_POST['cpu_id'];
    $ups_id = $_POST['ups_id'];
    $monitor_id = $_POST['monitor_id'];
    $internet = $_POST['internet'];

    $stmt = $conn->prepare("UPDATE `{$lab}_systems` SET cpu_id=?, ups_id=?, monitor_id=?, internet=? WHERE id=?");
    $stmt->bind_param("ssssi", $cpu_id, $ups_id, $monitor_id, $internet, $system_id);
    $stmt->execute();
}

// Search Systems
$searchQuery = "";
if (isset($_POST['query'])) {
    $search = $conn->real_escape_string($_POST['query']);
    $searchQuery = "AND (c.cpu_id LIKE '%$search%' 
                    OR u.ups_id LIKE '%$search%' 
                    OR m.monitor_id LIKE '%$search%' 
                    OR s.issue LIKE '%$search%' 
                    OR s.description LIKE '%$search%')";
}

$query = "SELECT s.id, c.cpu_id, u.ups_id, m.monitor_id, s.internet, s.issue, s.description 
          FROM `{$lab}_systems` s
          JOIN `{$lab}_cpus` c ON s.cpu_id = c.id
          JOIN `{$lab}_upss` u ON s.ups_id = u.id
          JOIN `{$lab}_monitors` m ON s.monitor_id = m.id
          WHERE 1=1 $searchQuery";

$result = $conn->query($query);
$output = "";

while ($system = $result->fetch_assoc()) {
    $output .= "
        <tr>
            <td>{$system['id']}</td>
            <td>{$system['cpu_id']}</td>
            <td>{$system['ups_id']}</td>
            <td>{$system['monitor_id']}</td>
            <td>{$system['internet']}</td>
            <td>{$system['issue']}</td>
            <td>{$system['description']}</td>
            <td class='text-center'>
                <button type='button' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal{$system['id']}'>
                    <i class='fas fa-edit'></i> Edit
                </button>
                <form method='POST' style='display:inline;'>
                    <input type='hidden' name='system_id' value='{$system['id']}'>
                    <button type='submit' class='btn btn-danger btn-sm' name='delete_system'>
                        <i class='fas fa-trash'></i> Delete
                    </button>
                </form>
            </td>
        </tr>

        <!-- Edit Modal for System ID {$system['id']} -->
        <div class='modal fade' id='editModal{$system['id']}' tabindex='-1' aria-labelledby='editModalLabel{$system['id']}' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='editModalLabel{$system['id']}'>Edit System ID {$system['id']}</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                    </div>
                    <div class='modal-body'>
                        <form method='POST'>
                            <input type='hidden' name='system_id' value='{$system['id']}'>
                            <div class='mb-3'>
                                <label>CPU ID</label>
                                <input type='text' class='form-control' name='cpu_id' value='{$system['cpu_id']}' required>
                            </div>
                            <div class='mb-3'>
                                <label>UPS ID</label>
                                <input type='text' class='form-control' name='ups_id' value='{$system['ups_id']}' required>
                            </div>
                            <div class='mb-3'>
                                <label>Monitor ID</label>
                                <input type='text' class='form-control' name='monitor_id' value='{$system['monitor_id']}' required>
                            </div>
                            <div class='mb-3'>
                                <label>Internet</label>
                                <select name='internet' class='form-select'>
                                    <option value='Yes' ".(($system['internet'] == 'Yes') ? 'selected' : '').">Yes</option>
                                    <option value='No' ".(($system['internet'] == 'No') ? 'selected' : '').">No</option>
                                </select>
                            </div>
                            <button type='submit' class='btn btn-success w-100 mt-3' name='update_system'>
                                <i class='fas fa-save'></i> Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    ";
}

echo $output ?: "<tr><td colspan='8' class='text-center'>No results found</td></tr>";
?>
