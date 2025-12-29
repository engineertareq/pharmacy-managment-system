<?php
include 'inc/db_connect.php'; 
if(isset($_POST['save'])){
    $name   = $_POST['name'];
    $price  = $_POST['price'];
    $stock  = $_POST['stock'];
    $expiry = $_POST['expiry'];

    $conn->query("INSERT INTO medicines 
    (name, sell_price, stock_quantity, expiry_date)
    VALUES ('$name','$price','$stock','$expiry')");

    header("Location: medicine_list.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Medicine</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
<div class="card shadow">
<div class="card-header bg-primary text-white">Add Medicine</div>
<div class="card-body">

<form method="post">
<div class="mb-3">
<label>Medicine Name</label>
<input type="text" name="name" class="form-control" required>
</div>

<div class="mb-3">
<label>Selling Price</label>
<input type="number" step="0.01" name="price" class="form-control" required>
</div>

<div class="mb-3">
<label>Stock Quantity</label>
<input type="number" name="stock" class="form-control" required>
</div>

<div class="mb-3">
<label>Expiry Date</label>
<input type="date" name="expiry" class="form-control" required>
</div>

<button name="save" class="btn btn-success">Save Medicine</button>
<a href="medicine_list.php" class="btn btn-secondary">Medicine List</a>

</form>

</div>
</div>
</div>

</body>
</html>
