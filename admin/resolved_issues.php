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
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
            margin: 0;
            text-align: center;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        /* Form Styling */
        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        select, input {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }
        button {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }

        /* Table Styles */
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #d1e7fd;
        }

        /* Action Button Styles */
        .resolve-btn {
            padding: 8px 14px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .resolve-btn:hover {
            background-color: #218838;
        }
        .resolve-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        /* Back Button */
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #28a745;
            color: white;
            border-radius: 8px;
            text-decoration: none;
        }
        .back-btn:hover {
            background-color: #218838;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            select, input, button {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Issues for <?php echo htmlspecialchars($lab); ?></h2>

    <!-- Add Issue Form -->
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

    <!-- Pending Issues Table -->
    <div class="issues-section">
        <h3>Pending Issues</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Device Type</th>
                    <th>Issue Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
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
            </tbody>
        </table>
    </div>

    <!-- Resolved Issues Table -->
    <div class="issues-section">
        <h3>Resolved Issues</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Device Type</th>
                    <th>Issue Description</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($issue = $resolved_issues->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $issue['id']; ?></td>
                    <td><?php echo htmlspecialchars($issue['device_type']); ?></td>
                    <td><?php echo htmlspecialchars($issue['description']); ?></td>
                    <td><?php echo $issue['status']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <a href="lab_panel.php?lab=<?php echo $lab; ?>" class="back-btn">Back to Lab Panel</a>
</div>

</body>
</html>
