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
        $quantity = $_POST['quantity'];
        $query = "INSERT INTO `{$lab}_stocks` (item_name, quantity) VALUES ('$item_name', '$quantity')";
        $conn->query($query);
    } elseif (isset($_POST['update_stock'])) {
        $stock_id = $_POST['stock_id'];
        $item_name = $_POST['item_name'];
        $quantity = $_POST['quantity'];
        $query = "UPDATE `{$lab}_stocks` SET item_name='$item_name', quantity='$quantity' WHERE id=$stock_id";
        $conn->query($query);
    } elseif (isset($_POST['delete_stock'])) {
        $stock_id = $_POST['stock_id'];
        $query = "DELETE FROM `{$lab}_stocks` WHERE id=$stock_id";
        $conn->query($query);
    }
}

$stocks = $conn->query("SELECT * FROM `{$lab}_stocks`");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Stocks - <?php echo htmlspecialchars($lab); ?></title>
    <link rel="stylesheet" href="../assests/style.css">
</head>
<body>
    <h2>Manage Stocks for <?php echo htmlspecialchars($lab); ?></h2>
    
    <h3>Add Stock Item</h3>
    <form method="POST">
        <input type="text" name="item_name" placeholder="Item Name" required>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <button type="submit" name="add_stock">Add</button>
    </form>
    
    <h3>Existing Stock</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Actions</th>
        </tr>
        <?php while ($stock = $stocks->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $stock['id']; ?></td>
            <td><?php echo $stock['item_name']; ?></td>
            <td><?php echo $stock['quantity']; ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="stock_id" value="<?php echo $stock['id']; ?>">
                    <input type="text" name="item_name" value="<?php echo $stock['item_name']; ?>" required>
                    <input type="number" name="quantity" value="<?php echo $stock['quantity']; ?>" required>
                    <button type="submit" name="update_stock">Update</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="stock_id" value="<?php echo $stock['id']; ?>">
                    <button type="submit" name="delete_stock">Delete</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
    
    <a href="lab_panel.php?lab=<?php echo $lab; ?>">Back to Lab Panel</a>
</body>
</html>
