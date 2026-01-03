<?php include 'inc/header.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        if ($qty == 0) { unset($_SESSION['cart'][$id]); }
        else { $_SESSION['cart'][$id] = $qty; }
    }
}

if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    echo "<script>window.location.href='cart.php';</script>";
}
?>

<h2 class="mb-4">Shopping Cart</h2>

<?php if (empty($_SESSION['cart'])): ?>
    <div class="alert alert-warning text-center py-5">
        <h4>Your Cart is Empty</h4>
        <a href="shop.php" class="btn btn-primary mt-3">Go to Shop</a>
    </div>
<?php else: ?>
    <form method="post">
        <div class="row">
            <div class="col-md-8">
                <table class="table table-bordered bg-white">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th width="15%">Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        $ids = implode(',', array_keys($_SESSION['cart']));
                        $sql = "SELECT * FROM medicines WHERE medicine_id IN ($ids)";
                        $result = $conn->query($sql);
                        
                        while($row = $result->fetch_assoc()):
                            $id = $row['medicine_id'];
                            $qty = $_SESSION['cart'][$id];
                            $subtotal = $row['sell_price'] * $qty;
                            $total += $subtotal;
                            $img = !empty($row['image_url']) ? "admin/" . $row['image_url'] : "https://via.placeholder.com/50";
                        ?>
                        <tr>
                            <td class="d-flex align-items-center">
                                <img src="<?php echo $img; ?>" width="50" class="me-3">
                                <?php echo $row['name']; ?>
                            </td>
                            <td>৳<?php echo number_format($row['sell_price'], 2); ?></td>
                            <td>
                                <input type="number" name="qty[<?php echo $id; ?>]" value="<?php echo $qty; ?>" class="form-control" min="0">
                            </td>
                            <td>৳<?php echo number_format($subtotal, 2); ?></td>
                            <td>
                                <a href="cart.php?remove=<?php echo $id; ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <button type="submit" name="update_cart" class="btn btn-warning"><i class="fas fa-sync"></i> Update Cart</button>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">Order Summary</div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal:</span>
                            <strong>৳<?php echo number_format($total, 2); ?></strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4 fs-5">
                            <span>Total:</span>
                            <strong class="text-success">৳<?php echo number_format($total, 2); ?></strong>
                        </div>
                        <a href="checkout.php" class="btn btn-success w-100 py-2">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php endif; ?>

<?php include 'inc/footer.php'; ?>