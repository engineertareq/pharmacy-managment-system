<?php 
include 'inc/db_connect.php'; 

if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    
    $del_sql = "DELETE FROM users WHERE user_id = $id";
    if ($conn->query($del_sql)) {
        echo "<script>alert('User deleted successfully'); window.location='users-list.php';</script>";
    } else {
        echo "<script>alert('Error deleting user: Dependent data exists.');</script>";
    }
}
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$search = "";
$whereSQL = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $whereSQL = "WHERE full_name LIKE '%$search%' OR email LIKE '%$search%' OR role LIKE '%$search%'";
}
$count_sql = "SELECT COUNT(*) FROM users $whereSQL";
$count_result = $conn->query($count_sql);
$total_records = $count_result->fetch_row()[0];
$total_pages = ceil($total_records / $limit);
$sql = "SELECT * FROM users $whereSQL ORDER BY user_id DESC LIMIT $start, $limit";
$result = $conn->query($sql);
?>

<?php include './partials/layouts/layoutTop.php' ?>

<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Users Grid</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="index.php" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Users Grid</li>
        </ul>
    </div>

    <div class="card h-100 p-0 radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <span class="text-md fw-medium text-secondary-light mb-0">Show</span>
                <select class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                    <option>10</option>
                </select>
                
                <form class="navbar-search" method="GET" action="">
                    <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Search name, email..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" style="border:none; background:transparent;"><iconify-icon icon="ion:search-outline" class="icon"></iconify-icon></button>
                </form>
            </div>
            <a href="add-user.php" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                Add New User
            </a>
        </div>
        
        <div class="card-body p-24">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Join Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Phone</th> <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) { 
                              
                                $img = !empty($row['image_url']) ? $row['image_url'] : 'assets/images/user-grid/user-grid-img14.png';
                                
                            
                                $role_badge = "bg-primary-focus text-primary-600";
                                if($row['role'] == 'admin') $role_badge = "bg-danger-focus text-danger-600";
                                if($row['role'] == 'staff') $role_badge = "bg-warning-focus text-warning-600";
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-10">
                                    <?php echo $row['user_id']; ?>
                                </div>
                            </td>
                            <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo $img; ?>" alt="" class="w-40-px h-40-px rounded-circle flex-shrink-0 me-12 overflow-hidden object-fit-cover">
                                    <div class="flex-grow-1">
                                        <span class="text-md mb-0 fw-normal text-secondary-light"><?php echo htmlspecialchars($row['full_name']); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="text-md mb-0 fw-normal text-secondary-light"><?php echo htmlspecialchars($row['email']); ?></span></td>
                            <td><?php echo ucfirst($row['role']); ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td class="text-center">
                                <span class="<?php echo $role_badge; ?> border px-24 py-4 radius-4 fw-medium text-sm">Active</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center gap-10 justify-content-center">
                                    <a href="view-profile.php?id=<?php echo $row['user_id']; ?>" class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                    </a>
                                    <a href="add-user.php?edit_id=<?php echo $row['user_id']; ?>" class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                    </a>
                                    <a href="users-list.php?delete_id=<?php echo $row['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this user? This cannot be undone.');" class="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            } 
                        } else {
                            echo "<tr><td colspan='8' class='text-center py-4'>No users found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                <span>Showing <?php echo $result->num_rows; ?> entries</span>
                <ul class="pagination d-flex flex-wrap align-items-center gap-2 justify-content-center">
                    <li class="page-item">
                        <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md" 
                           href="?page=<?php echo max(1, $page-1); ?>&search=<?php echo $search; ?>">
                            <iconify-icon icon="ep:d-arrow-left"></iconify-icon>
                        </a>
                    </li>

                    <?php for($i=1; $i <= $total_pages; $i++): ?>
                    <li class="page-item">
                        <a class="page-link <?php if($page == $i) echo 'bg-primary-600 text-white'; else echo 'bg-neutral-200 text-secondary-light'; ?> fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md" 
                           href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>">
                           <?php echo $i; ?>
                        </a>
                    </li>
                    <?php endfor; ?>

                    <li class="page-item">
                        <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md" 
                           href="?page=<?php echo min($total_pages, $page+1); ?>&search=<?php echo $search; ?>">
                            <iconify-icon icon="ep:d-arrow-right"></iconify-icon>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include './partials/layouts/layoutBottom.php' ?>