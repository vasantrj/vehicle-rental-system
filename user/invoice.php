<?php
session_start();
$root='../';
include '../config/db.php';
if(!isset($_SESSION['user_id'])){ header("Location: ../auth/login.php"); exit(); }
if(!isset($_GET['id'])) { header("Location: my-bookings.php"); exit(); }
$bid = intval($_GET['id']);
$stmt=mysqli_prepare($conn,"SELECT b.*,u.name uname,u.email uemail,u.phone uphone,v.name vname,v.brand vbrand,v.price_per_day vprice FROM bookings b LEFT JOIN users u ON b.user_id=u.id LEFT JOIN vehicles v ON b.vehicle_id=v.id WHERE b.id=? AND (b.user_id=? OR 1)");
mysqli_stmt_bind_param($stmt,'ii',$bid,$_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$d=mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if(!$d){ header("Location: my-bookings.php"); exit(); }
$days=(new DateTime($d['start_date']))->diff(new DateTime($d['end_date']))->days+1;
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<title>Invoice INV-<?= str_pad($bid,5,'0',STR_PAD_LEFT) ?> — DriveEase</title>
<style>
@media print{
  /* ── Page setup: A4, single page ── */
  @page{size:A4 portrait;margin:8mm 10mm}
  html,body{height:auto!important;overflow:visible!important}

  /* ── Hide chrome ── */
  .site-nav,.print-hide,.dash-bg,footer,nav{display:none!important}

  /* ── Base colors ── */
  body{background:#fff!important;color:#111!important;font-size:10pt!important}

  /* ── Invoice container: shrink to fit ── */
  .invoice-page{background:#fff!important;padding:0!important;margin:0!important}
  .invoice-box{
    background:#fff!important;
    border:1px solid #d1d5db!important;
    box-shadow:none!important;
    color:#111!important;
    padding:14px 18px!important;
    max-width:100%!important;
    margin:0!important;
    page-break-inside:avoid!important;
  }
  .invoice-box::before{display:none!important}

  /* ── Header ── */
  .invoice-header{padding-bottom:10px!important;margin-bottom:10px!important}
  .invoice-company h2{color:#ea580c!important;font-size:14pt!important;margin:0 0 4px!important}
  .invoice-company p{color:#374151!important;font-size:8pt!important;line-height:1.4!important;margin:0!important}
  .invoice-title{font-size:16pt!important;color:#111!important}
  .invoice-num{font-size:10pt!important;color:#374151!important}

  /* ── Section heads ── */
  .invoice-section-head{
    color:#6b7280!important;
    font-size:7pt!important;
    padding:6px 0 4px!important;
    margin-top:8px!important;
  }

  /* ── Tables ── */
  .invoice-table{margin-bottom:6px!important}
  .invoice-table th{
    color:#6b7280!important;
    font-size:8.5pt!important;
    padding:5px 0!important;
    border-bottom-color:#e5e7eb!important;
  }
  .invoice-table td{
    color:#111!important;
    font-size:8.5pt!important;
    padding:5px 0!important;
    border-bottom-color:#e5e7eb!important;
    -webkit-text-fill-color:#111!important;
  }

  /* ── Bill-To box ── */
  .invoice-box > div[style*="background:rgba(14"]{
    background:#f9fafb!important;
    border-color:#e5e7eb!important;
    padding:8px 12px!important;
    margin-bottom:6px!important;
  }

  /* ── Total row ── */
  .invoice-total-row{
    background:#fff7ed!important;
    border:1px solid #fed7aa!important;
    border-radius:6px!important;
    padding:10px 14px!important;
    margin-top:8px!important;
  }
  .invoice-total-row span:first-child{
    color:#111!important;
    font-weight:700!important;
    font-size:10pt!important;
    -webkit-text-fill-color:#111!important;
  }
  .invoice-total-row span:last-child{
    color:#ea580c!important;
    font-weight:800!important;
    font-size:14pt!important;
    -webkit-text-fill-color:#ea580c!important;
  }

  /* ── Badges ── */
  .badge{
    border:1px solid #e5e7eb!important;
    background:#f3f4f6!important;
    color:#374151!important;
    -webkit-text-fill-color:#374151!important;
    font-size:7.5pt!important;
    padding:2px 7px!important;
  }
  .badge-paid{border-color:#bbf7d0!important;background:#f0fdf4!important;color:#15803d!important;-webkit-text-fill-color:#15803d!important}
  .badge-approved{border-color:#bbf7d0!important;background:#f0fdf4!important;color:#15803d!important;-webkit-text-fill-color:#15803d!important}
  .badge-pending{border-color:#fde68a!important;background:#fffbeb!important;color:#92400e!important;-webkit-text-fill-color:#92400e!important}
  .badge-rejected{border-color:#fecaca!important;background:#fef2f2!important;color:#b91c1c!important;-webkit-text-fill-color:#b91c1c!important}

  /* ── Footer note ── */
  .invoice-box p:last-of-type{
    color:#6b7280!important;
    font-size:7.5pt!important;
    margin-top:10px!important;
    padding-top:8px!important;
    -webkit-text-fill-color:#6b7280!important;
  }
  .invoice-box p:last-of-type span{color:#ea580c!important;-webkit-text-fill-color:#ea580c!important}
}
</style>
</head><body class="invoice-page">
<!-- BG -->
<div class="dash-bg" aria-hidden="true">
  <div class="dash-bg-blob1"></div><div class="dash-bg-blob2"></div>
  <div class="dash-bg-blob3"></div><div class="dash-bg-grid"></div>
</div>
<?php include '../includes/navbar_user.php'; ?>
<div class="invoice-box">
  <!-- Animated top border via CSS ::before -->
  <div class="invoice-header">
    <div class="invoice-company">
      <h2>🚗 DriveEase</h2>
      <p>
        support@driveease.in<br>
        +91 89512 34347<br>
        Belagavi, Karnataka, India
      </p>
    </div>
    <div class="invoice-right">
      <div class="invoice-title">INVOICE</div>
      <div class="invoice-num">INV-<?= str_pad($bid,5,'0',STR_PAD_LEFT) ?></div>
      <div style="color:var(--t2);font-size:.8rem;margin-top:6px">Date: <?= date('d M Y') ?></div>
      <div style="margin-top:8px">
        <span class="badge <?= $d['payment_status']==='paid'?'badge-paid':'badge-pending' ?>"><?= ucfirst($d['payment_status']) ?></span>
      </div>
    </div>
  </div>

  <div class="invoice-section-head">Bill To</div>
  <div style="background:rgba(14,22,42,.5);border:1px solid var(--b1);border-radius:var(--r2);padding:16px 20px;margin-bottom:8px">
    <div style="font-family:var(--ff-head);font-weight:700;font-size:1rem;color:var(--t1);margin-bottom:4px"><?= htmlspecialchars($d['uname']??'Customer') ?></div>
    <div style="color:var(--t2);font-size:.875rem;line-height:1.8">
      <?= htmlspecialchars($d['uemail']??'') ?><br>
      <?= $d['uphone'] ? htmlspecialchars($d['uphone']) : '—' ?>
    </div>
  </div>

  <div class="invoice-section-head">Booking Details</div>
  <table class="invoice-table">
    <tr><th>Vehicle</th><td style="color:var(--t1)"><strong><?= htmlspecialchars($d['vname']??'—') ?></strong> &nbsp;·&nbsp; <span style="color:var(--t2)"><?= htmlspecialchars($d['vbrand']??'—') ?></span></td></tr>
    <tr><th>Rental Period</th><td style="color:var(--t1)"><?= $d['start_date'] ?> <span style="color:var(--t3)">→</span> <?= $d['end_date'] ?></td></tr>
    <tr><th>Duration</th><td><span style="color:var(--cyan);font-weight:700"><?= $days ?> day<?= $days>1?'s':'' ?></span></td></tr>
    <tr><th>Daily Rate</th><td style="color:var(--t1)">₹<?= number_format($d['vprice']??0) ?>/day</td></tr>
    <tr><th>Booking Status</th><td><span class="badge <?= $d['status']==='approved'?'badge-approved':($d['status']==='rejected'?'badge-rejected':'badge-pending') ?>"><?= ucfirst($d['status']) ?></span></td></tr>
  </table>

  <div class="invoice-section-head">Payment Details</div>
  <table class="invoice-table">
    <tr><th>Payment Method</th><td style="color:var(--t1)"><?= htmlspecialchars($d['payment_method']??'Razorpay') ?></td></tr>
    <tr><th>Transaction ID</th><td style="color:var(--t2);font-family:monospace;font-size:.82rem"><?= $d['payment_id'] ? htmlspecialchars($d['payment_id']) : '<span style="color:var(--t3)">N/A</span>' ?></td></tr>
    <tr><th>Payment Status</th><td><span class="badge <?= $d['payment_status']==='paid'?'badge-paid':'badge-pending' ?>"><?= ucfirst($d['payment_status']) ?></span></td></tr>
    <tr><th>Invoice Date</th><td style="color:var(--t1)"><?= date('d M Y') ?></td></tr>
  </table>

  <div class="invoice-total-row">
    <span>Total Amount</span>
    <span>₹<?= number_format($d['total_price']) ?></span>
  </div>

  <p style="color:var(--t3);font-size:.8rem;text-align:center;margin-top:28px;line-height:1.7;border-top:1px solid var(--b1);padding-top:20px">
    Thank you for choosing DriveEase. We look forward to serving you again.<br>
    Need help? <span style="color:var(--orange)">support@driveease.in</span>
  </p>

  <div class="d-flex gap-3 mt-4 print-hide justify-content-center flex-wrap">
    <button onclick="window.print()" class="btn btn-primary btn-md"><i class="fa-solid fa-print me-2"></i>Print Invoice</button>
    <a href="my-bookings.php" class="btn btn-ghost btn-md"><i class="fa-solid fa-arrow-left me-2"></i>Back to Bookings</a>
  </div>
</div>
<?php include '../includes/scripts.php'; ?>
</body></html>
