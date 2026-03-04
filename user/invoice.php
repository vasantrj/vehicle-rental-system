<?php
session_start();
include "../config/db.php";

if(!isset($_GET['id'])){
    die("Invalid invoice request.");
}

$booking_id = intval($_GET['id']);

/* SAFE QUERY */
$stmt = mysqli_prepare($conn,"
SELECT 
b.id,
b.start_date,
b.end_date,
b.total_price,
b.payment_status,
b.payment_id,
b.payment_method,
u.name AS user_name,
u.email,
v.name AS vehicle_name,
v.price_per_day
FROM bookings b
LEFT JOIN users u ON b.user_id = u.id
LEFT JOIN vehicles v ON b.vehicle_id = v.id
WHERE b.id=?
");

mysqli_stmt_bind_param($stmt,"i",$booking_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if(!$data){
    die("Invoice not found.");
}

/* CALCULATE DAYS */
$days=(new DateTime($data['start_date']))
->diff(new DateTime($data['end_date']))->days + 1;

?>

<!DOCTYPE html>
<html>
<head>

<title>Invoice</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f4f6f9;
font-family:Segoe UI;
}

.invoice-box{
background:white;
padding:40px;
border-radius:12px;
box-shadow:0 10px 25px rgba(0,0,0,0.08);
max-width:900px;
margin:auto;
}

.invoice-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:30px;
}

.invoice-title{
font-size:28px;
font-weight:600;
}

.company-info{
text-align:right;
}

.table th{
background:#f8f9fa;
}

.total-row{
font-size:18px;
font-weight:600;
}

.company-name{
font-size:22px;
font-weight:600;
}

.footer-note{
margin-top:30px;
font-size:14px;
color:#777;
}

</style>

</head>

<body class="p-5">

<div class="invoice-box">

<div class="invoice-header">

<div>

<div class="company-name">Vehicle Rental Pvt Ltd</div>

<div>
support@vehiclerental.com<br>
+91 98765 43210<br>
Bangalore, India
</div>

</div>

<div class="company-info">

<div class="invoice-title">INVOICE</div>

Invoice #: INV-<?= $booking_id ?><br>
Date: <?= date("d M Y") ?>

</div>

</div>

<hr>

<h5>Customer Details</h5>

<p>
<strong><?= htmlspecialchars($data['user_name'] ?? 'Customer') ?></strong><br>
<?= htmlspecialchars($data['email'] ?? 'N/A') ?>
</p>

<h5>Booking Details</h5>

<table class="table table-bordered">

<tr>
<th>Vehicle</th>
<td><?= htmlspecialchars($data['vehicle_name'] ?? 'Vehicle') ?></td>
</tr>

<tr>
<th>Rental Period</th>
<td>
<?= $data['start_date'] ?> → <?= $data['end_date'] ?>
</td>
</tr>

<tr>
<th>Total Days</th>
<td><?= $days ?></td>
</tr>

</table>

<h5>Payment Details</h5>

<table class="table table-bordered">

<tr>
<th>Payment Method</th>
<td><?= $data['payment_method'] ?? "Razorpay" ?></td>
</tr>

<tr>
<th>Payment ID</th>
<td><?= $data['payment_id'] ?? "N/A" ?></td>
</tr>

<tr>
<th>Status</th>
<td><?= ucfirst($data['payment_status']) ?></td>
</tr>

</table>

<h5>Pricing</h5>

<table class="table table-bordered">

<tr>
<th>Price Per Day</th>
<td>₹<?= $data['price_per_day'] ?></td>
</tr>

<tr>
<th>Days</th>
<td><?= $days ?></td>
</tr>

<tr class="total-row">
<th>Total Amount</th>
<td>₹<?= $data['total_price'] ?></td>
</tr>

</table>

<div class="footer-note">

Thank you for choosing our vehicle rental service.  
We look forward to serving you again.

</div>

<div class="mt-4 text-end">

<button onclick="window.print()" class="btn btn-success">
Print Invoice
</button>

<a href="my-bookings.php" class="btn btn-secondary">
Back
</a>

</div>

</div>

</body>
</html>