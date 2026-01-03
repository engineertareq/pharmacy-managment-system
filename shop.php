<?php include 'inc/header.php'; 

if (isset($_POST['add_to_cart'])) {
    $med_id = $_POST['medicine_id'];
    if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
    if (isset($_SESSION['cart'][$med_id])) { $_SESSION['cart'][$med_id]++; } else { $_SESSION['cart'][$med_id] = 1; }
    echo "<script>window.location.href='shop.php';</script>"; 
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>All Medicines</h2>
    </div>

<div class="row">
    <?php
    $sql = "SELECT * FROM medicines WHERE status='active'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $img = !empty($row['image_url']) ? "admin/" . $row['image_url'] : "https://via.placeholder.com/200?text=No+Image";
            ?>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card card-product h-100">
                    <img src="<?php echo $img; ?>" class="card-img-top product-img" alt="<?php echo $row['name']; ?>">
                    <div class="card-body text-center d-flex flex-column">
                        <h5 class="card-title"><?php echo $row['name']; ?></h5>
                        <p class="small text-muted"><?php echo $row['sku']; ?></p>
                        <h4 class="text-success fw-bold">৳<?php echo number_format($row['sell_price'], 2); ?></h4>
                        
                        <form method="post" class="mt-auto">
                            <input type="hidden" name="medicine_id" value="<?php echo $row['medicine_id']; ?>">
                            <button type="submit" name="add_to_cart" class="btn btn-primary w-100">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<div class='col-12'><div class='alert alert-info'>No products available.</div></div>";
    }
    ?>
</div>

<?php include 'inc/footer.php'; ?>