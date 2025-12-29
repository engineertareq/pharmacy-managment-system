<?php 
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php'; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM suppliers WHERE supplier_id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>Supplier not found!</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>Invalid Request!</div>";
    exit;
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = $_POST['company_name'];
    $contact_person = $_POST['contact_person'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $updateSql = "UPDATE suppliers SET 
                  company_name='$company_name', 
                  contact_person='$contact_person', 
                  email='$email', 
                  phone='$phone', 
                  address='$address' 
                  WHERE supplier_id=$id";

    if ($conn->query($updateSql) === TRUE) {
        echo "<script>
                alert('Supplier updated successfully!');
                window.location.href='view_suppliers.php';
              </script>";
    } else {
        $message = "<div class='alert alert-danger'>Error updating record: " . $conn->error . "</div>";
    }
}
?>

<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Edit Supplier</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="index.php" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Edit Supplier</li>
        </ul>
    </div>

    <div class="row gy-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Update Supplier Details</h5>
                </div>
                <div class="card-body">
                    <?php echo $message; ?>
                    
                    <form method="POST" action="">
                        <div class="row gy-3">
                            
                            <div class="col-12">
                                <label class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($row['company_name']); ?>" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Contact Person</label>
                                <input type="text" name="contact_person" class="form-control" 
                                       value="<?php echo htmlspecialchars($row['contact_person']); ?>" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" 
                                       value="<?php echo htmlspecialchars($row['email']); ?>" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" 
                                       value="<?php echo htmlspecialchars($row['phone']); ?>" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" 
                                       value="<?php echo htmlspecialchars($row['address']); ?>">
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-warning">Update Supplier</button>
                                <a href="view_suppliers.php" class="btn btn-secondary-600">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include './partials/layouts/layoutBottom.php' ?>