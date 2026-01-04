<?php 
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php'; 

// Fetch categories and suppliers for the dropdowns
$categories = $conn->query("SELECT category_id, name FROM categories");
$suppliers = $conn->query("SELECT supplier_id, company_name FROM suppliers");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Sanitize Inputs
    $name = htmlspecialchars($_POST['name']);
    $generic_name = htmlspecialchars($_POST['generic_name']);
    $sku = htmlspecialchars($_POST['sku']);
    $category_id = intval($_POST['category_id']);
    $supplier_id = intval($_POST['supplier_id']);
    $buy_price = floatval($_POST['buy_price']);
    $sell_price = floatval($_POST['sell_price']);
    $stock_quantity = intval($_POST['stock_quantity']);
    $batch_number = htmlspecialchars($_POST['batch_number']);
    $expiry_date = $_POST['expiry_date'];
    $status = $_POST['status'];

    // 2. Handle Image Upload
    $image_url = ""; // Default empty if no image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        
        // Create folder if it doesn't exist
        if (!is_dir($target_dir)) { 
            mkdir($target_dir, 0777, true); 
        } 

        // Get file extension
        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        // Check if it's a valid image type
        if (in_array($file_extension, $allowed_types)) {
            // Generate a unique name to prevent overwriting
            $new_filename = uniqid() . "_" . basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = $target_file; // Store path in DB
            } else {
                $message = "<div class='alert alert-danger'>Failed to upload image.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Invalid file format. Only JPG, JPEG, PNG, GIF allowed.</div>";
        }
    }

    // 3. Insert using Prepared Statements (Prevents SQL Injection)
    if (empty($message)) { // Only proceed if no upload errors
        $stmt = $conn->prepare("INSERT INTO medicines (name, generic_name, sku, category_id, supplier_id, buy_price, sell_price, stock_quantity, batch_number, expiry_date, image_url, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("sssiiddissss", $name, $generic_name, $sku, $category_id, $supplier_id, $buy_price, $sell_price, $stock_quantity, $batch_number, $expiry_date, $image_url, $status);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Medicine added successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Database Error: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}
?>

<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Add Medicine</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium"><a href="index.php">Dashboard</a></li>
            <li>-</li>
            <li class="fw-medium">Add Medicine</li>
        </ul>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Medicine Information</h5>
        </div>
        <div class="card-body">
            <?php echo $message; ?>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="row gy-3">
                    
                    <div class="col-md-6">
                        <label class="form-label">Medicine Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Napa Extra" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Generic Name</label>
                        <input type="text" name="generic_name" class="form-control" placeholder="e.g. Paracetamol">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" class="form-control" placeholder="MED-001">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php 
                            if ($categories && $categories->num_rows > 0) {
                                while($cat = $categories->fetch_assoc()) { 
                                    echo "<option value='".$cat['category_id']."'>".$cat['name']."</option>"; 
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">Select Supplier</option>
                            <?php 
                            if ($suppliers && $suppliers->num_rows > 0) {
                                while($sup = $suppliers->fetch_assoc()) { 
                                    echo "<option value='".$sup['supplier_id']."'>".$sup['company_name']."</option>"; 
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Buy Price</label>
                        <input type="number" step="0.01" name="buy_price" class="form-control" placeholder="0.00">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Sell Price</label>
                        <input type="number" step="0.01" name="sell_price" class="form-control" placeholder="0.00">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="form-control" placeholder="0">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Batch Number</label>
                        <input type="text" name="batch_number" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Medicine Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary-600">Save Medicine</button>
                        <a href="medicine_list.php" class="btn btn-secondary-600">View List</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include './partials/layouts/layoutBottom.php' ?>