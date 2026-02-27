<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

$totalRevenue = mysqli_fetch_row(mysqli_query($conn,
    "SELECT SUM(total_price) FROM bookings WHERE status='approved'"
))[0] ?? 0;

$totalBookings = mysqli_fetch_row(mysqli_query($conn,
    "SELECT COUNT(*) FROM bookings"
))[0];

$approvedBookings = mysqli_fetch_row(mysqli_query($conn,
    "SELECT COUNT(*) FROM bookings WHERE status='approved'"
))[0];

$rejectedBookings = mysqli_fetch_row(mysqli_query($conn,
    "SELECT COUNT(*) FROM bookings WHERE status='rejected'"
))[0];

$topVehicle = mysqli_fetch_row(mysqli_query($conn,
    "SELECT v.name, COUNT(b.id) AS total
     FROM bookings b
     JOIN vehicles v ON b.vehicle_id = v.id
     WHERE b.status='approved'
     GROUP BY b.vehicle_id
     ORDER BY total DESC
     LIMIT 1"
));

$monthlyRevenue = mysqli_query($conn,
    "SELECT MONTH(start_date) AS month,
            SUM(total_price) AS revenue
     FROM bookings
     WHERE status='approved'
     GROUP BY MONTH(start_date)"
);
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="p-4">

<h2>Advanced Admin Dashboard</h2>

<!-- ACTION BUTTONS -->
<div class="mb-4">
    <a class="btn btn-primary" href="add-vehicle.php">Add Vehicle</a>
    <a class="btn btn-warning" href="manage-vehicles.php">Manage Vehicles</a>
    <a class="btn btn-success" href="bookings.php">View Bookings</a>
    <a class="btn btn-danger" href="../auth/logout.php">Logout</a>
</div>

<!-- STATS -->
<div class="row g-3">
<div class="col-md-3"><div class="card p-3">Revenue: ₹<?= $totalRevenue ?></div></div>
<div class="col-md-3"><div class="card p-3">Bookings: <?= $totalBookings ?></div></div>
<div class="col-md-3"><div class="card p-3">Approved: <?= $approvedBookings ?></div></div>
<div class="col-md-3"><div class="card p-3">Rejected: <?= $rejectedBookings ?></div></div>
<div class="col-md-4"><div class="card p-3">
Top Vehicle: <?= $topVehicle[0] ?? 'N/A' ?>
</div></div>
</div>

<hr>

<h4>Monthly Revenue Chart</h4>
<canvas id="revenueChart"></canvas>

<script>
const ctx = document.getElementById('revenueChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [
            <?php
            while($row = mysqli_fetch_assoc($monthlyRevenue)) {
                echo "'Month ".$row['month']."',";
            }
            ?>
        ],
        datasets: [{
            label: 'Revenue',
            data: [
                <?php
                mysqli_data_seek($monthlyRevenue, 0);
                while($row = mysqli_fetch_assoc($monthlyRevenue)) {
                    echo $row['revenue'].",";
                }
                ?>
            ],
        }]
    }
});
</script>

</body>
</html>