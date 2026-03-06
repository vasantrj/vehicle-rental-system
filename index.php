<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>

<title>Vehicle Rental System</title>

<link rel="stylesheet" href="assets/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<?php include "includes/navbar_public.php"; ?>

<section class="hero">

<div class="container">

<h1>Rent Vehicles Instantly</h1>

<p>
Smart vehicle rental platform with secure booking and instant invoice.
</p>

<a href="auth/register.php" class="btn btn-light btn-lg me-2">
Get Started
</a>

<a href="auth/login.php" class="btn btn-light btn-lg">
Login
</a>

</div>

</section>

<section class="py-5">

<div class="container">

<h2 class="text-center mb-5">
Popular Vehicles
</h2>

<div class="row g-4">

<div class="col-md-3">

<div class="card vehicle-card">

<img src="assets/images/car1.jpg">

<div class="card-body text-center">

<h5>Sedan Car</h5>

<p>Comfortable city rides</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card vehicle-card">

<img src="assets/images/bike1.jpg">

<div class="card-body text-center">

<h5>Sport Bike</h5>

<p>Perfect for quick travel</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card vehicle-card">

<img src="assets/images/car2.jpg">

<div class="card-body text-center">

<h5>Luxury Car</h5>

<p>Premium driving experience</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card vehicle-card">

<img src="assets/images/suv1.jpg">

<div class="card-body text-center">

<h5>SUV</h5>

<p>Great for family trips</p>

</div>

</div>

</div>

</div>

</div>

</section>

<section class="py-5 bg-light">

<div class="container">

<h2 class="text-center mb-5">
Why Choose Us
</h2>

<div class="row g-4">

<div class="col-md-4">

<div class="feature-box">

<h4>📅 Smart Booking</h4>

<p>
Choose available dates using our intelligent booking calendar.
</p>

</div>

</div>

<div class="col-md-4">

<div class="feature-box">

<h4>💳 Secure Payment</h4>

<p>
Pay safely with Razorpay integration.
</p>

</div>

</div>

<div class="col-md-4">

<div class="feature-box">

<h4>🧾 Instant Invoice</h4>

<p>
Download professional invoices immediately after booking.
</p>

</div>

</div>

</div>

</div>

</section>

<section class="cta">

<div class="container">

<h2>Start Renting Today</h2>

<p class="mt-3 mb-4">
Create your account and book vehicles in seconds.
</p>

<a href="auth/register.php" class="btn btn-light btn-lg">
Create Account
</a>

</div>

</section>

<footer class="bg-dark text-white text-center p-4">

Vehicle Rental System © <?= date("Y") ?>

</footer>

</body>
</html>