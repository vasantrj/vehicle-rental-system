<?php
session_start();

include "../config/db.php";
require "../config/razorpay.php";
require "../vendor/autoload.php";

use Razorpay\Api\Api;

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {
    header("Location: ../auth/login.php");
    exit();
}

$booking_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn,
"SELECT total_price FROM bookings WHERE id=? AND user_id=? AND payment_status='pending'"
);

mysqli_stmt_bind_param($stmt,"ii",$booking_id,$user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if(!$data){
    die("Invalid booking.");
}

$total_price = $data['total_price'];

$api = new Api($keyId,$keySecret);

$order = $api->order->create([
'receipt' => 'booking_'.$booking_id,
'amount' => $total_price * 100,
'currency' => 'INR'
]);

$orderId = $order['id'];

?>

<!DOCTYPE html>
<html>
<head>

<title>Payment</title>

<link rel="stylesheet" href="../assets/style.css">

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

</head>

<body>

<?php include "../includes/navbar.php"; ?>

<div class="container mt-5 text-center">

<h3>Complete Payment</h3>

<button id="payBtn" class="btn btn-primary">
Pay ₹<?= $total_price ?>
</button>

</div>

<script>

var options = {

"key": "<?= $keyId ?>",
"amount": "<?= $total_price * 100 ?>",
"currency": "INR",
"name": "Vehicle Rental System",
"description": "Vehicle Booking Payment",
"order_id": "<?= $orderId ?>",

handler: function (response){

window.location="payment_verify.php?booking_id=<?= $booking_id ?>&payment_id="+response.razorpay_payment_id+"&order_id="+response.razorpay_order_id+"&signature="+response.razorpay_signature;

}

};

var rzp = new Razorpay(options);

document.getElementById("payBtn").onclick=function(e){

rzp.open();
e.preventDefault();

}

</script>

<?php include "../includes/footer.php"; ?>

</body>
</html>