<?php 
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php';


$suppliers_result = $conn->query("SELECT supplier_id, company_name FROM suppliers");

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplier_id = $_POST['supplier_id'];
    $invoice_no = $_POST['invoice_no'];
    $total_amount = $_POST['total_amount'];
    $purchase_date = $_POST['purchase_date'];

    $sql = "INSERT INTO purchases (supplier_id, invoice_no, total_amount, purchase_date) 
            VALUES ('$supplier_id', '$invoice_no', '$total_amount', '$purchase_date')";

    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert alert-success'>Purchase recorded successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Add Purchase</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium"><a href="index.php">Dashboard</a></li>
            <li>-</li>
            <li class="fw-medium">New Purchase</li>
        </ul>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Purchase Information</h5>
        </div>
        <div class="card-body">
            <?php echo $message; ?>
            <form method="POST" action="">
                <div class="row gy-3">
                    
                    <div class="col-md-6">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">Select Supplier</option>
                            <?php 
                            if ($suppliers_result->num_rows > 0) {
                                while($sup = $suppliers_result->fetch_assoc()) {
                                    echo "<option value='".$sup['supplier_id']."'>".$sup['company_name']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Invoice No</label>
                        <input type="text" name="invoice_no" class="form-control" placeholder="#INV-0001" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Total Amount</label>
                        <input type="number" step="0.01" name="total_amount" class="form-control" placeholder="0.00" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary-600">Save Purchase</button>
                        <a href="view_purchases.php" class="btn btn-secondary-600">View List</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include './partials/layouts/layoutBottom.php' ?>