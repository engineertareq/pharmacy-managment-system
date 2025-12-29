<?php
// delete_order_item.php
include 'inc/db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($conn->query("DELETE FROM order_items WHERE item_id = $id") === TRUE) {
        header("Location: view_order_items.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>