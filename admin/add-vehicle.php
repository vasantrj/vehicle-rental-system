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

    $query = "INSERT INTO vehicles (name, brand, price_per_day) VALUES ('$name', '$brand', '$price')";
    if (mysqli_query($conn, $query)) {
        $msg = "Vehicle added successfully!";
    } else {
        $msg = "Error adding vehicle!";
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
  <?php if($msg) echo "<p>$msg</p>"; ?>

  <form method="post" class="w-50">
    <input class="form-control mb-2" name="name" placeholder="Vehicle Name" required>
    <input class="form-control mb-2" name="brand" placeholder="Brand" required>
    <input class="form-control mb-2" name="price" type="number" placeholder="Price per day" required>
    <button class="btn btn-primary" name="add">Add Vehicle</button>
  </form>

  <br>
  <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>