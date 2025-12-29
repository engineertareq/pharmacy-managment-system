<?php 
// view_purchases.php
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php';
?>

<div class="dashboard-main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Purchase History</h5>
            <a href="create_purchase.php" class="btn btn-primary-600">
                <iconify-icon icon="solar:add-circle-linear" class="icon text-xl me-1"></iconify-icon> New Purchase
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Invoice No</th>
                            <th>Supplier</th>
                            <th>Total Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // JOIN Query to get Supplier Name
                        $sql = "SELECT purchases.*, suppliers.company_name 
                                FROM purchases 
                                LEFT JOIN suppliers ON purchases.supplier_id = suppliers.supplier_id 
                                ORDER BY purchase_date DESC";
                        
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['purchase_id'] . "</td>";
                                echo "<td>" . date('d M Y', strtotime($row['purchase_date'])) . "</td>";
                                echo "<td><span class='badge bg-info-subtle text-info-600'>" . $row['invoice_no'] . "</span></td>";
                                echo "<td class='fw-bold'>" . $row['company_name'] . "</td>";
                                echo "<td>$" . number_format($row['total_amount'], 2) . "</td>";
                                echo "<td>
                                        <a href='edit_purchase.php?id=".$row['purchase_id']."' class='btn btn-sm btn-info-600'>Edit</a>
                                        <a href='delete_purchase.php?id=".$row['purchase_id']."' class='btn btn-sm btn-danger-600' onclick='return confirm(\"Delete this purchase?\")'>Delete</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No purchases found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include './partials/layouts/layoutBottom.php' ?>