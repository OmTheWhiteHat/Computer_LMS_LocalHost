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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Google Font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }

        /* Navbar */
        .navbar {
            background-color: #007bff;
            padding: 15px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .navbar span {
            font-size: 20px;
            font-weight: bold;
        }
        .navbar a {
            text-decoration: none;
            color: white;
            font-weight: 500;
            margin-left: 15px;
            padding: 8px 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            transition: 0.3s ease-in-out;
        }
        .navbar a:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* Container */
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #333;
            font-weight: 600;
        }

        /* Lab Grid */
        .lab-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
            padding: 0;
        }

        .lab-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s;
        }
        .lab-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .lab-card h3 {
            margin: 0;
            color: #007bff;
            font-weight: 600;
        }
        .lab-card p {
            font-size: 14px;
            color: #666;
        }
        .lab-card a {
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            padding: 10px 15px;
            background: #28a745;
            color: white;
            border-radius: 5px;
            margin-top: 10px;
            transition: background 0.3s ease-in-out;
        }
        .lab-card a:hover {
            background: #218838;
        }

        /* Table */
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
            background: #cce5ff;
        }

        /* Logout Button */
        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease-in-out;
        }
        .logout-btn:hover {
            background: #b52b38;
        }
    </style>
</head>
<body>

<div class="navbar">
    <span>Lab Management System</span>
    <div>
        <a href="qr_profile.php"><i class="fas fa-qrcode"></i> My QR</a>
        <a href="../public/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<div class="container">
    <h2>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</h2>
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
