<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("No booking ID provided.");
}

$booking_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn,
    "SELECT total_price FROM bookings 
     WHERE id=? AND user_id=? AND payment_status='pending'"
);

mysqli_stmt_bind_param($stmt, "ii", $booking_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Invalid or already processed booking.");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Razorpay Payment</title>
<link rel="stylesheet" href="../assets/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.payment-box {
    max-width: 400px;
    margin: auto;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}
</style>
</head>
<body class="bg-light p-5">
<?php include "../includes/navbar.php"; ?>
<div class="container mt-4">
<div class="payment-box bg-white text-center">
    <h4>Razorpay Secure Payment</h4>
    <p>Total Amount</p>
    <h3>₹<?= $data['total_price'] ?></h3>
    <div class="spinner-border text-primary mt-3"></div>
    <p class="mt-3">Processing Payment...</p>
</div>

<form id="payForm" method="post" action="payment_verify.php">
<input type="hidden" name="booking_id" value="<?= $booking_id ?>">
</form>

<script>
setTimeout(() => {
    document.getElementById("payForm").submit();
}, 3000);
</script>
</div>
<?php include "../includes/footer.php"; ?>
</body>
</html>