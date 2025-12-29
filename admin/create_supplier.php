<?php 
include './partials/layouts/layoutTop.php'; 
include 'inc/db_connect.php'; 


$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = $_POST['company_name'];
    $contact_person = $_POST['contact_person'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "INSERT INTO suppliers (company_name, contact_person, phone, email, address) 
            VALUES ('$company_name', '$contact_person', '$phone', '$email', '$address')";

    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert alert-success'>New supplier created successfully</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}
?>

<div class="dashboard-main-body">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Add Supplier</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="index.php" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Add Supplier</li>
        </ul>
    </div>

    <div class="row gy-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Supplier Details</h5>
                </div>
                <div class="card-body">
                    <?php echo $message; ?>
                    
                    <form method="POST" action="">
                        <div class="row gy-3">
                            
                            <div class="col-12">
                                <label class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control" placeholder="Enter Company Name" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Contact Person</label>
                                <input type="text" name="contact_person" class="form-control" placeholder="Enter Contact Person" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" placeholder="+1 (555) 000-0000" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" placeholder="Enter Address">
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary-600">Save Supplier</button>
                                <a href="view_suppliers.php" class="btn btn-secondary-600">View List</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include './partials/layouts/layoutBottom.php' ?>