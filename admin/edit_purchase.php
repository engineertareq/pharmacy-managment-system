<?php 
// edit_purchase.php
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php';

$id = $_GET['id'];
$purchase_data = $conn->query("SELECT * FROM purchases WHERE purchase_id = $id")->fetch_assoc();
$suppliers_result = $conn->query("SELECT supplier_id, company_name FROM suppliers");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplier_id = $_POST['supplier_id'];
    $invoice_no = $_POST['invoice_no'];
    $total_amount = $_POST['total_amount'];
    $purchase_date = $_POST['purchase_date'];

    $sql = "UPDATE purchases SET 
            supplier_id='$supplier_id', 
            invoice_no='$invoice_no', 
            total_amount='$total_amount', 
            purchase_date='$purchase_date' 
            WHERE purchase_id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.href='view_purchases.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<div class="dashboard-main-body">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Purchase</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row gy-3">
                    <div class="col-md-6">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <?php 
                            while($sup = $suppliers_result->fetch_assoc()) {
                                $selected = ($sup['supplier_id'] == $purchase_data['supplier_id']) ? 'selected' : '';
                                echo "<option value='".$sup['supplier_id']."' $selected>".$sup['company_name']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Invoice No</label>
                        <input type="text" name="invoice_no" class="form-control" value="<?php echo $purchase_data['invoice_no']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Total Amount</label>
                        <input type="number" step="0.01" name="total_amount" class="form-control" value="<?php echo $purchase_data['total_amount']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control" value="<?php echo $purchase_data['purchase_date']; ?>" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-warning">Update Purchase</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include './partials/layouts/layoutBottom.php' ?>