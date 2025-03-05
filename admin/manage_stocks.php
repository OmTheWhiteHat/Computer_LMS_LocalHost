<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_GET['lab'])) {
    header("Location: ../public/login.php");
    exit();
}

include '../include/db.php';
$lab = $_GET['lab'];
$conn->select_db($lab);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_stock'])) {
        $item_name = $_POST['item_name'];
        $quantity = (int)$_POST['quantity']; // Ensure quantity is an integer

        // Check if quantity is non-negative
        if ($quantity < 0) {
            echo "<script>alert('Quantity cannot be negative');</script>";
        } else {
            $query = "INSERT INTO `{$lab}_stocks` (item_name, quantity) VALUES ('$item_name', '$quantity')";
            $conn->query($query);
        }
    } elseif (isset($_POST['update_stock'])) {
        $stock_id = $_POST['stock_id'];
        $item_name = $_POST['item_name'];
        $quantity = (int)$_POST['quantity']; // Ensure quantity is an integer

        // Check if quantity is non-negative
        if ($quantity < 0) {
            echo "<script>alert('Quantity cannot be negative');</script>";
        } else {
            $query = "UPDATE `{$lab}_stocks` SET item_name='$item_name', quantity='$quantity' WHERE id=$stock_id";
            $conn->query($query);
        }
    } elseif (isset($_POST['delete_stock'])) {
        $stock_id = $_POST['stock_id'];
        $query = "DELETE FROM `{$lab}_stocks` WHERE id=$stock_id";
        $conn->query($query);
    }
}

$stocks = $conn->query("SELECT * FROM `{$lab}_stocks`");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Stocks - <?php echo htmlspecialchars($lab); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css"> <!-- You can modify this to your path if needed -->
    <style>
        /* Custom Styling */
        body {
            background-color: #f4f7fa;
            font-family: 'Arial', sans-serif;
        }
        
        .container {
            max-width: 1200px;
            margin-top: 50px;
        }

        h2 {
            color: #007bff;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 20px;
            font-weight: bold;
        }

        .card-body {
            background-color: white;
            padding: 30px;
        }

        .form-control {
            border-radius: 8px;
            box-shadow: none;
            font-size: 16px;
        }

        .form-control:focus {
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.6);
            border-color: #007bff;
        }

        .btn-primary, .btn-warning, .btn-danger {
            border-radius: 5px;
            padding: 10px 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        table {
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }

        .actions-btns form {
            display: inline;
        }

        .mt-4 a {
            text-align: center;
            display: block;
            padding: 10px 20px;
            font-size: 16px;
            text-decoration: none;
            background-color: #6c757d;
            color: white;
            border-radius: 5px;
        }
        
        .mt-4 a:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Stocks for <?php echo htmlspecialchars($lab); ?></h2>

        <!-- Add Stock Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">Add Stock Item</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" name="item_name" class="form-control" placeholder="Item Name" required>
                        </div>
                        <div class="col-md-6">
                            <input type="number" name="quantity" class="form-control" placeholder="Quantity" required min="0">
                        </div>
                    </div>
                    <button type="submit" name="add_stock" class="btn btn-primary">Add Stock</button>
                </form>
            </div>
        </div>

        <!-- Existing Stock Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Existing Stock</h5>
            </div>
            <div class="m-3">
                <input type="text" id="searchStock" class="form-control" placeholder="Search stock items...">
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="stockTable">
                        <?php while ($stock = $stocks->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $stock['id']; ?></td>
                            <td><?php echo $stock['item_name']; ?></td>
                            <td><?php echo $stock['quantity']; ?></td>
                            <td class="actions-btns">
                                <!-- Update Stock Form -->
                                <form method="POST">
                                    <input type="hidden" name="stock_id" value="<?php echo $stock['id']; ?>">
                                    <input type="text" name="item_name" value="<?php echo $stock['item_name']; ?>" class="form-control mb-2" required>
                                    <input type="number" name="quantity" value="<?php echo $stock['quantity']; ?>" class="form-control mb-2" required min="0">
                                    <button type="submit" name="update_stock" class="btn btn-warning btn-sm">Update</button>
                                </form>
                                
                                <!-- Delete Stock Form -->
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="stock_id" value="<?php echo $stock['id']; ?>">
                                    <button type="submit" name="delete_stock" class="btn btn-danger btn-sm ms-2">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $("#searchStock").on("keyup", function () {
        let query = $(this).val();
        let lab = "<?php echo htmlspecialchars($lab); ?>";
        
        $.ajax({
            url: "search_stock.php",
            method: "GET",
            data: { lab: lab, query: query },
            success: function (data) {
                $("#stockTable").html(data);
            }
        });
    });
});
</script>

                </table>
            </div>
        </div>

        <div class="mt-4">
            <a href="lab_panel.php?lab=<?php echo $lab; ?>" class="btn btn-secondary">Back to Lab Panel</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
