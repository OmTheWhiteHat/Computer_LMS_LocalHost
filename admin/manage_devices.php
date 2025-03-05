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
        $stmt = $conn->prepare("INSERT INTO devices (device_name, working_condition) VALUES (?, ?)");
        $stmt->bind_param("ss", $device_name, $working_condition);
        $stmt->execute();
    } elseif (isset($_POST['update_device'])) {
        $device_id = $_POST['device_id'];
        $stmt = $conn->prepare("UPDATE devices SET device_name=?, working_condition=? WHERE id=?");
        $stmt->bind_param("ssi", $device_name, $working_condition, $device_id);
        $stmt->execute();
    } elseif (isset($_POST['delete_device'])) {
        $device_id = $_POST['device_id'];
        $stmt = $conn->prepare("DELETE FROM devices WHERE id=?");
        $stmt->bind_param("i", $device_id);
        $stmt->execute();
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
    <script>
        function searchLogs() {
            let input = document.getElementById("searchDevice").value;
            let xhr = new XMLHttpRequest();
            xhr.open("GET", "search_devices.php?lab=<?php echo $lab; ?>&query=" + encodeURIComponent(input), true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("logsTable").innerHTML = xhr.responseText;
                }
            }; 
            xhr.send();
        }
    </script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            background: white;
            width: 100%;
            max-width: 1000px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2, h3 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        input, select, button {
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 80%;
            max-width: 400px;
        }

        button {
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
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
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
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
            justify-content: space-evenly;
            gap: 10px;
        }

        .actions form {
            display: inline-block;
            width: 48%;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                flex-direction: column;
                align-items: center;
            }
            input, select, button {
                width: 100%;
            }
            table th, table td {
                font-size: 14px;
            }
            .actions {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Devices for <?php echo htmlspecialchars($lab); ?></h2>
    <input type="text" id="searchDevice" placeholder="Search by Device Name..." onkeyup="searchLogs()">

    <h3>Add New Device</h3>
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
        <thead>
            <tr>
                <th>ID</th>
                <th>Device Name</th>
                <th>Condition</th>
                <th>Time Added</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="logsTable">
            <?php while ($device = $devices->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $device['id']; ?></td>
                <td><?php echo $device['device_name']; ?></td>
                <td><?php echo $device['working_condition']; ?></td>
                <td><?php echo $device['time_added']; ?></td>
                <td class="actions">
                    <form method="POST">
                        <input type="hidden" name="device_id" value="<?php echo $device['id']; ?>">
                        <input type="text" name="device_name" value="<?php echo htmlspecialchars($device['device_name']); ?>" required>
                        <select name="working_condition">
                            <option value="Good" <?php echo ($device['working_condition'] == 'Good') ? 'selected' : ''; ?>>Good</option>
                            <option value="Needs Repair" <?php echo ($device['working_condition'] == 'Needs Repair') ? 'selected' : ''; ?>>Needs Repair</option>
                            <option value="Not Working" <?php echo ($device['working_condition'] == 'Not Working') ? 'selected' : ''; ?>>Not Working</option>
                        </select>
                        <button type="submit" name="update_device"><i class="fas fa-edit"></i> Update</button>
                    </form>
                    <form method="POST">
                        <input type="hidden" name="device_id" value="<?php echo $device['id']; ?>">
                        <button type="submit" name="delete_device" class="delete-btn" onclick="return confirm('Are you sure you want to delete this device?')"><i class="fas fa-trash"></i> Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="lab_panel.php?lab=<?php echo $lab; ?>" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Lab Panel</a>
</div>

</body>
</html>
