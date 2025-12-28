<?php include_once("inc/db_connect.php");  ?>

<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table,th,td{
            border: 1px solid black;
            border-collapse: collapse;
            
        }
    </style>
</head>
<?php 
$sql="SELECT * FROM categories order by category_id desc";
$result=$db->query($sql);  
//$row=$result->fetch_array(); 



?>
<body>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
        
        </tr>
        <?php while($row1=$result->fetch_object()): ?>
        <tr>
            <td><?php  echo $row1->category_id; ?></td>
            <td><?php  echo $row1->name; ?></td>
            <td><?php   echo $row1->description	; ?></td>
      

        </tr>
        <?php endwhile; ?>
    </table> <br>

    <a href="add_category.php"> Add category </a>
</body>
</html>