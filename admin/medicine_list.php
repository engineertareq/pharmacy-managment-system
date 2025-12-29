<?php
include 'inc/db_connect.php'; 

/* UPDATE */
if(isset($_POST['update'])){
    $id     = $_POST['id'];
    $name   = $_POST['name'];
    $price  = $_POST['price'];
    $stock  = $_POST['stock'];
    $expiry = $_POST['expiry'];

    $conn->query("UPDATE medicines SET
        name='$name',
        sell_price='$price',
        stock_quantity='$stock',
        expiry_date='$expiry'
        WHERE medicine_id=$id
    ");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Medicine List</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

<div class="d-flex justify-content-between mb-3">
<h4>Medicine List</h4>
<a href="add_medicine.php" class="btn btn-primary">+ Add Medicine</a>
</div>

<table class="table table-bordered table-striped shadow">
<thead class="table-dark">
<tr>
<th>#</th>
<th>Name</th>
<th>Price</th>
<th>Stock</th>
<th>Expiry</th>
<th width="180">Action</th>
</tr>
</thead>

<tbody>
<?php
$i=1;
$res=$conn->query("SELECT * FROM medicines ORDER BY medicine_id DESC");
while($row=$res->fetch_assoc()){
?>
<tr>
<td><?= $i++ ?></td>
<td><?= htmlspecialchars($row['name']) ?></td>
<td><?= $row['sell_price'] ?></td>
<td><?= $row['stock_quantity'] ?></td>
<td><?= $row['expiry_date'] ?></td>
<td>
<a href="add_medicine.php?edit=<?= $row['medicine_id']?>" class="btn btn-warning btn-sm">Edit</a>
<a href="medicine_delete.php?id=<?= $row['medicine_id']?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Delete this medicine?')">
   Delete
</a>
</td>
</tr>

<?php if(isset($_GET['edit']) && $_GET['edit']==$row['medicine_id']){ ?>
<tr>
<td colspan="6">
<form method="post" class="card card-body bg-light">
<input type="hidden" name="id" value="<?= $row['medicine_id']?>">

<div class="row">
<div class="col-md-3">
<input name="name" value="<?= $row['name']?>" class="form-control" required>
</div>

<div class="col-md-2">
<input name="price" value="<?= $row['sell_price']?>" class="form-control" required>
</div>

<div class="col-md-2">
<input name="stock" value="<?= $row['stock_quantity']?>" class="form-control" required>
</div>

<div class="col-md-3">
<input type="date" name="expiry" value="<?= $row['expiry_date']?>" class="form-control" required>
</div>


</div>
</form>
</td>
</tr>
<?php } ?>

<?php } ?>
</tbody>
</table>

</div>
</body>
</html>
