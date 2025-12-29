<?php 
// edit_medicine.php
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php'; 

// 1. Check & Fetch ID
if (!isset($_GET['id'])) {
    echo "Invalid ID"; exit;
}
$id = $_GET['id'];

// 2. Fetch Existing Medicine Data
$sql_med = "SELECT * FROM medicines WHERE medicine_id = $id";
$result_med = $conn->query($sql_med);

if ($result_med->num_rows == 0) {
    echo "Medicine not found"; exit;
}
$med = $result_med->fetch_assoc();

// 3. Fetch Categories & Suppliers for Dropdowns
// Note: Ensure 'category_id' matches your DB column name
$categories = $conn->query("SELECT category_id, name FROM categories");
$suppliers = $conn->query("SELECT supplier_id, company_name FROM suppliers");

$message = "";

// 4. Handle Update Request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $generic_name = $_POST['generic_name'];
    $sku = $_POST['sku'];
    $category_id = $_POST['category_id'];
    $supplier_id = $_POST['supplier_id'];
    $buy_price = $_POST['buy_price'];
    $sell_price = $_POST['sell_price'];
    $stock_quantity = $_POST['stock_quantity'];
    $batch_number = $_POST['batch_number'];
    $expiry_date = $_POST['expiry_date'];
    $status = $_POST['status'];

    // Image Upload Logic (Keep old image if no new one uploaded)
    $image_url = $med['image_url']; 

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $target_file = $target_dir . time() . "_" . basename($_FILES["image"]["name"]);
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $target_file; // Update variable with new path
        }
    }

    $update_sql = "UPDATE medicines SET 
            name='$name', 
            generic_name='$generic_name', 
            sku='$sku', 
            category_id='$category_id', 
            supplier_id='$supplier_id', 
            buy_price='$buy_price', 
            sell_price='$sell_price', 
            stock_quantity='$stock_quantity', 
            batch_number='$batch_number', 
            expiry_date='$expiry_date', 
            image_url='$image_url', 
            status='$status' 
            WHERE medicine_id=$id";

    if ($conn->query($update_sql) === TRUE) {
        echo "<script>
                alert('Medicine updated successfully!'); 
                window.location.href='medicine_list.php';
              </script>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Edit Medicine</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium"><a href="index.php">Dashboard</a></li>
            <li>-</li>
            <li class="fw-medium"><a href="medicine_list.php">Medicines</a></li>
            <li>-</li>
            <li class="fw-medium">Edit</li>
        </ul>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Update Information</h5>
        </div>
        <div class="card-body">
            <?php echo $message; ?>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="row gy-3">
                    
                    <div class="col-md-6">
                        <label class="form-label">Medicine Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($med['name']); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Generic Name</label>
                        <input type="text" name="generic_name" class="form-control" value="<?php echo htmlspecialchars($med['generic_name']); ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" class="form-control" value="<?php echo htmlspecialchars($med['sku']); ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">Select Category</option>
                            <?php 
                            if ($categories->num_rows > 0) {
                                while($cat = $categories->fetch_assoc()) { 
                                    $selected = ($cat['category_id'] == $med['category_id']) ? 'selected' : '';
                                    echo "<option value='".$cat['category_id']."' $selected>".$cat['name']."</option>"; 
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select">
                            <option value="">Select Supplier</option>
                            <?php 
                            if ($suppliers->num_rows > 0) {
                                while($sup = $suppliers->fetch_assoc()) { 
                                    $selected = ($sup['supplier_id'] == $med['supplier_id']) ? 'selected' : '';
                                    echo "<option value='".$sup['supplier_id']."' $selected>".$sup['company_name']."</option>"; 
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Buy Price</label>
                        <input type="number" step="0.01" name="buy_price" class="form-control" value="<?php echo $med['buy_price']; ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Sell Price</label>
                        <input type="number" step="0.01" name="sell_price" class="form-control" value="<?php echo $med['sell_price']; ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="form-control" value="<?php echo $med['stock_quantity']; ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Batch Number</label>
                        <input type="text" name="batch_number" class="form-control" value="<?php echo htmlspecialchars($med['batch_number']); ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control" value="<?php echo $med['expiry_date']; ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" <?php if($med['status'] == 1) echo 'selected'; ?>>Active</option>
                            <option value="0" <?php if($med['status'] == 0) echo 'selected'; ?>>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Update Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        
                        <?php if (!empty($med['image_url'])): ?>
                            <div class="mt-2">
                                <span class="text-sm text-secondary">Current Image:</span><br>
                                <img src="<?php echo $med['image_url']; ?>" alt="Current Image" width="80" class="rounded border">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-warning px-4">Update Medicine</button>
                        <a href="medicine_list.php" class="btn btn-secondary-600 px-4">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include './partials/layouts/layoutBottom.php' ?>