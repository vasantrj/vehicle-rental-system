<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {
    header("Location: ../auth/login.php");
    exit();
}

$vehicle_id = $_GET['id'];
$user_email = $_SESSION['email'];

// Check vehicle availability
$vehRes = mysqli_query($conn, "SELECT status FROM vehicles WHERE id=$vehicle_id");
$veh = mysqli_fetch_assoc($vehRes);

if ($veh['status'] !== 'available') {
    die("This vehicle is already booked.");
}

// Get user id
$userRes = mysqli_query($conn, "SELECT id FROM users WHERE email='$user_email'");
$user = mysqli_fetch_assoc($userRes);
$user_id = $user['id'];

$msg = "";

if (isset($_POST['book'])) {
    $start = $_POST['start'];
    $end = $_POST['end'];

    mysqli_query($conn, "INSERT INTO bookings (user_id, vehicle_id, start_date, end_date, status)
                         VALUES ($user_id, $vehicle_id, '$start', '$end', 'pending')");
    $msg = "Booking request sent! Waiting for admin approval.";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Book Vehicle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <h2>Book Vehicle</h2>

  <?php if($msg) echo "<p class='text-success'>$msg</p>"; ?>

  <form method="post" class="w-50">
    <label>Start Date</label>
    <input class="form-control mb-2" type="date" name="start" required>
    <label>End Date</label>
    <input class="form-control mb-2" type="date" name="end" required>
    <button class="btn btn-primary" name="book">Confirm Booking</button>
  </form>

  <br>
  <a href="vehicles.php">Back</a>
</body>
</html>