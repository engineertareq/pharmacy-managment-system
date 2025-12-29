<?php 
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php'; 
?>

<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Medicine List</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="index.php" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Medicines</li>
        </ul>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">All Medicines</h5>
            <a href="add_medicine.php" class="btn btn-primary-600">
                <iconify-icon icon="solar:add-circle-linear" class="icon text-xl me-1"></iconify-icon> 
                Add Medicine
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Medicine Name</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Price (Sell)</th>
                            <th>Stock</th>
                            <th>Expiry</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT m.*, c.name AS category_name, s.company_name 
                                FROM medicines m 
                                LEFT JOIN categories c ON m.category_id = c.category_id 
                                LEFT JOIN suppliers s ON m.supplier_id = s.supplier_id 
                                ORDER BY m.medicine_id DESC";
                        
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $status_badge = ($row['status'] == 1) 
                                    ? '<span class="badge bg-success-subtle text-success-600">Active</span>' 
                                    : '<span class="badge bg-danger-subtle text-danger-600">Inactive</span>';
                                $img_src = "assets/images/user-grid/user-grid-img14.png"; // 
                                if (!empty($row['image_url']) && file_exists($row['image_url'])) {
                                    $img_src = $row['image_url'];
                                } elseif (!empty($row['image_url'])) {
                      
                                    $img_src = $row['image_url']; 
                                }

                                echo "<tr>";
                                echo "<td>
                                        <div class='d-flex align-items-center gap-2'>
                                            <img src='$img_src' alt='img' class='w-40-px h-40-px rounded-circle object-fit-cover'>
                                        </div>
                                      </td>";
                                
                                echo "<td>
                                        <h6 class='text-md mb-0 fw-medium'>" . htmlspecialchars($row['name']) . "</h6>
                                        <span class='text-sm text-secondary-light fw-normal'>" . htmlspecialchars($row['generic_name']) . "</span>
                                      </td>";
                                
                                echo "<td>" . htmlspecialchars($row['category_name'] ?? 'N/A') . "</td>";
                                echo "<td>" . htmlspecialchars($row['company_name'] ?? 'N/A') . "</td>";
                                echo "<td class='fw-bold'>৳" . number_format($row['sell_price'], 2) . "</td>";
            
                                $stock_class = ($row['stock_quantity'] < 10) ? 'text-danger fw-bold' : '';
                                echo "<td class='$stock_class'>" . $row['stock_quantity'] . "</td>";
                                
                                echo "<td>" . $row['expiry_date'] . "</td>";
                                echo "<td>" . $status_badge . "</td>";
                                
                                echo "<td>
                                        <div class='d-flex align-items-center gap-2'>
                                            <a href='edit_medicine.php?id=".$row['medicine_id']."' class='btn btn-sm btn-info-600 d-flex align-items-center justify-content-center'>
                                                <iconify-icon icon='solar:pen-new-square-linear' class='text-lg'></iconify-icon>
                                            </a>
                                            <a href='delete_medicine.php?id=".$row['medicine_id']."' class='btn btn-sm btn-danger-600 d-flex align-items-center justify-content-center' onclick='return confirm(\"Are you sure you want to delete this medicine?\")'>
                                                <iconify-icon icon='solar:trash-bin-trash-linear' class='text-lg'></iconify-icon>
                                            </a>
                                        </div>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center py-4'>No medicines found in the inventory.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php include './partials/layouts/layoutBottom.php' ?>