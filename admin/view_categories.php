<?php 
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php'; 
?>

<div class="dashboard-main-body">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Categories List</h5>
            <a href="create_category.php" class="btn btn-primary-600">
                <iconify-icon icon="solar:add-circle-linear" class="icon text-xl me-1"></iconify-icon> 
                Add Category
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM categories ORDER BY category_id DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['category_id'] . "</td>";
                                echo "<td class='fw-bold'>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                echo "<td>
                                        <div class='d-flex gap-2'>
                                            <a href='edit_category.php?id=".$row['category_id']."' class='btn btn-sm btn-info-600'>Edit</a>
                                            <a href='delete_category.php?id=".$row['category_id']."' class='btn btn-sm btn-danger-600' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                        </div>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>No categories found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include './partials/layouts/layoutBottom.php' ?>