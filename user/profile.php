<?php

session_start();
include "../config/db.php";

if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
exit();
}

$user_id=$_SESSION['user_id'];

/* GET USER INFO */

$stmt=mysqli_prepare($conn,"SELECT * FROM users WHERE id=?");
mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);
$result=mysqli_stmt_get_result($stmt);
$user=mysqli_fetch_assoc($result);

/* BOOKING STATS */

$total_bookings=mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as total FROM bookings WHERE user_id=$user_id"
))['total'];

$total_spent=mysqli_fetch_assoc(mysqli_query($conn,
"SELECT SUM(total_price) as total FROM bookings WHERE user_id=$user_id"
))['total'];

?>

<?php include "../includes/navbar_user.php"; ?>

<!DOCTYPE html>
<html>
<head>

<title>Profile</title>

<link rel="stylesheet" href="../assets/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h2>User Profile</h2>

<div class="row mt-4">

<div class="col-md-6">

<div class="card p-4">

<h5>Account Information</h5>

<p><strong>Name:</strong> <?= $user['name'] ?></p>

<p><strong>Email:</strong> <?= $user['email'] ?></p>

<p><strong>Phone:</strong> <?= $user['phone'] ?? "Not added" ?></p>

<a href="edit-profile.php" class="btn btn-primary">
Edit Profile
</a>

<a href="change-password.php" class="btn btn-warning">
Change Password
</a>

</div>

</div>

<div class="col-md-6">

<div class="card p-4">

<h5>Booking Statistics</h5>

<p><strong>Total Bookings:</strong> <?= $total_bookings ?></p>

<p><strong>Total Spent:</strong> ₹<?= $total_spent ?? 0 ?></p>

</div>

</div>

</div>

</div>

</body>
</html>