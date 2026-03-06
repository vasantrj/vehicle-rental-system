<?php

session_start();

include "../config/db.php";
require "../config/razorpay.php";
require "../vendor/autoload.php";
require "../mail_helper.php";

use Razorpay\Api\Api;

$booking_id = $_GET['booking_id'];
$payment_id = $_GET['payment_id'];
$order_id = $_GET['order_id'];
$signature = $_GET['signature'];

$generated_signature = hash_hmac(
"sha256",
$order_id."|".$payment_id,
$keySecret
);

if($generated_signature==$signature){

$stmt=mysqli_prepare($conn,"
SELECT b.total_price,b.start_date,b.end_date,
u.email,u.name,
v.name AS vehicle_name
FROM bookings b
JOIN users u ON b.user_id=u.id
JOIN vehicles v ON b.vehicle_id=v.id
WHERE b.id=?
");

mysqli_stmt_bind_param($stmt,"i",$booking_id);
mysqli_stmt_execute($stmt);
$result=mysqli_stmt_get_result($stmt);
$data=mysqli_fetch_assoc($result);

$update=mysqli_prepare($conn,"
UPDATE bookings
SET status='approved',
payment_status='paid',
payment_id='$payment_id',
payment_method='Razorpay'
WHERE id=$booking_id
");

mysqli_stmt_bind_param($update,"si",$payment_id,$booking_id);
mysqli_stmt_execute($update);

sendBookingEmail(
$data['email'],
$data['name'],
$data['vehicle_name'],
$data['start_date'],
$data['end_date'],
$data['total_price']
);

header("Location: payment_result.php?status=success");

}
else{

header("Location: payment_result.php?status=failed");

}

?>