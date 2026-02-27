<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

$msg = "";

if (isset($_POST['add'])) {

    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];

    $image_name = $_FILES['image']['name'];
    $temp_name = $_FILES['image']['tmp_name'];

    $target = "../assets/images/" . $image_name;

    if (move_uploaded_file($temp_name, $target)) {

        mysqli_query($conn, "INSERT INTO vehicles 
            (name, brand, price_per_day, image, status) 
            VALUES ('$name', '$brand', '$price', '$image_name', 'available')");

        $msg = "Vehicle added successfully!";
    } else {
        $msg = "Image upload failed!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Vehicle</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2>Add Vehicle</h2>
<?php if($msg) echo "<p class='text-success'>$msg</p>"; ?>

<form method="post" enctype="multipart/form-data" class="w-50">
    <input class="form-control mb-2" name="name" placeholder="Vehicle Name" required>
    <input class="form-control mb-2" name="brand" placeholder="Brand" required>
    <input class="form-control mb-2" name="price" type="number" placeholder="Price per day" required>
    <input class="form-control mb-2" type="file" name="image" required>
    <button class="btn btn-primary" name="add">Add Vehicle</button>
</form>

</body>
</html>