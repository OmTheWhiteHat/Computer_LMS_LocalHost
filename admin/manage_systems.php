<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_GET['lab'])) {
    header("Location: ../public/login.php");
    exit();
}

include '../include/db.php';
$lab = $_GET['lab'];
$conn->select_db($lab);

// Create table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS `{$lab}_systems` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpu_id VARCHAR(50) NOT NULL,
    ups_id VARCHAR(50) NOT NULL,
    monitor_id VARCHAR(50) NOT NULL,
    internet VARCHAR(10) NOT NULL,
    issue VARCHAR(255) NOT NULL,
    description TEXT
)");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_system'])) {
        $cpu_id = $_POST['cpu_id'];
        $ups_id = $_POST['ups_id'];
        $monitor_id = $_POST['monitor_id'];
        $internet = $_POST['internet'];
        $issue = $_POST['issue'];
        $description = $_POST['description'];
        
        $conn->query("INSERT INTO `{$lab}_systems` (cpu_id, ups_id, monitor_id, internet, issue, description) VALUES 
                      ('$cpu_id', '$ups_id', '$monitor_id', '$internet', '$issue', '$description')");
    } elseif (isset($_POST['update_system'])) {
        $system_id = $_POST['system_id'];
        $cpu_id = $_POST['cpu_id'];
        $ups_id = $_POST['ups_id'];
        $monitor_id = $_POST['monitor_id'];
        $internet = $_POST['internet'];
        $issue = $_POST['issue'];
        $description = $_POST['description'];
        
        $conn->query("UPDATE `{$lab}_systems` SET cpu_id='$cpu_id', ups_id='$ups_id', monitor_id='$monitor_id', 
                      internet='$internet', issue='$issue', description='$description' WHERE id=$system_id");
    }
}

$systems = $conn->query("SELECT * FROM `{$lab}_systems` ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Systems - <?php echo htmlspecialchars($lab); ?></title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2, h3 { text-align: center; color: #333; }
        .form-container { display: flex; justify-content: center; gap: 15px; margin-bottom: 20px; }
        input, select, button { padding: 10px; font-size: 16px; border-radius: 5px; border: 1px solid #ccc; }
        button { cursor: pointer; background-color: #007bff; color: white; border: none; }
        button:hover { background-color: #0056b3; }
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .back-btn { display: block; width: fit-content; margin: 20px auto; background-color: #28a745; padding: 10px 15px; color: white; border-radius: 5px; text-decoration: none; text-align: center; }
        .back-btn:hover { background-color: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Systems for <?php echo htmlspecialchars($lab); ?></h2>

        <h3>Add System</h3>
        <form method="POST" class="form-container">
            <input type="text" name="cpu_id" placeholder="CPU ID" required>
            <input type="text" name="ups_id" placeholder="UPS ID" required>
            <input type="text" name="monitor_id" placeholder="Monitor ID" required>
            <select name="internet">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
            <input type="text" name="issue" placeholder="Issue" required>
            <input type="text" name="description" placeholder="Description" required>
            <button type="submit" name="add_system">Add System</button>
        </form>

        <h3>Existing Systems</h3>
        <div class="table-container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>CPU ID</th>
                    <th>UPS ID</th>
                    <th>Monitor ID</th>
                    <th>Internet</th>
                    <th>Issue</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                <?php while ($system = $systems->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $system['id']; ?></td>
                    <td><?php echo $system['cpu_id']; ?></td>
                    <td><?php echo $system['ups_id']; ?></td>
                    <td><?php echo $system['monitor_id']; ?></td>
                    <td><?php echo $system['internet']; ?></td>
                    <td><?php echo $system['issue']; ?></td>
                    <td><?php echo $system['description']; ?></td>
                    <td>
                        <form method="POST" class="form-container" style="flex-direction: column;">
                            <input type="hidden" name="system_id" value="<?php echo $system['id']; ?>">
                            <input type="text" name="cpu_id" value="<?php echo $system['cpu_id']; ?>" required>
                            <input type="text" name="ups_id" value="<?php echo $system['ups_id']; ?>" required>
                            <input type="text" name="monitor_id" value="<?php echo $system['monitor_id']; ?>" required>
                            <select name="internet">
                                <option value="Yes" <?php echo ($system['internet'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                                <option value="No" <?php echo ($system['internet'] == 'No') ? 'selected' : ''; ?>>No</option>
                            </select>
                            <input type="text" name="issue" value="<?php echo $system['issue']; ?>" required>
                            <input type="text" name="description" value="<?php echo $system['description']; ?>" required>
                            <button type="submit" name="update_system">Update</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>

        <a href="lab_panel.php?lab=<?php echo $lab; ?>" class="back-btn">Back to Lab Panel</a>
    </div>
</body>
</html>
