<?php
session_start();
include '../include/db.php';

if (!isset($_GET['lab']) || !isset($_GET['query'])) {
    exit();
}

$lab = $_GET['lab'];
$search = $_GET['query'];

$conn->select_db($lab);

$query = "SELECT * FROM `{$lab}_stocks` 
          WHERE item_name LIKE '%$search%'";

$result = $conn->query($query);
$output = "";

while ($stock = $result->fetch_assoc()) {
    $output .= "
        <tr>
            <td>{$stock['id']}</td>
            <td>{$stock['item_name']}</td>
            <td>{$stock['quantity']}</td>
            <td class='actions-btns'>
                <form method='POST'>
                    <input type='hidden' name='stock_id' value='{$stock['id']}'>
                    <input type='text' name='item_name' value='{$stock['item_name']}' class='form-control mb-2' required>
                    <input type='number' name='quantity' value='{$stock['quantity']}' class='form-control mb-2' required min='0'>
                    <button type='submit' name='update_stock' class='btn btn-warning btn-sm'>Update</button>
                </form>
                <form method='POST' style='display:inline;'>
                    <input type='hidden' name='stock_id' value='{$stock['id']}'>
                    <button type='submit' name='delete_stock' class='btn btn-danger btn-sm ms-2'>Delete</button>
                </form>
            </td>
        </tr>";
}

echo $output ?: "<tr><td colspan='4' class='text-center'>No results found</td></tr>";
?>
