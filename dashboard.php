<?php 
include 'inc/header.php'; 

if (!isset($_SESSION['user_id'])) {
    echo "<script>location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch User Data
$user_sql = "SELECT * FROM users WHERE user_id = '$user_id'";
$user_res = $conn->query($user_sql);

if ($user_res->num_rows > 0) {
    $user_data = $user_res->fetch_assoc();
} else {
    echo "<script>window.location.href='logout.php';</script>";
    exit();
}

// Fetch Orders
$order_sql = "SELECT * FROM orders WHERE client_id = '$user_id' ORDER BY order_id DESC";
$orders = $conn->query($order_sql);
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <?php 
                        $img = !empty($user_data['image_url']) ? $user_data['image_url'] : 'assets/images/user-grid/user-grid-img14.png';
                    ?>
                    <img src="<?php echo $img; ?>" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;" alt="Profile">
                    
                    <h4><?php echo htmlspecialchars($user_data['full_name']); ?></h4>
                    <p class="text-muted"><?php echo htmlspecialchars($user_data['email']); ?></p>
                    <span class="badge bg-info text-dark"><?php echo strtoupper($user_data['role']); ?></span>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Phone:</strong> <?php echo htmlspecialchars($user_data['phone'] ?? 'N/A'); ?></li>
                    <li class="list-group-item"><strong>Address:</strong> <?php echo htmlspecialchars($user_data['address'] ?? 'N/A'); ?></li>
                    <li class="list-group-item"><strong>Joined:</strong> <?php echo date('M d, Y', strtotime($user_data['created_at'])); ?></li>
                    <li class="list-group-item text-center">
                        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">My Orders</h5>
                </div>
                <div class="card-body">
                    <?php if ($orders && $orders->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($order = $orders->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $order['invoice_number']; ?></td>
                                        <td><?php echo date('d M Y', strtotime($order['order_date'])); ?></td>
                                        <td>৳<?php echo number_format($order['grand_total'], 2); ?></td>
                                        <td>
                                            <?php 
                                            $status = $order['payment_status'];
                                            $badgeClass = ($status == 'paid') ? 'bg-success' : 'bg-warning';
                                            echo "<span class='badge $badgeClass'>".ucfirst($status)."</span>";
                                            ?>
                                        </td>
                                        <td>
                                            <a href="admin/generate_invoice_pdf.php?id=<?php echo $order['order_id']; ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               target="_blank">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">You haven't placed any orders yet. <a href="shop.php">Go Shopping</a></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>