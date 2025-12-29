<?php 
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php'; 
?>

<div class="dashboard-main-body">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Order Items List</h5>
            <a href="create_order_item.php" class="btn btn-primary-600">Add New Item</a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Medicine</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // JOIN to get Medicine Name
                        $sql = "SELECT oi.*, m.name as medicine_name 
                                FROM order_items oi
                                LEFT JOIN medicines m ON oi.medicine_id = m.medicine_id
                                ORDER BY oi.item_id DESC";
                        
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><span class='badge bg-primary-subtle text-primary-600'>Order #" . $row['order_id'] . "</span></td>";
                                echo "<td class='fw-bold'>" . htmlspecialchars($row['medicine_name']) . "</td>";
                                echo "<td>" . $row['quantity'] . "</td>";
                                echo "<td>$" . number_format($row['price_per_unit'], 2) . "</td>";
                                echo "<td class='fw-bold'>$" . number_format($row['total_price'], 2) . "</td>";
                                echo "<td>
                                        <div class='d-flex gap-2'>
                                            <a href='edit_order_item.php?id=".$row['item_id']."' class='btn btn-sm btn-info-600'>Edit</a>
                                            <a href='delete_order_item.php?id=".$row['item_id']."' class='btn btn-sm btn-danger-600' onclick='return confirm(\"Delete this item?\")'>Delete</a>
                                        </div>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No items found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include './partials/layouts/layoutBottom.php' ?>