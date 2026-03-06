<?php

session_start();
include "../config/db.php";

$revenue=mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(total_price) as total FROM bookings"))['total'];

$bookings=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM bookings"))['total'];

$approved=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM bookings WHERE status='approved'"))['total'];

$vehicles=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM vehicles"))['total'];

?>

<?php include "../includes/navbar_admin.php"; ?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Dashboard</title>

<link rel="stylesheet" href="../assets/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

.hero{
background:linear-gradient(120deg,#ff7a18,#ff4e00);
color:white;
padding:35px;
border-radius:14px;
margin-top:20px;
}

.stat-card{
padding:30px;
border-radius:14px;
color:white;
box-shadow:0 10px 30px rgba(0,0,0,0.15);
transition:0.3s;
}

.stat-card:hover{
transform:translateY(-5px);
}

.orange{background:#ff6a00;}
.green{background:#16a34a;}
.blue{background:#2563eb;}
.dark{background:#1f2937;}

</style>

</head>

<body>

<div class="container">

<div class="hero">

<h2>Admin Dashboard</h2>
<p>Overview of platform activity</p>

</div>

<div class="row mt-4 g-4">

<div class="col-md-3">

<div class="stat-card orange">

<h5>Total Revenue</h5>
<h2>₹<?= $revenue ?? 0 ?></h2>

</div>

</div>

<div class="col-md-3">

<div class="stat-card green">

<h5>Total Bookings</h5>
<h2><?= $bookings ?></h2>

</div>

</div>

<div class="col-md-3">

<div class="stat-card blue">

<h5>Approved</h5>
<h2><?= $approved ?></h2>

</div>

</div>

<div class="col-md-3">

<div class="stat-card dark">

<h5>Vehicles</h5>
<h2><?= $vehicles ?></h2>

</div>

</div>

</div>

</div>

</body>
</html>