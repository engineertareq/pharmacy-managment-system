<?php 
// view_orders.php
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php'; 
?>

<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Order List</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="index.php" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Orders</li>
        </ul>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">All Orders</h5>
            <a href="create_order.php" class="btn btn-primary-600">
                <iconify-icon icon="solar:add-circle-linear" class="icon text-xl me-1"></iconify-icon> 
                Create New Order
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Invoice No</th>
                            <th>Order Date</th>
                            <th>Client</th>
                            <th>Staff In-Charge</th>
                            <th>Grand Total</th>
                            <th>Payment Status</th>
                            <th>Method</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Corrected Query:
                        // 1. Joins 'users' table AS 'c' (for client) using 'user_id'
                        // 2. Joins 'users' table AS 's' (for staff) using 'user_id'
                        // 3. Selects 'full_name' for both
                        $sql = "SELECT o.*, c.full_name as client_name, s.full_name as staff_name 
                                FROM orders o
                                LEFT JOIN users c ON o.client_id = c.user_id
                                LEFT JOIN users s ON o.staff_id = s.user_id
                                ORDER BY o.order_id DESC";
                        
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                
                                // Color Badge Logic for Status
                                $status_badge = match($row['payment_status']) {
                                    'Paid' => 'bg-success-subtle text-success-600',
                                    'Pending' => 'bg-warning-subtle text-warning-600',
                                    'Due' => 'bg-danger-subtle text-danger-600',
                                    default => 'bg-secondary-subtle text-secondary-600'
                                };

                                echo "<tr>";
                                echo "<td><span class='text-primary-600 fw-bold'>#" . htmlspecialchars($row['invoice_number']) . "</span></td>";
                                echo "<td>" . date('d M Y', strtotime($row['order_date'])) . "</td>";
                                echo "<td>" . htmlspecialchars($row['client_name'] ?? 'Unknown') . "</td>";
                                echo "<td>" . htmlspecialchars($row['staff_name'] ?? 'Unknown') . "</td>";
                                echo "<td class='fw-bold'>$" . number_format($row['grand_total'], 2) . "</td>";
                                echo "<td><span class='badge $status_badge px-2 py-1'>" . $row['payment_status'] . "</span></td>";
                                echo "<td>" . $row['payment_method'] . "</td>";
                                echo "<td class='text-center'>
                                        <div class='d-flex justify-content-center gap-2'>
                                            <a href='edit_order.php?id=".$row['order_id']."' class='btn btn-sm btn-info-600 d-flex align-items-center'>
                                                <iconify-icon icon='solar:pen-new-square-linear' class='me-1'></iconify-icon> Edit
                                            </a>
                                            <a href='delete_order.php?id=".$row['order_id']."' class='btn btn-sm btn-danger-600 d-flex align-items-center' onclick='return confirm(\"Are you sure you want to delete this order?\")'>
                                                <iconify-icon icon='solar:trash-bin-trash-linear' class='me-1'></iconify-icon> Delete
                                            </a>
                                        </div>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center py-4 text-muted'>No orders found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php include './partials/layouts/layoutBottom.php' ?>