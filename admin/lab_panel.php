<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_GET['lab'])) {
    header("Location: ../public/login.php");
    exit();
}
$lab = $_GET['lab'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Management - <?php echo htmlspecialchars($lab); ?></title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Reset basic styles */
        body, h2, ul {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Container for the page */
        .container {
            background: white;
            padding: 40px;
            max-width: 900px;
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            box-sizing: border-box;
        }

        h2 {
            color: #333;
            font-size: 32px;
            margin-bottom: 20px;
        }

        p.lead {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }

        /* List of actions */
        ul.action-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 0;
            list-style: none;
            margin: 0;
        }

        ul.action-list .action-item {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            text-align: center;
            padding: 15px;
        }

        ul.action-list .action-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
        }

        ul.action-list .action-link {
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: #333;
            font-weight: 600;
            font-size: 18px;
            gap: 10px;  /* Adjust gap between icon and text */
        }

        ul.action-list .action-item i {
            font-size: 30px;
            color: #fff;
            background-color: #007bff;
            border-radius: 50%;
            padding: 20px;
            transition: background-color 0.3s ease;
        }

        ul.action-list .action-item:nth-child(1) i {
            background-color: #007bff; /* Blue */
        }

        ul.action-list .action-item:nth-child(2) i {
            background-color: #28a745; /* Green */
        }

        ul.action-list .action-item:nth-child(3) i {
            background-color: #ffc107; /* Yellow */
        }

        ul.action-list .action-item:nth-child(4) i {
            background-color: #17a2b8; /* Cyan */
        }

        ul.action-list .action-item:nth-child(5) i {
            background-color: #dc3545; /* Red */
        }

        ul.action-list .action-item:nth-child(6) i {
            background-color: #6f42c1; /* Purple */
        }

        /* Hover effect on action item */
        ul.action-list .action-item:hover i {
            background-color: #0056b3;
        }

        ul.action-list .action-item:hover .action-link {
            color: #007bff; /* Change text color on hover */
        }

        /* Back button styling */
        .back-btn {
            display: inline-block;
            margin-top: 40px;
            padding: 12px 25px;
            background: #28a745;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .back-btn:hover {
            background: #218838;
            transform: scale(1.05);
        }

        /* Media queries for responsiveness */
        @media (max-width: 768px) {
            .container {
                padding: 25px;
            }

            ul.action-list {
                grid-template-columns: 1fr;
            }

            ul.action-list .action-item i {
                font-size: 24px;
                padding: 18px;
            }

            ul.action-list .action-link {
                font-size: 16px;
            }

            .back-btn {
                padding: 10px 20px;
            }
        }
    </style>
</head>

<body>

<div class="container">
    <h2>Managing <?php echo htmlspecialchars($lab); ?></h2>
    <p class="lead">Choose an option below to manage the lab's systems, devices, stocks, and more.</p>

    <!-- Action List -->
    <ul class="action-list">
    <li class="action-item">
        <a href="manage_devices.php?lab=<?php echo $lab; ?>" class="action-link">
            <i class='bx bx-desktop'></i>
            <span>Manage Devices</span>
        </a>
    </li>
    <li class="action-item">
        <a href="manage_systems.php?lab=<?php echo $lab; ?>" class="action-link">
            <i class='bx bx-server'></i>
            <span>Manage Systems</span>
        </a>
    </li>
    <li class="action-item">
        <a href="manage_stocks.php?lab=<?php echo $lab; ?>" class="action-link">
            <i class='bx bx-box'></i>
            <span>Manage Stocks</span>
        </a>
    </li>
    <li class="action-item">
        <a href="print_receipt.php?lab=<?php echo $lab; ?>" class="action-link">
            <i class='bx bx-printer'></i>
            <span>Print Receipt</span>
        </a>
    </li>
    <li class="action-item">
        <a href="resolved_issues.php?lab=<?php echo $lab; ?>" class="action-link">
            <i class='bx bx-check-circle'></i>
            <span>Resolved Issues</span>
        </a>
    </li>
    <li class="action-item">
        <a href="system_logs.php?lab=<?php echo $lab; ?>" class="action-link">
            <i class='bx bx-list-ul'></i>
            <span>System Logs</span>
        </a>
    </li>
    </ul>

    <!-- Back Button -->
    <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>

</body>

</html>
