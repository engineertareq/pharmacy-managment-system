<?php 
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php'; 
?>

<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Invoice List</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium"><a href="index.php">Dashboard</a></li>
            <li>-</li>
            <li class="fw-medium">Invoices</li>
        </ul>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">All Invoices</h5>
            <a href="create_order.php" class="btn btn-primary-600">+ Create Order</a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th class="text-center">Download PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT o.*, c.full_name as client_name 
                                FROM orders o 
                                LEFT JOIN users c ON o.client_id = c.user_id 
                                ORDER BY o.order_id DESC";
                        
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $status_badge = match($row['payment_status']) {
                                    'Paid' => 'bg-success-subtle text-success-600',
                                    'Pending' => 'bg-warning-subtle text-warning-600',
                                    'Due' => 'bg-danger-subtle text-danger-600',
                                    default => 'bg-dark'
                                };

                                echo "<tr>";
                                echo "<td class='fw-bold'>#" . $row['invoice_number'] . "</td>";
                                echo "<td>" . date('d M Y', strtotime($row['order_date'])) . "</td>";
                                echo "<td>" . $row['client_name'] . "</td>";
                                echo "<td class='fw-bold'>$" . number_format($row['grand_total'], 2) . "</td>";
                                echo "<td><span class='badge $status_badge'>" . $row['payment_status'] . "</span></td>";
                                
                                // REMOVED target='_blank' so it downloads on the current page
                                echo "<td class='text-center'>
                                        <a href='generate_invoice_pdf.php?id=" . $row['order_id'] . "' class='btn btn-sm btn-danger-600 d-flex align-items-center justify-content-center gap-1'>
                                            <iconify-icon icon='solar:file-download-bold-duotone'></iconify-icon> Download
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No invoices found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include './partials/layouts/layoutBottom.php' ?>