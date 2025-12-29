<?php 
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php'; 

if (!isset($_GET['id'])) {
    echo "Invalid ID"; exit;
}
$id = $_GET['id'];
$item = $conn->query("SELECT * FROM order_items WHERE item_id = $id")->fetch_assoc();

// Fetch Dropdowns
$orders_res = $conn->query("SELECT order_id FROM orders");
$medicines_res = $conn->query("SELECT medicine_id, name, sell_price FROM medicines");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $medicine_id = $_POST['medicine_id'];
    $quantity = $_POST['quantity'];
    $price_per_unit = $_POST['price_per_unit'];
    $total_price = $quantity * $price_per_unit; // Recalculate total

    $sql = "UPDATE order_items SET 
            order_id='$order_id', 
            medicine_id='$medicine_id', 
            quantity='$quantity', 
            price_per_unit='$price_per_unit', 
            total_price='$total_price' 
            WHERE item_id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Item updated successfully!'); window.location.href='view_order_items.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<div class="dashboard-main-body">
    <div class="card">
        <div class="card-header"><h5 class="card-title mb-0">Edit Order Item</h5></div>
        <div class="card-body">
            <form method="POST">
                <div class="row gy-3">
                    <div class="col-md-6">
                        <label class="form-label">Order ID</label>
                        <select name="order_id" class="form-select" required>
                            <?php 
                            if($orders_res) {
                                while($row = $orders_res->fetch_assoc()) {
                                    $sel = ($row['order_id'] == $item['order_id']) ? 'selected' : '';
                                    echo "<option value='".$row['order_id']."' $sel>Order #".$row['order_id']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Medicine</label>
                        <select name="medicine_id" class="form-select" required>
                            <?php 
                            if($medicines_res) {
                                while($row = $medicines_res->fetch_assoc()) {
                                    $sel = ($row['medicine_id'] == $item['medicine_id']) ? 'selected' : '';
                                    echo "<option value='".$row['medicine_id']."' $sel>".$row['name']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="<?php echo $item['quantity']; ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Unit Price</label>
                        <input type="number" step="0.01" name="price_per_unit" class="form-control" value="<?php echo $item['price_per_unit']; ?>" required>
                    </div>

                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-warning">Update Item</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include './partials/layouts/layoutBottom.php' ?>