<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>

<title>Vehicle Rental System</title>

<link rel="stylesheet" href="assets/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

.hero{
background:linear-gradient(120deg,#007bff,#0056b3);
color:white;
padding:100px 0;
text-align:center;
}

.hero h1{
font-size:48px;
font-weight:600;
}

.feature-box{
padding:25px;
border-radius:10px;
background:white;
box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

.vehicle-card{
border:none;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
border-radius:10px;
}

</style>

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">

<a class="navbar-brand" href="#">🚗 Vehicle Rental</a>

<div class="ms-auto">

<a href="auth/login.php" class="btn btn-light me-2">
Login
</a>

<a href="auth/register.php" class="btn btn-warning">
Register
</a>

</div>

</div>
</nav>

<section class="hero">

<div class="container">

<h1>Book Vehicles Easily</h1>

<p class="lead">
Smart vehicle rental platform with secure payments and instant booking.
</p>

<a href="auth/register.php" class="btn btn-light btn-lg mt-3">
Get Started
</a>

</div>

</section>

<section class="py-5">

<div class="container">

<h2 class="text-center mb-4">Platform Features</h2>

<div class="row">

<div class="col-md-4">
<div class="feature-box text-center">
<h4>📅 Smart Booking</h4>
<p>Choose dates easily with availability calendar.</p>
</div>
</div>

<div class="col-md-4">
<div class="feature-box text-center">
<h4>💳 Secure Payments</h4>
<p>Pay securely with Razorpay payment gateway.</p>
</div>
</div>

<div class="col-md-4">
<div class="feature-box text-center">
<h4>🧾 Instant Invoice</h4>
<p>Download professional invoice after booking.</p>
</div>
</div>

</div>

</div>

</section>

<footer class="bg-dark text-white text-center p-3">

Vehicle Rental System © <?= date("Y") ?>

</footer>

</body>
</html>