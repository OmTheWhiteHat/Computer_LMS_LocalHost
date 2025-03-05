<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../public/login.php");
    exit();
}
include '../include/db.php';
$labs = ["lab_A", "lab_B", "lab_C", "lab_D"]; // Available labs
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }
        .navbar {
            background-color: #333;
            padding: 15px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
        }
        h2 {
            margin-top: 20px;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .lab-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
            padding: 0;
        }
        .lab-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }
        .lab-card:hover {
            transform: scale(1.05);
        }
        .lab-card a {
            text-decoration: none;
            font-weight: bold;
            color: #333;
            display: block;
            padding: 10px;
            background: #28a745;
            color: white;
            border-radius: 5px;
            margin-top: 10px;
        }
        .lab-card a:hover {
            background: #218838;
        }
        .logout-btn {
            background: red;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .logout-btn:hover {
            background: darkred;
        }
    </style>
</head>
<body>

<div class="navbar">
    <span>Lab Management System</span>
    <a href="../public/logout.php" class="logout-btn">Logout</a>
</div>

<div class="container">
    <h2>Welcome, <?php echo $_SESSION['username']; ?> !</h2>
    <h3>Select a Lab to Manage:</h3>
    
    <div class="lab-grid">
        <?php foreach ($labs as $lab) { ?>
            <div class="lab-card">
                <h3><?php echo strtoupper($lab); ?></h3>
                <p>Manage devices, systems, and logs of <?php echo strtoupper($lab); ?></p>
                <a href="lab_panel.php?lab=<?php echo $lab; ?>">Manage</a>
            </div>
        <?php } ?>
    </div>
</div>

<div class="container"> 
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        margin-top: 20px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    th, td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ddd;
        border-right: 1px solid #ddd;
    }
    th {
        background: #007bff;
        color: white;
        font-size: 16px;
    }
    tr:nth-child(even) {
        background: #f2f2f2;
    }
    tr:hover {
        background:rgb(84, 199, 199);
    }
</style>
    <table>
        <?php 
        $conn->select_db("main_auth");

        // Fetch lab information from the labs table
        $query = "SELECT lab_name, database_name FROM labs";
        $labcode = $conn->query($query);
        ?>
        <tr>
            <th>Lab Name</th>
            <th>Lab Code</th>
        </tr>
        <?php while ($lab1 = $labcode->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($lab1['database_name']); ?></td>
            <td><?php echo htmlspecialchars($lab1['lab_name']); ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
