<?php
session_start();
$root = '../';
$status = $_GET['status']??'failed';
$booking_id = intval($_GET['id']??0);
include '../config/db.php';

$booking = null;
if($booking_id){
  $stmt=mysqli_prepare($conn,"SELECT b.*,v.name vname,v.brand vbrand,v.image vimg FROM bookings b JOIN vehicles v ON b.vehicle_id=v.id WHERE b.id=?");
  mysqli_stmt_bind_param($stmt,'i',$booking_id);
  mysqli_stmt_execute($stmt);
  $booking=mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<title><?= $status==='success'?'✅ Booking Confirmed':'❌ Payment Failed' ?> — DriveEase</title>
</head><body>
<div class="dash-bg" aria-hidden="true">
  <div class="dash-bg-blob1"></div><div class="dash-bg-blob2"></div>
  <div class="dash-bg-blob3"></div><div class="dash-bg-grid"></div>
</div>
<?php include '../includes/navbar_user.php'; ?>
<div class="payment-result">
  <div class="result-card">
    <?php if($status==='success'): ?>
      <div class="result-icon success">✅</div>
      <h2 style="color:#6ee7b7">Booking Confirmed!</h2>
      <p>Payment successful. A confirmation email has been sent to your inbox (Check Spam section also !!).</p>

      <?php if($booking): ?>
      <div style="background:rgba(14,22,42,.8);border:1px solid var(--b1);border-radius:var(--r2);padding:18px 20px;margin-bottom:24px;text-align:left">
        <div style="display:flex;gap:12px;align-items:center;margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid var(--b1)">
          <img src="../assets/images/<?= htmlspecialchars($booking['vimg']) ?>"
               style="width:56px;height:44px;object-fit:cover;border-radius:6px;border:1px solid var(--b1);flex-shrink:0">
          <div>
            <div style="font-family:var(--ff-head);font-weight:700;color:var(--t1);font-size:.95rem"><?= htmlspecialchars($booking['vname']) ?></div>
            <div style="font-size:.75rem;color:var(--orange)"><?= htmlspecialchars($booking['vbrand']) ?></div>
          </div>
        </div>
        <div style="font-size:.85rem;color:var(--t2);line-height:2">
          <div style="display:flex;justify-content:space-between"><span>Dates</span><span style="color:var(--t1)"><?= $booking['start_date'] ?> → <?= $booking['end_date'] ?></span></div>
          <?php
            $days=(new DateTime($booking['start_date']))->diff(new DateTime($booking['end_date']))->days+1;
          ?>
          <div style="display:flex;justify-content:space-between"><span>Duration</span><span style="color:var(--cyan);font-weight:700"><?= $days ?> day<?= $days!=1?'s':'' ?></span></div>
          <div style="display:flex;justify-content:space-between;border-top:1px solid var(--b1);padding-top:8px;margin-top:4px"><span>Total Paid</span><span style="color:var(--orange);font-weight:800;font-size:1.1rem">₹<?= number_format($booking['total_price']) ?></span></div>
        </div>
      </div>
      <?php endif; ?>

      <div class="d-flex gap-3 justify-content-center flex-wrap">
        <?php if($booking_id): ?>
          <a href="invoice.php?id=<?= $booking_id ?>" class="btn btn-primary btn-md"><i class="fa-solid fa-file-invoice me-2"></i>View Invoice</a>
        <?php endif; ?>
        <a href="my-bookings.php" class="btn btn-ghost btn-md"><i class="fa-solid fa-list me-2"></i>My Bookings</a>
      </div>

    <?php else: ?>
      <div class="result-icon fail">❌</div>
      <h2 style="color:#fca5a5">Payment Failed</h2>
      <p>Something went wrong. Your booking was not confirmed. Please try again.</p>
      <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="vehicles.php" class="btn btn-primary btn-md"><i class="fa-solid fa-rotate-left me-2"></i>Try Again</a>
        <a href="my-bookings.php" class="btn btn-ghost btn-md">My Bookings</a>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php include '../includes/scripts.php'; ?>
</body></html>
