<?php 

session_start();
include 'inc/db_connect.php'; 


if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; 
}

$user_id = $_SESSION['user_id'];
$message = "";


if (isset($_POST['update_profile'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $phone     = mysqli_real_escape_string($conn, $_POST['phone']);
    $role      = mysqli_real_escape_string($conn, $_POST['role']); 
    $address   = mysqli_real_escape_string($conn, $_POST['address']); 
    

    $update_image_query = "";
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "assets/images/users/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $update_image_query = ", image_url = '$target_file'";
        }
    }

    $sql = "UPDATE users SET 
            full_name = '$full_name', 
            email = '$email', 
            phone = '$phone', 
            role = '$role', 
            address = '$address' 
            $update_image_query
            WHERE user_id = $user_id";

    if ($conn->query($sql)) {
        $message = "<div class='alert alert-success'>Profile updated successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error updating profile.</div>";
    }
}


if (isset($_POST['change_password'])) {
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass === $confirm_pass) {
        $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password_hash = '$hashed_password' WHERE user_id = $user_id";
        if ($conn->query($sql)) {
            $message = "<div class='alert alert-success'>Password changed successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error changing password.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Passwords do not match!</div>";
    }
}


$result = $conn->query("SELECT * FROM users WHERE user_id = $user_id");
$user = $result->fetch_assoc();


$user_image = !empty($user['image_url']) ? $user['image_url'] : 'assets/images/user-grid/user-grid-img14.png';
?>

<?php include './partials/layouts/layoutTop.php' ?>

<?php $script ='<script>
    // ======================== Upload Image Start =====================
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

    // ================== Password Show Hide Js Start ==========
    function initializePasswordToggle(toggleSelector) {
        $(toggleSelector).on("click", function() {
            $(this).toggleClass("ri-eye-off-line");
            var input = $($(this).attr("data-toggle"));
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    }
    initializePasswordToggle(".toggle-password");
    </script>';?>

<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">View Profile</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium"><a href="index.php">Dashboard</a></li>
            <li>-</li>
            <li class="fw-medium">View Profile</li>
        </ul>
    </div>

    <?php echo $message; ?>

    <div class="row gy-4">
        <div class="col-lg-4">
            <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
                <img src="assets/images/user-grid/user-grid-bg1.png" alt="" class="w-100 object-fit-cover">
                <div class="pb-24 ms-16 mb-24 me-16 mt--100">
                    <div class="text-center border border-top-0 border-start-0 border-end-0">
                        <img src="<?php echo $user_image; ?>" alt="" class="border br-white border-width-2-px w-200-px h-200-px rounded-circle object-fit-cover">
                        <h6 class="mb-0 mt-16"><?php echo htmlspecialchars($user['full_name']); ?></h6>
                        <span class="text-secondary-light mb-16"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="mt-24">
                        <h6 class="text-xl mb-16">Personal Info</h6>
                        <ul>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Full Name</span>
                                <span class="w-70 text-secondary-light fw-medium">: <?php echo htmlspecialchars($user['full_name']); ?></span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Email</span>
                                <span class="w-70 text-secondary-light fw-medium">: <?php echo htmlspecialchars($user['email']); ?></span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Phone</span>
                                <span class="w-70 text-secondary-light fw-medium">: <?php echo htmlspecialchars($user['phone']); ?></span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Role</span>
                                <span class="w-70 text-secondary-light fw-medium">: <span class="badge bg-primary-600"><?php echo ucfirst($user['role']); ?></span></span>
                            </li>
                            <li class="d-flex align-items-center gap-1">
                                <span class="w-30 text-md fw-semibold text-primary-light">Address</span>
                                <span class="w-70 text-secondary-light fw-medium">: <?php echo htmlspecialchars($user['address']); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body p-24">
                    <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24 active" id="pills-edit-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-edit-profile" type="button" role="tab">Edit Profile</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-change-passwork-tab" data-bs-toggle="pill" data-bs-target="#pills-change-passwork" type="button" role="tab">Change Password</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-notification-tab" data-bs-toggle="pill" data-bs-target="#pills-notification" type="button" role="tab">Settings</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        
                        <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel" tabindex="0">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <h6 class="text-md text-primary-light mb-16">Profile Image</h6>
                                <div class="mb-24 mt-16">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                            <input type='file' name="image" id="imageUpload" accept=".png, .jpg, .jpeg" hidden>
                                            <label for="imageUpload" class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                                <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                            </label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imagePreview" style="background-image: url('<?php echo $user_image; ?>');"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Full Name</label>
                                            <input type="text" name="full_name" class="form-control radius-8" value="<?php echo $user['full_name']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Email</label>
                                            <input type="email" name="email" class="form-control radius-8" value="<?php echo $user['email']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Phone</label>
                                            <input type="text" name="phone" class="form-control radius-8" value="<?php echo $user['phone']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Department (Role)</label>
                                            <select class="form-control radius-8 form-select" name="role">
                                                <option value="admin" <?php if($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                                                <option value="staff" <?php if($user['role'] == 'staff') echo 'selected'; ?>>Staff</option>
                                                <option value="client" <?php if($user['role'] == 'client') echo 'selected'; ?>>Client</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Description (Address)</label>
                                            <textarea name="address" class="form-control radius-8" placeholder="Enter Address..."><?php echo $user['address']; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <button type="submit" name="update_profile" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="pills-change-passwork" role="tabpanel" tabindex="0">
                            <form action="" method="POST">
                                <div class="mb-20">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">New Password</label>
                                    <div class="position-relative">
                                        <input type="password" name="new_password" class="form-control radius-8" id="your-password" required>
                                        <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#your-password"></span>
                                    </div>
                                </div>
                                <div class="mb-20">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Confirm Password</label>
                                    <div class="position-relative">
                                        <input type="password" name="confirm_password" class="form-control radius-8" id="confirm-password" required>
                                        <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#confirm-password"></span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <button type="submit" name="change_password" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="pills-notification" role="tabpanel" tabindex="0">
                            <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
                                <label for="companzNew" class="position-absolute w-100 h-100 start-0 top-0"></label>
                                <div class="d-flex align-items-center gap-3 justify-content-between">
                                    <span class="form-check-label line-height-1 fw-medium text-secondary-light">Company News</span>
                                    <input class="form-check-input" type="checkbox" role="switch" id="companzNew">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include './partials/layouts/layoutBottom.php' ?>