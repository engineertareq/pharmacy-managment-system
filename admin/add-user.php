<?php
include 'inc/db_connect.php'; 

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $role = mysqli_real_escape_string($conn, $_POST['role']); 
    $address = mysqli_real_escape_string($conn, $_POST['address']); 
    $password = mysqli_real_escape_string($conn, $_POST['password']);

   
    $password_hash = password_hash($password, PASSWORD_DEFAULT);


    $image_path = "";
    if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] == 0) {
        $target_dir = "assets/images/users/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $target_file = $target_dir . basename($_FILES["user_image"]["name"]);
        move_uploaded_file($_FILES["user_image"]["tmp_name"], $target_file);
        $image_path = $target_file; 

    }


    $sql = "INSERT INTO `users`(`full_name`, `email`, `password_hash`, `phone`, `address`, `role`) 
            VALUES ('$full_name', '$email', '$password_hash', '$phone', '$address', '$role')";

    if ($conn->query($sql) === TRUE) {
        $message = '<div class="alert alert-success">User created successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
    }
}
?>

<?php include './partials/layouts/layoutTop.php' ?>

<?php $script = '<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $("#imagePreview").css("background-image", "url(" + e.target.result + ")");
                $("#imagePreview").hide();
                $("#imagePreview").fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imageUpload").change(function() {
        readURL(this);
    });
</script>';?>

<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Add User</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="index.php" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Add User</li>
        </ul>
    </div>

    <div class="card h-100 p-0 radius-12">
        <div class="card-body p-24">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-xl-8 col-lg-10">
                    
                    <?php echo $message; ?>

                    <div class="card border">
                        <div class="card-body">
                            
                            <form action="" method="POST" enctype="multipart/form-data">
                                
                                <h6 class="text-md text-primary-light mb-16">Profile Image</h6>

                                <div class="mb-24 mt-16">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                            <input type='file' name="user_image" id="imageUpload" accept=".png, .jpg, .jpeg" hidden>
                                            <label for="imageUpload" class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                                <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                            </label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imagePreview" style="background-image: url('assets/images/user-dummy.png');"> </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-20">
                                    <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">Full Name <span class="text-danger-600">*</span></label>
                                    <input type="text" name="full_name" class="form-control radius-8" id="name" placeholder="Enter Full Name" required>
                                </div>

                                <div class="mb-20">
                                    <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span class="text-danger-600">*</span></label>
                                    <input type="email" name="email" class="form-control radius-8" id="email" placeholder="Enter email address" required>
                                </div>

                                <div class="mb-20">
                                    <label for="password" class="form-label fw-semibold text-primary-light text-sm mb-8">Password <span class="text-danger-600">*</span></label>
                                    <input type="password" name="password" class="form-control radius-8" id="password" placeholder="Enter Password" required>
                                </div>

                                <div class="mb-20">
                                    <label for="number" class="form-label fw-semibold text-primary-light text-sm mb-8">Phone</label>
                                    <input type="text" name="phone" class="form-control radius-8" id="number" placeholder="Enter phone number">
                                </div>

                                <div class="mb-20">
                                    <label for="role" class="form-label fw-semibold text-primary-light text-sm mb-8">Role (Department) <span class="text-danger-600">*</span> </label>
                                    <select class="form-control radius-8 form-select" name="role" id="role" required>
                                        <option value="client">Client</option>
                                        <option value="staff">Staff</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>

                                <div class="mb-20">
                                    <label for="address" class="form-label fw-semibold text-primary-light text-sm mb-8">Address (Description)</label>
                                    <textarea name="address" class="form-control radius-8" id="address" placeholder="Write address..."></textarea>
                                </div>

                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <button type="button" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                        Save User
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include './partials/layouts/layoutBottom.php' ?>