<?php
include 'inc/db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM suppliers WHERE supplier_id = $id";
    if ($conn->query($sql) === TRUE) {

        echo "<script>
                alert('Supplier deleted successfully!');
                window.location.href = 'view_suppliers.php';
              </script>";
    } else {

        echo "Error deleting record: " . $conn->error;
    }
} else {

    header("Location: view_suppliers.php");
    exit();
}
?>