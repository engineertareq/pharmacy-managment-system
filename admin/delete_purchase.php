<?php
include 'inc/db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($conn->query("DELETE FROM purchases WHERE purchase_id=$id") === TRUE) {
        header("Location: view_purchases.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>