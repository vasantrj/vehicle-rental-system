<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {
    header("Location: ../auth/login.php");
    exit();
}

$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $result = mysqli_query($conn, 
        "SELECT * FROM vehicles 
         WHERE status='available' 
         AND (name LIKE '%$search%' OR brand LIKE '%$search%')");
} else {
    $result = mysqli_query($conn, 
        "SELECT * FROM vehicles WHERE status='available'");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Available Vehicles</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2>Available Vehicles</h2>

<form method="get" class="mb-3">
    <input class="form-control w-25 d-inline" name="search" placeholder="Search vehicle">
    <button class="btn btn-primary">Search</button>
</form>

<div class="row">
<?php while($row = mysqli_fetch_assoc($result)) { ?>
    <div class="col-md-4 mb-4">
        <div class="card">
            <img src="../assets/images/<?= $row['image'] ?>" 
                 class="card-img-top" 
                 style="height:200px; object-fit:cover;">
            <div class="card-body">
                <h5><?= $row['name'] ?></h5>
                <p><?= $row['brand'] ?></p>
                <p>₹<?= $row['price_per_day'] ?> / day</p>
                <a class="btn btn-success" 
                   href="book-vehicle.php?id=<?= $row['id'] ?>">Book</a>
            </div>
        </div>
    </div>
<?php } ?>
</div>

</body>
</html>