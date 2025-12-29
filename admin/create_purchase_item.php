<?php 
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php'; 

$purchases_res = $conn->query("SELECT purchase_id, invoice_no FROM purchases");
$medicines_res = $conn->query("SELECT medicine_id, name FROM medicines");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $purchase_id = $_POST['purchase_id'];
    $medicine_id = $_POST['medicine_id'];
    $quantity = $_POST['quantity'];
    $cost_price = $_POST['cost_price'];
    $batch_no = $_POST['batch_no'];
    $expiry_date = $_POST['expiry_date'];

    $conn->begin_transaction();

    try {

        $sql_insert = "INSERT INTO purchase_items (purchase_id, medicine_id, quantity, cost_price, batch_no, expiry_date) 
                       VALUES ('$purchase_id', '$medicine_id', '$quantity', '$cost_price', '$batch_no', '$expiry_date')";
        
        if (!$conn->query($sql_insert)) {
            throw new Exception("Error adding item: " . $conn->error);
        }

        $sql_update = "UPDATE medicines SET stock_quantity = stock_quantity + $quantity WHERE medicine_id = '$medicine_id'";
        
        if (!$conn->query($sql_update)) {
            throw new Exception("Error updating stock: " . $conn->error);
        }

        $conn->commit();
        $message = "<div class='alert alert-success'>Item added and Stock updated successfully!</div>";

    } catch (Exception $e) {
        $conn->rollback();
        $message = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
    }
}
?>

<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Add Purchase Item</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium"><a href="index.php">Dashboard</a></li>
            <li>-</li>
            <li class="fw-medium">New Item</li>
        </ul>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Item Details</h5>
        </div>
        <div class="card-body">
            <?php echo $message; ?>
            <form method="POST" action="">
                <div class="row gy-3">
                    
                    <div class="col-md-6">
                        <label class="form-label">Purchase Invoice</label>
                        <select name="purchase_id" class="form-select" required>
                            <option value="">Select Invoice</option>
                            <?php 
                            if ($purchases_res && $purchases_res->num_rows > 0) {
                                while($row = $purchases_res->fetch_assoc()) {
                                    echo "<option value='".$row['purchase_id']."'>Invoice #".$row['invoice_no']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Medicine</label>
                        <select name="medicine_id" class="form-select" required>
                            <option value="">Select Medicine</option>
                            <?php 
                            if ($medicines_res && $medicines_res->num_rows > 0) {
                                while($row = $medicines_res->fetch_assoc()) {
                                    echo "<option value='".$row['medicine_id']."'>".$row['name']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" placeholder="100" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Cost Price</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control" placeholder="0.00" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Batch No</label>
                        <input type="text" name="batch_no" class="form-control" placeholder="BATCH-001">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control" required>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary-600">Add Item</button>
                        <a href="view_purchase_items.php" class="btn btn-secondary-600">View List</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include './partials/layouts/layoutBottom.php' ?>