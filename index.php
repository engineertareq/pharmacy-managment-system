<?php include 'inc/header.php'; 


if (isset($_POST['add_to_cart'])) {
    $med_id = $_POST['medicine_id'];
    if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
    
    if (isset($_SESSION['cart'][$med_id])) {
        $_SESSION['cart'][$med_id]++;
    } else {
        $_SESSION['cart'][$med_id] = 1;
    }
    echo "<div class='alert alert-success'>Item added to cart!</div>";
    echo "<meta http-equiv='refresh' content='1'>"; 
}
?>

<div class="p-5 mb-4 bg-light rounded-3 text-center" style="background: #c5d5e4ff;">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold text-success">Welcome to PharmaCare </h1>
        <!-- <p class="col-md-8 fs-4 mx-auto">Your trusted online pharmacy for genuine medicines and healthcare products.</p>
        <a class="btn btn-primary btn-lg" href="shop.php">Browse Shop</a> -->
    </div>
</div>

<h3 class="mb-4">Featured Medicines</h3>
<div class="row">
    <?php
    $sql = "SELECT * FROM medicines WHERE status='active' ORDER BY medicine_id DESC LIMIT 4";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Fix Image Path
            $img = !empty($row['image_url']) ? "admin/" . $row['image_url'] : "https://via.placeholder.com/200?text=No+Image";
            ?>
            <div class="col-md-3 mb-4">
                <div class="card card-product h-100">
                    <img src="<?php echo $img; ?>" class="card-img-top product-img" alt="<?php echo $row['name']; ?>">
                    <div class="card-body text-center d-flex flex-column">
                        <h5 class="card-title"><?php echo $row['name']; ?></h5>
                        <p class="card-text text-muted mb-1"><?php echo $row['generic_name']; ?></p>
                        <h4 class="text-success fw-bold mb-3">৳<?php echo number_format($row['sell_price'], 2); ?></h4>
                        
                        <form method="post" class="mt-auto">
                            <input type="hidden" name="medicine_id" value="<?php echo $row['medicine_id']; ?>">
                            <button type="submit" name="add_to_cart" class="btn btn-outline-success w-100">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p class='alert alert-warning'>No medicines found.</p>";
    }
    ?>
</div>

<?php include 'inc/footer.php'; ?>