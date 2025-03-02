<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_GET['lab'])) {
    header("Location: ../public/login.php");
    exit();
}
include '../include/db.php';
$lab = $_GET['lab'];
$conn->select_db($lab); // ✅ Ensure the correct database is selected

// ✅ Add Issue with Device Type
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_issue'])) {
        $description = $conn->real_escape_string($_POST['description']);
        $device_type = $conn->real_escape_string($_POST['device_type']);
        $query = "INSERT INTO `{$lab}_issues` (device_type, description, status) VALUES ('$device_type', '$description', 'Pending')";
        $conn->query($query);
    } elseif (isset($_POST['resolve_issue'])) {
        $issue_id = $_POST['issue_id'];
        $query = "UPDATE `{$lab}_issues` SET status='Resolved' WHERE id=$issue_id";
        $conn->query($query);
    }
}

// ✅ Fetch Active & Resolved Issues
$active_issues = $conn->query("SELECT * FROM `{$lab}_issues` WHERE status='Pending'");
$resolved_issues = $conn->query("SELECT * FROM `{$lab}_issues` WHERE status='Resolved'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Issues - <?php echo htmlspecialchars($lab); ?></title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        tr:hover {
            background: #d1e7fd;
        }
        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        input, select {
            width: 80%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 15px;
            border: none;
            background: #28a745;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        .resolve-btn {
            background: red;
            margin-left: 10px;
        }
        .resolve-btn:hover {
            background: darkred;
        }
        .back-btn {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Issues for <?php echo htmlspecialchars($lab); ?></h2>

    <!-- ✅ Form to Add Issues -->
    <form method="post">
        <select name="device_type" required>
            <option value="">Select Device Type</option>
            <option value="Computer">Computer</option>
            <option value="CPU">CPU</option>
            <option value="Projector">Projector</option>
            <option value="Printer">Printer</option>
            <option value="Network">Network</option>
            <option value="Keyboard">Keyboard</option>
            <option value="Mouse">Mouse</option>
            <option value="Monitor">Monitor</option>
            <option value="Software">Software</option>
            <option value="Operating_System">Operating System</option>
            <option value="Router">Router</option>
        </select>
        <input type="text" name="description" placeholder="Enter issue description..." required>
    
        <button type="submit" name="add_issue">Add Issue</button>
    </form>
<br>
    <!-- ✅ Active Issues -->
    <h3>Pending Issues</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Device Type</th>
            <th>Issue Description</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($issue = $active_issues->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $issue['id']; ?></td>
            <td><?php echo htmlspecialchars($issue['device_type']); ?></td>
            <td><?php echo htmlspecialchars($issue['description']); ?></td>
            <td><?php echo $issue['status']; ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="issue_id" value="<?php echo $issue['id']; ?>">
                    <button type="submit" name="resolve_issue" class="resolve-btn">Resolve</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
<br>
    <!-- ✅ Resolved Issues -->
    <h3>Resolved Issues</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Device Type</th>
            <th>Issue Description</th>
            <th>Status</th>
        </tr>
        <?php while ($issue = $resolved_issues->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $issue['id']; ?></td>
            <td><?php echo htmlspecialchars($issue['device_type']); ?></td>
            <td><?php echo htmlspecialchars($issue['description']); ?></td>
            <td><?php echo $issue['status']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <a href="lab_panel.php?lab=<?php echo $lab; ?>" class="back-btn">Back to Lab Panel</a>
</div>

</body>
</html>
