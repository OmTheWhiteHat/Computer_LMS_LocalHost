<?php 
session_start();
if (!isset($_SESSION['username']) || !isset($_GET['lab'])) {
    header("Location: ../public/login.php");
    exit();
}

include '../include/db.php';
$lab = $_GET['lab'];  

$conn->select_db($lab);

// ✅ Auto-create table if it doesn’t exist
$conn->query("CREATE TABLE IF NOT EXISTS devices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    device_name VARCHAR(255) NOT NULL,
    working_condition ENUM('Good', 'Needs Repair', 'Not Working') DEFAULT 'Good',
    time_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $device_name = trim($_POST['device_name'] ?? '');
    $working_condition = $_POST['working_condition'] ?? 'Good';
    
    if (isset($_POST['add_device']) && !empty($device_name)) {
        $conn->query("INSERT INTO devices (device_name, working_condition) VALUES ('$device_name', '$working_condition')");
    } elseif (isset($_POST['update_device'])) {
        $device_id = $_POST['device_id'];
        $conn->query("UPDATE devices SET device_name='$device_name', working_condition='$working_condition' WHERE id=$device_id");
    } elseif (isset($_POST['delete_device'])) {
        $device_id = $_POST['device_id'];
        $conn->query("DELETE FROM devices WHERE id=$device_id");
    }
}

$devices = $conn->query("SELECT * FROM devices ORDER BY time_added DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Devices - <?php echo htmlspecialchars($lab); ?></title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            width: 90%;
            max-width: 800px;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h2, h3 {
            color: #333;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        input, select, button {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
        }
        button:hover {
            background-color: #0056b3;
        }
        .delete-btn {
            background-color: #dc3545;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
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
        .actions {
            display: flex;
            justify-content: center;
            gap: 5px;
        }
        .actions form {
            display: inline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Devices for <?php echo htmlspecialchars($lab); ?></h2>

    <h3>Add Device</h3>
    <form method="POST">
        <input type="text" name="device_name" placeholder="Device Name" required>
        <select name="working_condition">
            <option value="Good">Good</option>
            <option value="Needs Repair">Needs Repair</option>
            <option value="Not Working">Not Working</option>
        </select>
        <button type="submit" name="add_device"><i class="fas fa-plus"></i> Add</button>
    </form>

    <h3>Existing Devices</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Device Name</th>
            <th>Condition</th>
            <th>Time Added</th>
            <th>Actions</th>
        </tr>
        <?php while ($device = $devices->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $device['id']; ?></td>
            <td><?php echo $device['device_name']; ?></td>
            <td><?php echo $device['working_condition']; ?></td>
            <td><?php echo $device['time_added']; ?></td>
            <td class="actions">
                <form method="POST">
                    <input type="hidden" name="device_id" value="<?php echo $device['id']; ?>">
                    <input type="text" name="device_name" value="<?php echo $device['device_name']; ?>" required>
                    <select name="working_condition">
                        <option value="Good" <?php echo ($device['working_condition'] == 'Good') ? 'selected' : ''; ?>>Good</option>
                        <option value="Needs Repair" <?php echo ($device['working_condition'] == 'Needs Repair') ? 'selected' : ''; ?>>Needs Repair</option>
                        <option value="Not Working" <?php echo ($device['working_condition'] == 'Not Working') ? 'selected' : ''; ?>>Not Working</option>
                    </select>
                    <button type="submit" name="update_device"><i class="fas fa-edit"></i> Update</button>
                </form>
                <form method="POST">
                    <input type="hidden" name="device_id" value="<?php echo $device['id']; ?>">
                    <button type="submit" name="delete_device" class="delete-btn" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> Delete</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

    <a href="lab_panel.php?lab=<?php echo $lab; ?>" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Lab Panel</a>
</div>

</body>
</html>
