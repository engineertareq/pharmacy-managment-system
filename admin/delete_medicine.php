<?php
include 'inc/db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_check = "SELECT image_url FROM medicines WHERE medicine_id = $id";
    $result = $conn->query($sql_check);
    $row = $result->fetch_assoc();

    $sql = "DELETE FROM medicines WHERE medicine_id = $id";

    if ($conn->query($sql) === TRUE) {
        
        if (!empty($row['image_url']) && file_exists($row['image_url'])) {
            unlink($row['image_url']);
        }

        header("Location: medicine_list.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    header("Location: medicine_list.php");
    exit();
}
?>