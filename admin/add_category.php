<?php 
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php'; 

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $sql = "INSERT INTO categories (name, description) VALUES ('$name', '$description')";

    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert alert-success'>Category created successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Add Category</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium"><a href="index.php">Dashboard</a></li>
            <li>-</li>
            <li class="fw-medium">New Category</li>
        </ul>
    </div>

    <div class="row gy-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Category Details</h5>
                </div>
                <div class="card-body">
                    <?php echo $message; ?>
                    <form method="POST" action="">
                        <div class="row gy-3">
                            
                            <div class="col-12">
                                <label class="form-label">Category Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Antibiotics" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Enter category description..."></textarea>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary-600">Save Category</button>
                                <a href="view_categories.php" class="btn btn-secondary-600">View List</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include './partials/layouts/layoutBottom.php' ?>