<?php 
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php'; 

if (!isset($_GET['id'])) {
    echo "Invalid ID"; exit;
}

$id = $_GET['id'];
$item = $conn->query("SELECT * FROM purchase_items WHERE p_item_id = $id")->fetch_assoc();
$purchases_res = $conn->query("SELECT purchase_id, invoice_no FROM purchases");
$medicines_res = $conn->query("SELECT medicine_id, name FROM medicines");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $purchase_id = $_POST['purchase_id'];
    $medicine_id = $_POST['medicine_id'];
    $quantity = $_POST['quantity'];
    $cost_price = $_POST['cost_price'];
    $batch_no = $_POST['batch_no'];
    $expiry_date = $_POST['expiry_date'];

    $sql = "UPDATE purchase_items SET 
            purchase_id='$purchase_id', 
            medicine_id='$medicine_id', 
            quantity='$quantity', 
            cost_price='$cost_price', 
            batch_no='$batch_no', 
            expiry_date='$expiry_date' 
            WHERE p_item_id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Updated Successfully'); window.location.href='view_purchase_items.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<div class="dashboard-main-body">
    <div class="card">
        <div class="card-header"><h5 class="card-title mb-0">Edit Item</h5></div>
        <div class="card-body">
            <form method="POST">
                <div class="row gy-3">
                    <div class="col-md-6">
                        <label class="form-label">Invoice</label>
                        <select name="purchase_id" class="form-select">
                            <?php while($p = $purchases_res->fetch_assoc()) {
                                $sel = ($p['purchase_id'] == $item['purchase_id']) ? 'selected' : '';
                                echo "<option value='".$p['purchase_id']."' $sel>#".$p['invoice_no']."</option>";
                            } ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Medicine</label>
                        <select name="medicine_id" class="form-select">
                            <?php while($m = $medicines_res->fetch_assoc()) {
                                $sel = ($m['medicine_id'] == $item['medicine_id']) ? 'selected' : '';
                                echo "<option value='".$m['medicine_id']."' $sel>".$m['name']."</option>";
                            } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="<?php echo $item['quantity']; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Cost Price</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control" value="<?php echo $item['cost_price']; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Batch No</label>
                        <input type="text" name="batch_no" class="form-control" value="<?php echo $item['batch_no']; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control" value="<?php echo $item['expiry_date']; ?>">
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include './partials/layouts/layoutBottom.php' ?>