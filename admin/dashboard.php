<?php
session_start();
include "../config/db.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="admin"){
header("Location: ../auth/login.php");
exit();
}

$revenue=mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(total_price) as total FROM bookings WHERE payment_status='paid'"))['total'];

$bookings=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM bookings"));

$approved=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM bookings WHERE status='approved'"));

$vehicles=mysqli_num_rows(mysqli_query($conn,"SELECT * FROM vehicles"));

?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Dashboard</title>

<link rel="stylesheet" href="../assets/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<?php include "../includes/navbar.php"; ?>

<div class="container mt-4">

<h2>Admin Dashboard</h2>

<div class="row mt-4">

<div class="col-md-3">
<div class="card p-3 text-center">
<h5>Total Revenue</h5>
<h3>₹<?= $revenue ?? 0 ?></h3>
</div>
</div>

<div class="col-md-3">
<div class="card p-3 text-center">
<h5>Total Bookings</h5>
<h3><?= $bookings ?></h3>
</div>
</div>

<div class="col-md-3">
<div class="card p-3 text-center">
<h5>Approved</h5>
<h3><?= $approved ?></h3>
</div>
</div>

<div class="col-md-3">
<div class="card p-3 text-center">
<h5>Vehicles</h5>
<h3><?= $vehicles ?></h3>
</div>
</div>

</div>

<div class="card mt-5 p-4">

<h4>Monthly Revenue</h4>

<canvas id="revenueChart"></canvas>

</div>

</div>

<script>

const ctx=document.getElementById('revenueChart');

new Chart(ctx,{
type:'bar',
data:{
labels:['Jan','Feb','Mar','Apr','May','Jun'],
datasets:[{
label:'Revenue',
data:[1200,1900,3000,5000,2000,3500],
backgroundColor:'#007bff'
}]
}
});

</script>

</body>
</html>