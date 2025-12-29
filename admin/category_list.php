

<?php

include 'inc/db_connect.php'; 


?>
<div class="card shadow">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Category List</h5>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-secondary">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th width="120">Action</th>
                </tr>
            </thead>
            <tbody>

            <?php
            $i=1;
            $result = $conn->query("SELECT * FROM categories ORDER BY category_id DESC");
            while($row = $result->fetch_assoc()){
            ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>
                        <a href="delete_category.php?id=<?= $row['category_id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this category?')">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>

            </tbody>
        </table>
    </div>
</div>
