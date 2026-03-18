<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>

<title>Vehicle Rental</title>

<link rel="stylesheet" href="assets/style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<?php include "includes/navbar_public.php"; ?>

<!-- HERO -->

<section class="hero">

<div class="hero-content">

<h1>Rent Your Dream Ride</h1>

<p>Luxury • Comfort • Performance</p>

<?php if(isset($_SESSION['user_id'])){ ?>

<a href="user/book-vehicle.php" class="btn-main">
Browse Vehicles
</a>

<?php }else{ ?>

<a href="auth/login.php" class="btn-main">
Login to Book
</a>

<?php } ?>

</div>

</section>

<!-- POPULAR VEHICLES -->

<section class="container mt-5">

<h2 class="text-center mb-4">Popular Vehicles</h2>

<div class="row g-4">

<?php
include "config/db.php";

$q=mysqli_query($conn,"SELECT * FROM vehicles LIMIT 6");

while($v=mysqli_fetch_assoc($q)){
?>

<div class="col-md-4">

<div class="vehicle-card">

<img src="assets/images/<?php echo $v['image']; ?>">

<div class="vehicle-info">

<h5><?php echo $v['name']; ?></h5>

<p class="vehicle-price">
₹<?php echo $v['price_per_day']; ?> / day
</p>

<?php if(isset($_SESSION['user_id'])){ ?>

<a href="user/book-vehicle.php" class="btn-book">
Book Now
</a>

<?php }else{ ?>

<a href="auth/login.php" class="btn-book">
Login to Book
</a>

<?php } ?>

</div>

</div>

</div>

<?php } ?>

</div>

</section>

<!-- TESTIMONIAL -->

<section class="container mt-5">

<h2 class="text-center mb-5">What Our Customers Say</h2>

<div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">

<div class="carousel-inner">

<div class="carousel-item active">

<div class="text-center">

<i class="fa-solid fa-star"></i>
<i class="fa-solid fa-star"></i>
<i class="fa-solid fa-star"></i>
<i class="fa-solid fa-star"></i>
<i class="fa-solid fa-star"></i>

<p>
Amazing rental experience. Booking was smooth and the car was perfect.
</p>

<strong>Rahul Sharma</strong>

</div>

</div>

<div class="carousel-item">

<div class="text-center">

<i class="fa-solid fa-star"></i>
<i class="fa-solid fa-star"></i>
<i class="fa-solid fa-star"></i>
<i class="fa-solid fa-star"></i>
<i class="fa-solid fa-star"></i>

<p>
Great service and affordable pricing.
</p>

<strong>Ananya Patel</strong>

</div>

</div>

<div class="carousel-item">

<div class="text-center">

<i class="fa-solid fa-star"></i>
<i class="fa-solid fa-star"></i>
<i class="fa-solid fa-star"></i>
<i class="fa-solid fa-star"></i>
<i class="fa-solid fa-star"></i>

<p>
Secure payment and easy booking process.
</p>

<strong>Vikram Singh</strong>

</div>

</div>

</div>

</div>

</section>

<!-- WHY US -->

<section class="container text-center mt-5">

<h2>Why Choose Us</h2>

<div class="row mt-4">

<div class="col-md-3 why-box">

<h4>💳 Secure Payment</h4>
<p>Safe Razorpay integration</p>

</div>

<div class="col-md-3 why-box">

<h4>🚗 Premium Vehicles</h4>
<p>Well maintained fleet</p>

</div>

<div class="col-md-3 why-box">

<h4>⚡ Instant Booking</h4>
<p>Quick and easy process</p>

</div>

<div class="col-md-3 why-box">

<h4>📞 24/7 Support</h4>
<p>Always available</p>

</div>

</div>

</section>

<!-- FOOTER -->

<footer class="text-center p-4">

<p>© <?php echo date("Y"); ?> Vehicle Rental System</p>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>