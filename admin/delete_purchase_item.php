<?php
// delete_purchase_item.php
include 'inc/db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM purchase_items WHERE p_item_id=$id");
    header("Location: view_purchase_items.php");
}
?>