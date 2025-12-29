<?php 
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php'; 
?>

<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Suppliers List</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="index.php" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Suppliers</li>
        </ul>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">All Suppliers</h5>
            <a href="create_supplier.php" class="btn btn-primary-600">
                <iconify-icon icon="solar:add-circle-linear" class="icon text-xl me-1"></iconify-icon> 
                Add New Supplier
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Company Name</th>
                            <th>Contact Person</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                 
                        $sql = "SELECT * FROM suppliers ORDER BY supplier_id DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['supplier_id'] . "</td>";
                                echo "<td class='fw-bold'>" . htmlspecialchars($row['company_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['contact_person']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                                echo "<td class='text-center'>
                                        <div class='d-flex justify-content-center gap-2'>
                                            <a href='edit_supplier.php?id=".$row['supplier_id']."' class='btn btn-sm btn-info-600 d-flex align-items-center'>
                                                <iconify-icon icon='solar:pen-new-square-linear' class='me-1'></iconify-icon> Edit
                                            </a>
                                            <a href='delete_supplier.php?id=".$row['supplier_id']."' class='btn btn-sm btn-danger-600 d-flex align-items-center' onclick='return confirm(\"Are you sure you want to delete this supplier?\")'>
                                                <iconify-icon icon='solar:trash-bin-trash-linear' class='me-1'></iconify-icon> Delete
                                            </a>
                                        </div>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center text-muted py-4'>No suppliers found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php include './partials/layouts/layoutBottom.php' ?>