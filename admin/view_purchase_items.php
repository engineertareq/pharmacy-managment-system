<?php 
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php'; 
?>

<div class="dashboard-main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Purchased Items List</h5>
            <a href="create_purchase_item.php" class="btn btn-primary-600">Add New Item</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Medicine</th>
                            <th>Batch</th>
                            <th>Expiry</th>
                            <th>Qty</th>
                            <th>Cost</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT pi.*, p.invoice_no, m.name as medicine_name 
                                FROM purchase_items pi
                                JOIN purchases p ON pi.purchase_id = p.purchase_id
                                LEFT JOIN medicines m ON pi.medicine_id = m.medicine_id
                                ORDER BY pi.p_item_id DESC";
                        
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $total_line = $row['quantity'] * $row['cost_price'];
                                echo "<tr>";
                                echo "<td><span class='badge bg-info-subtle text-info-600'>#" . $row['invoice_no'] . "</span></td>";
                                echo "<td class='fw-bold'>" . htmlspecialchars($row['medicine_name']) . "</td>";
                                echo "<td>" . $row['batch_no'] . "</td>";
                                echo "<td>" . $row['expiry_date'] . "</td>";
                                echo "<td>" . $row['quantity'] . "</td>";
                                echo "<td>$" . $row['cost_price'] . "</td>";
                                echo "<td>$" . number_format($total_line, 2) . "</td>";
                                echo "<td>
                                        <div class='d-flex gap-2'>
                                            <a href='edit_purchase_item.php?id=".$row['p_item_id']."' class='btn btn-sm btn-info-600'>Edit</a>
                                            <a href='delete_purchase_item.php?id=".$row['p_item_id']."' class='btn btn-sm btn-danger-600' onclick='return confirm(\"Delete this item?\")'>Delete</a>
                                        </div>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>No items found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include './partials/layouts/layoutBottom.php' ?>