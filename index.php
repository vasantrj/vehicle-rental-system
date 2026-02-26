<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Vehicle Rental Management System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Your CSS -->
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">VRMS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Vehicles</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Drivers</a></li>
        <li class="nav-item"><a class="nav-link" href="#">About</a></li>
      </ul>
      <div class="d-flex gap-2">
        <a class="btn btn-outline-primary" href="auth/login.php">Login</a>
        <a class="btn btn-primary" href="auth/register.php">Sign Up</a>
      </div>
    </div>
  </div>
</nav>

<!-- Hero -->
<section class="hero">
  <div class="container">
    <div class="row align-items-center min-vh-100">
      
      <div class="col-md-6">
        <h1 class="display-5 fw-bold">Vehicle Rental Management System</h1>
        <p class="text-muted mt-3">
          Manage vehicles, bookings, and customers in one simple platform.
        </p>
        <div class="mt-4">
          <a href="auth/login.php" class="btn btn-primary btn-lg me-2">Book a Vehicle</a>
          <a href="#" class="btn btn-outline-secondary btn-lg">Explore Vehicles</a>
        </div>
      </div>

      <div class="col-md-6 text-center">
        <img src="assets/images/hero-car.png" alt="Vehicle" class="img-fluid hero-img">
      </div>

    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/main.js"></script>
</body>
</html>