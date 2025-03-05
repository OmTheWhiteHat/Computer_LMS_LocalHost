<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_GET['lab'])) {
    header("Location: ../public/login.php");
    exit();
}

include '../include/db.php';
$lab = $_GET['lab'];
$conn->select_db($lab);

// Ensure tables exist
$conn->query("CREATE TABLE IF NOT EXISTS `{$lab}_cpus` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpu_id VARCHAR(50) UNIQUE NOT NULL
)");

$conn->query("CREATE TABLE IF NOT EXISTS `{$lab}_upss` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ups_id VARCHAR(50) UNIQUE NOT NULL
)");

$conn->query("CREATE TABLE IF NOT EXISTS `{$lab}_monitors` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    monitor_id VARCHAR(50) UNIQUE NOT NULL
)");

$conn->query("CREATE TABLE IF NOT EXISTS `{$lab}_systems` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpu_id INT NOT NULL,
    ups_id INT NOT NULL,
    monitor_id INT NOT NULL,
    internet VARCHAR(10) NOT NULL,
    issue VARCHAR(255) NOT NULL,
    description TEXT,
    FOREIGN KEY (cpu_id) REFERENCES `{$lab}_cpus`(id) ON DELETE CASCADE,
    FOREIGN KEY (ups_id) REFERENCES `{$lab}_upss`(id) ON DELETE CASCADE,
    FOREIGN KEY (monitor_id) REFERENCES `{$lab}_monitors`(id) ON DELETE CASCADE
)");

function getOrInsertID($conn, $table, $column, $value) {
    $stmt = $conn->prepare("SELECT id FROM `{$table}` WHERE {$column} = ?");
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['id'];
    }
    $stmt = $conn->prepare("INSERT INTO `{$table}` ({$column}) VALUES (?)");
    $stmt->bind_param("s", $value);
    $stmt->execute();
    return $stmt->insert_id;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_system'])) {
        $cpu_id = getOrInsertID($conn, "{$lab}_cpus", "cpu_id", $_POST['cpu_id']);
        $ups_id = getOrInsertID($conn, "{$lab}_upss", "ups_id", $_POST['ups_id']);
        $monitor_id = getOrInsertID($conn, "{$lab}_monitors", "monitor_id", $_POST['monitor_id']);
        $internet = $_POST['internet'];
        $issue = $_POST['issue'];
        $description = $_POST['description'];

        $stmt = $conn->prepare("INSERT INTO `{$lab}_systems` (cpu_id, ups_id, monitor_id, internet, issue, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisss", $cpu_id, $ups_id, $monitor_id, $internet, $issue, $description);
        $stmt->execute();
    }

    if (isset($_POST['update_system'])) {
        $system_id = $_POST['system_id'];
        $cpu_id = getOrInsertID($conn, "{$lab}_cpus", "cpu_id", $_POST['cpu_id']);
        $ups_id = getOrInsertID($conn, "{$lab}_upss", "ups_id", $_POST['ups_id']);
        $monitor_id = getOrInsertID($conn, "{$lab}_monitors", "monitor_id", $_POST['monitor_id']);
        $internet = $_POST['internet'];
        $issue = $_POST['issue'];
        $description = $_POST['description'];

        $stmt = $conn->prepare("UPDATE `{$lab}_systems` SET cpu_id=?, ups_id=?, monitor_id=?, internet=?, issue=?, description=? WHERE id=?");
        $stmt->bind_param("iiisssi", $cpu_id, $ups_id, $monitor_id, $internet, $issue, $description, $system_id);
        $stmt->execute();
    }

    if (isset($_POST['delete_system'])) {
        $system_id = $_POST['system_id'];
        $stmt = $conn->prepare("DELETE FROM `{$lab}_systems` WHERE id=?");
        $stmt->bind_param("i", $system_id);
        $stmt->execute();
    }
}

$systems = $conn->query("SELECT s.id, c.cpu_id, u.ups_id, m.monitor_id, s.internet, s.issue, s.description
FROM `{$lab}_systems` s
JOIN `{$lab}_cpus` c ON s.cpu_id = c.id
JOIN `{$lab}_upss` u ON s.ups_id = u.id
JOIN `{$lab}_monitors` m ON s.monitor_id = m.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Systems - <?php echo htmlspecialchars($lab); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container mt-4">
        <!-- Add System Form -->
        <h3 class="text-center mb-4">Add New System</h3>
        <form method="POST" class="needs-validation" novalidate>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="cpu_id">CPU ID</label>
                    <input type="text" class="form-control" name="cpu_id" placeholder="Enter CPU ID" required>
                </div>
                <div class="col-md-4 form-group">
                    <label for="ups_id">UPS ID</label>
                    <input type="text" class="form-control" name="ups_id" placeholder="Enter UPS ID" required>
                </div>
                <div class="col-md-4 form-group">
                    <label for="monitor_id">Monitor ID</label>
                    <input type="text" class="form-control" name="monitor_id" placeholder="Enter Monitor ID" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4 form-group">
                    <label for="internet">Internet Connectivity</label>
                    <select name="internet" class="form-select">
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label for="issue">Issue</label>
                    <input type="text" class="form-control" name="issue" placeholder="Enter Issue" required>
                </div>
                <div class="col-md-4 form-group">
                    <label for="description">Description</label>
                    <input type="text" class="form-control" name="description" placeholder="Enter Description" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-100 mt-3" name="add_system">
                <i class="fas fa-plus"></i> Add System
            </button>
        </form>
    </div>

    <div class="container mt-5">
        <h2 class="text-center">Manage Systems for <?php echo htmlspecialchars($lab); ?></h2>
        <table class="table table-striped table-bordered">
            <thead>
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
            </thead>
            <tbody>
                <?php while ($system = $systems->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $system['id']; ?></td>
                        <td><?php echo $system['cpu_id']; ?></td>
                        <td><?php echo $system['ups_id']; ?></td>
                        <td><?php echo $system['monitor_id']; ?></td>
                        <td><?php echo $system['internet']; ?></td>
                        <td><?php echo $system['issue']; ?></td>
                        <td><?php echo $system['description']; ?></td>
                        <td class="text-center">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="system_id" value="<?php echo $system['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" name="delete_system">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $system['id']; ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Edit Modal -->
        <?php while ($system = $systems->fetch_assoc()) { ?>
        <div class="modal fade" id="editModal<?php echo $system['id']; ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit System ID <?php echo $system['id']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <input type="hidden" name="system_id" value="<?php echo $system['id']; ?>">
                            <div class="mb-3">
                                <label>CPU ID</label>
                                <input type="text" class="form-control" name="cpu_id" value="<?php echo $system['cpu_id']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>UPS ID</label>
                                <input type="text" class="form-control" name="ups_id" value="<?php echo $system['ups_id']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Monitor ID</label>
                                <input type="text" class="form-control" name="monitor_id" value="<?php echo $system['monitor_id']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Internet</label>
                                <select name="internet" class="form-select">
                                    <option value="Yes" <?php echo ($system['internet'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                                    <option value="No" <?php echo ($system['internet'] == 'No') ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Issue</label>
                                <input type="text" class="form-control" name="issue" value="<?php echo $system['issue']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <input type="text" class="form-control" name="description" value="<?php echo $system['description']; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100 mt-3" name="update_system">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</body>
</html>
