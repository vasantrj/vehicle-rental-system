<?php

session_start();
include "../config/db.php";

if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
exit();
}

$user_id=$_SESSION['user_id'];

$query=mysqli_query($conn,"
SELECT b.*,v.name 
FROM bookings b
JOIN vehicles v ON b.vehicle_id=v.id
WHERE b.user_id=$user_id
ORDER BY b.id DESC
");

?>

<?php include "../includes/navbar_user.php"; ?>

<!DOCTYPE html>
<html>
<head>

<title>My Bookings</title>

<link rel="stylesheet" href="../assets/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

.header{
background:linear-gradient(120deg,#ff7a18,#ff4e00);
padding:30px;
color:white;
border-radius:12px;
margin-top:20px;
}

.booking-card{
border:none;
border-radius:15px;
box-shadow:0 8px 25px rgba(0,0,0,0.1);
padding:25px;
transition:0.3s;
}

.booking-card:hover{
transform:translateY(-5px);
}

.badge-approved{
background:#16a34a;
}

.badge-pending{
background:#f59e0b;
}

.badge-cancelled{
background:#ef4444;
}

</style>

</head>

<body>

<div class="container">

<!-- HEADER -->

<div class="header">

<h2>My Bookings</h2>

<p>View and manage your vehicle bookings</p>

</div>

<div class="row mt-4 g-4">

<?php

if(mysqli_num_rows($query)==0){

echo "<div class='text-center mt-5'>
<h4>No bookings yet</h4>
<p>Browse vehicles and book your first ride 🚗</p>
<a href='book-vehicle.php' class='btn btn-primary'>Browse Vehicles</a>
</div>";

}

while($row=mysqli_fetch_assoc($query)){

$status_class="badge-pending";

if($row['status']=="approved"){
$status_class="badge-approved";
}

if($row['status']=="cancelled"){
$status_class="badge-cancelled";
}

?>

<div class="col-md-6">

<div class="booking-card">

<h5><?= $row['name'] ?></h5>

<p>

<strong>From:</strong> <?= $row['start_date'] ?><br>

<strong>To:</strong> <?= $row['end_date'] ?><br>

<strong>Total:</strong> ₹<?= $row['total_price'] ?>

</p>

<span class="badge <?= $status_class ?>">

<?= ucfirst($row['status']) ?>

</span>

<div class="mt-3">

<a href="invoice.php?id=<?= $row['id'] ?>" class="btn btn-dark btn-sm">

View Invoice

</a>

</div>

</div>

</div>

<?php } ?>

</div>

</div>

</body>
</html>