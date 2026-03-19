<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
session_start();
include '../config/db.php';
require '../config/razorpay.php';
require '../razorpay_loader.php';
require '../mail_helper.php';
use Razorpay\Api\Api;

$booking_id = intval($_GET['booking_id']??0);
$payment_id = $_GET['payment_id']??'';
$order_id   = $_GET['order_id']??'';
$signature  = $_GET['signature']??'';

$gen_sig = hash_hmac('sha256',$order_id.'|'.$payment_id,$keySecret);

if($gen_sig===$signature){
  $stmt=mysqli_prepare($conn,"SELECT b.total_price,b.start_date,b.end_date,u.email,u.name,v.name AS vname FROM bookings b JOIN users u ON b.user_id=u.id JOIN vehicles v ON b.vehicle_id=v.id WHERE b.id=?");
  mysqli_stmt_bind_param($stmt,'i',$booking_id);
  mysqli_stmt_execute($stmt);
  $data=mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

  $upd=mysqli_prepare($conn,"UPDATE bookings SET status='approved',payment_status='paid',payment_id=?,payment_method='Razorpay' WHERE id=?");
  mysqli_stmt_bind_param($upd,'si',$payment_id,$booking_id);
  mysqli_stmt_execute($upd);

  sendBookingEmail($data['email'],$data['name'],$data['vname'],$data['start_date'],$data['end_date'],$data['total_price']);
  header("Location: payment_result.php?status=success&id=$booking_id");
} else {
  header("Location: payment_result.php?status=failed");
}
exit();
