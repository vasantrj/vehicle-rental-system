<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

  <h2>Welcome User 😊</h2>
  <p>What would you like to do?</p>

  <div class="mt-3">
    <a class="btn btn-primary me-2" href="vehicles.php">Browse Vehicles</a>
    <a class="btn btn-success me-2" href="my-bookings.php">My Bookings</a>
    <a class="btn btn-danger" href="../auth/logout.php">Logout</a>
  </div>

</body>
</html>