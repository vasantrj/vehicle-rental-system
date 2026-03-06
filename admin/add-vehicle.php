<?php

session_start();
include "../config/db.php";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$name=$_POST['name'];
$brand=$_POST['brand'];
$price=$_POST['price'];

$image=$_FILES['image']['name'];

move_uploaded_file($_FILES['image']['tmp_name'],"../assets/images/".$image);

$stmt=mysqli_prepare($conn,"INSERT INTO vehicles(name,brand,price_per_day,image) VALUES(?,?,?,?)");

mysqli_stmt_bind_param($stmt,"ssis",$name,$brand,$price,$image);

mysqli_stmt_execute($stmt);

}

?>

<?php include "../includes/navbar_admin.php"; ?>

<!DOCTYPE html>
<html>
<head>

<title>Add Vehicle</title>

<link rel="stylesheet" href="../assets/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container">

<div class="card p-4 mt-5" style="max-width:700px;margin:auto;">

<h3>Add Vehicle</h3>

<form method="post" enctype="multipart/form-data">

<input class="form-control mb-3" name="name" placeholder="Vehicle Name">

<input class="form-control mb-3" name="brand" placeholder="Brand">

<input class="form-control mb-3" name="price" placeholder="Price per day">

<input class="form-control mb-3" type="file" name="image">

<button class="btn btn-primary w-100">

Add Vehicle

</button>

</form>

</div>

</div>

</body>
</html>