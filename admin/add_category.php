<?php
include 'inc/db_connect.php'; 

/* ADD CATEGORY */
if(isset($_POST['add_category'])){
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);

    if($name != ""){
        $conn->query("INSERT INTO categories(name, description) VALUES('$name','$desc')");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Category Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

<!-- ADD CATEGORY CARD -->
<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Add Category</h5>
    </div>

    <div class="card-body">
        <form method="post">
            <div class="mb-3">
                <label>Category Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

           <a href="category_list.php"> <button name="add_category" class="btn btn-success">
                Add Category </a>
            </button>
        </form>
    </div>
</div>

<!-- CATEGORY LIST -->

</div>
</body>
</html>
