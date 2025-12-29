<?php 
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php'; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM categories WHERE category_id = $id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $sql = "UPDATE categories SET name='$name', description='$description' WHERE category_id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Category updated successfully!'); window.location.href='view_categories.php';</script>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<div class="dashboard-main-body">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Category</h5>
        </div>
        <div class="card-body">
            <?php echo $message; ?>
            <form method="POST" action="">
                <div class="row gy-3">
                    <div class="col-12">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($row['description']); ?></textarea>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-warning">Update Category</button>
                        <a href="view_categories.php" class="btn btn-secondary-600">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include './partials/layouts/layoutBottom.php' ?>