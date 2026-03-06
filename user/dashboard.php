<?php

session_start();
include "../config/db.php";

if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
exit();
}

$user_id = $_SESSION['user_id'];

/* USER NAME */
$user_query = mysqli_query($conn,"SELECT name FROM users WHERE id=$user_id");
$user = mysqli_fetch_assoc($user_query);

/* TOTAL BOOKINGS */
$bookings = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as total FROM bookings WHERE user_id=$user_id"
))['total'];

/* TOTAL SPENT */
$total_spent = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT SUM(total_price) as total FROM bookings WHERE user_id=$user_id"
))['total'];

/* TOTAL VEHICLES */
$vehicles = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as total FROM vehicles"
))['total'];

?>

<?php include "../includes/navbar_user.php"; ?>

<!DOCTYPE html>
<html>
<head>

<title>User Dashboard</title>

<link rel="stylesheet" href="../assets/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

.dashboard-header{
padding:40px 0;
}

.stat-card{
border:none;
border-radius:12px;
padding:25px;
color:white;
}

.bg-orange{
background:#f97316;
}

.bg-darkblue{
background:#1f2937;
}

.bg-green{
background:#16a34a;
}

.vehicle-card{
border:none;
border-radius:12px;
box-shadow:0 6px 20px rgba(0,0,0,0.1);
}

.vehicle-card img{
height:180px;
object-fit:cover;
}

</style>

</head>

<body>

<div class="container">

<!-- WELCOME -->

<div class="dashboard-header">

<h2>Welcome <?= $user['name'] ?> 👋</h2>

<p class="text-muted">
Here’s a quick overview of your activity
</p>

</div>


<!-- STATS -->

<div class="row g-4">

<div class="col-md-4">

<div class="stat-card bg-orange">

<h4>Total Bookings</h4>

<h2><?= $bookings ?></h2>

</div>

</div>

<div class="col-md-4">

<div class="stat-card bg-green">

<h4>Total Spent</h4>

<h2>₹<?= $total_spent ?? 0 ?></h2>

</div>

</div>

<div class="col-md-4">

<div class="stat-card bg-darkblue">

<h4>Available Vehicles</h4>

<h2><?= $vehicles ?></h2>

</div>

</div>

</div>


<!-- QUICK ACTIONS -->

<div class="mt-5">

<h4>Quick Actions</h4>

<div class="mt-3">

<a href="book-vehicle.php" class="btn btn-primary me-3">
Browse Vehicles
</a>

<a href="my-bookings.php" class="btn btn-success me-3">
My Bookings
</a>

<a href="feedback.php" class="btn btn-warning">
Give Feedback
</a>

</div>

</div>


<!-- FEATURED VEHICLES -->

<div class="mt-5">

<h4>Popular Vehicles</h4>

<div class="row g-4 mt-2">

<?php
$vehicles_query = mysqli_query($conn,"SELECT * FROM vehicles LIMIT 3");
while($v = mysqli_fetch_assoc($vehicles_query)){
?>

<div class="col-md-4">

<div class="card vehicle-card">

<img src="../assets/images/<?= $v['image'] ?>" class="card-img-top">

<div class="card-body">

<h5><?= $v['name'] ?></h5>

<p>₹<?= $v['price_per_day'] ?> / day</p>

<a href="book-vehicle.php" class="btn btn-primary">
Book Now
</a>

</div>

</div>

</div>

<?php } ?>

</div>

</div>

</div>

</body>
</html>