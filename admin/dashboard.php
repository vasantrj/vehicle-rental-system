<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Welcome Admin 👑</h2>
    <hr>

    <a class="btn btn-primary" href="add-vehicle.php">Add Vehicle</a>
    <a class="btn btn-warning" href="manage-vehicles.php">Manage Vehicles</a>
    <a class="btn btn-success" href="bookings.php">View Bookings</a>
    <a class="btn btn-danger" href="../auth/logout.php">Logout</a>
</div>

</body>
</html>