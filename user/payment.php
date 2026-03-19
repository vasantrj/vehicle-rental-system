<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
session_start();
$root = '../';
include '../config/db.php';
require '../config/razorpay.php';
require '../razorpay_loader.php';
use Razorpay\Api\Api;

if(!isset($_SESSION['user_id'])||$_SESSION['role']!=='user'){header("Location: ../auth/login.php");exit();}

$uid         = $_SESSION['user_id'];
$vehicle_id  = intval($_POST['vehicle_id']??0);
$start       = mysqli_real_escape_string($conn,$_POST['start_date']??'');
$end         = mysqli_real_escape_string($conn,$_POST['end_date']??'');
$price_per_day = intval($_POST['price_per_day']??0);

if(!$vehicle_id||!$start||!$end){header("Location: vehicles.php");exit();}

// Server-side guard: end date must be >= start date (same day = 1-day booking)
$startDt = new DateTime($start);
$endDt   = new DateTime($end);
if($endDt < $startDt){ header("Location: vehicles.php"); exit(); }

$days  = $startDt->diff($endDt)->days+1;
$total = $days*$price_per_day;

// Create booking (pending)
mysqli_query($conn,"INSERT INTO bookings(user_id,vehicle_id,start_date,end_date,total_price,status,payment_status) VALUES($uid,$vehicle_id,'$start','$end',$total,'pending','pending')");
$booking_id = mysqli_insert_id($conn);

// Create Razorpay order
$api   = new Api($keyId,$keySecret);
$order = $api->order->create(['receipt'=>'booking_'.$booking_id,'amount'=>$total*100,'currency'=>'INR']);
$orderId = $order['id'];

$vehicle = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM vehicles WHERE id=$vehicle_id"));
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<title>Payment — DriveEase</title>
</head><body>
<!-- BG -->
<div class="dash-bg" aria-hidden="true"><div class="dash-bg-blob1"></div><div class="dash-bg-blob2"></div><div class="dash-bg-blob3"></div><div class="dash-bg-grid"></div></div>
<?php include '../includes/navbar_user.php'; ?>
<div class="payment-result">
  <div class="result-card">
    <div class="result-icon success">💳</div>
    <h2 style="margin-bottom:8px">Complete Payment</h2>
    <p style="color:var(--t2);margin-bottom:6px"><?= htmlspecialchars($vehicle['name']??'Vehicle') ?></p>
    <p style="color:var(--t2);font-size:.875rem;margin-bottom:6px">
      <?= $start ?> → <?= $end ?> &nbsp;·&nbsp; <?= $days ?> day<?= $days>1?'s':'' ?>
    </p>
    <div style="font-family:var(--ff-head);font-size:2rem;font-weight:800;color:var(--orange);margin-bottom:28px">₹<?= number_format($total) ?></div>
    <button id="payBtn" class="btn btn-primary btn-lg" style="width:100%">
      <i class="fa-solid fa-lock me-2"></i>Pay ₹<?= number_format($total) ?> Securely
    </button>
    <p style="color:var(--t3);font-size:.75rem;margin-top:14px">
      <i class="fa-solid fa-shield-halved me-1"></i>Secured by Razorpay · 256-bit SSL
    </p>
  </div>
</div>
<?php include '../includes/scripts.php'; ?>
<script>
var rzp = new Razorpay({
  key:"<?= $keyId ?>",
  amount:"<?= $total*100 ?>",
  currency:"INR",
  name:"DriveEase",
  description:"Vehicle Booking — <?= htmlspecialchars($vehicle['name']??'') ?>",
  image:"https://i.imgur.com/n2xG2v5.png",
  order_id:"<?= $orderId ?>",
  theme:{color:"#f97316"},
  handler:function(r){
    window.location="payment_verify.php?booking_id=<?= $booking_id ?>&payment_id="+r.razorpay_payment_id+"&order_id="+r.razorpay_order_id+"&signature="+r.razorpay_signature;
  }
});
document.getElementById('payBtn').onclick=function(e){rzp.open();e.preventDefault()};
</script>
</body></html>
