<?php
session_start();
$root = '../';
include '../config/db.php';
if(!isset($_SESSION['user_id'])){ header("Location: ../auth/login.php"); exit(); }

$uid    = $_SESSION['user_id'];
$filter = $_GET['filter'] ?? 'all';
$where  = "b.user_id=$uid";
if($filter==='pending')  $where .= " AND b.status='pending'";
if($filter==='approved') $where .= " AND b.status='approved'";
if($filter==='rejected') $where .= " AND b.status='rejected'";

$q = mysqli_query($conn,"SELECT b.*,v.name vname,v.image vimg,v.brand vbrand FROM bookings b JOIN vehicles v ON b.vehicle_id=v.id WHERE $where ORDER BY b.id DESC");
$total = mysqli_num_rows($q);
$counts = [
  'all'      => mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM bookings WHERE user_id=$uid"))['c'],
  'pending'  => mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM bookings WHERE user_id=$uid AND status='pending'"))['c'],
  'approved' => mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM bookings WHERE user_id=$uid AND status='approved'"))['c'],
  'rejected' => mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM bookings WHERE user_id=$uid AND status='rejected'"))['c'],
];
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<title>My Bookings — DriveEase</title>
</head><body>
<!-- BG -->
<div class="dash-bg" aria-hidden="true"><div class="dash-bg-blob1"></div><div class="dash-bg-blob2"></div><div class="dash-bg-blob3"></div><div class="dash-bg-grid"></div></div>
<?php include '../includes/navbar_user.php'; ?>
<div class="page-wrap">
  <div class="dash-header">
    <div class="eyebrow">My Account</div>
    <h1>My Bookings</h1>
    <div class="sub"><?= $total ?> booking<?= $total!=1?'s':'' ?> found</div>
  </div>

  <div class="cat-tabs mb-4">
    <?php foreach(['all'=>'All','pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected'] as $k=>$l): ?>
      <a href="?filter=<?= $k ?>" class="cat-tab <?= $filter===$k?'active':'' ?>"><?= $l ?> (<?= $counts[$k] ?>)</a>
    <?php endforeach; ?>
  </div>

  <?php if($total===0): ?>
  <div class="empty-state">
    <div class="empty-icon">📋</div>
    <h4>No bookings here</h4>
    <p>Ready to hit the road?</p>
    <a href="vehicles.php" class="btn btn-primary btn-md">Browse Vehicles</a>
  </div>
  <?php else: ?>
  <div class="row g-4 mb-5">
    <?php while($row=mysqli_fetch_assoc($q)):
      $sc=$row['status']==='approved'?'badge-approved':($row['status']==='rejected'?'badge-rejected':'badge-pending');
      $psc=$row['payment_status']==='paid'?'badge-paid':'badge-pending';
      $start=new DateTime($row['start_date']); $end=new DateTime($row['end_date']);
      $days=$end->diff($start)->days+1;
    ?>
    <div class="col-md-6">
      <div class="booking-card">
        <div style="display:flex;gap:14px;align-items:flex-start;margin-bottom:16px">
          <img src="../assets/images/<?= htmlspecialchars($row['vimg']) ?>"
               style="width:80px;height:60px;object-fit:cover;border-radius:var(--r1);border:1px solid var(--b1);flex-shrink:0">
          <div>
            <div style="font-size:.72rem;color:var(--orange);font-weight:700;text-transform:uppercase;letter-spacing:.06em"><?= htmlspecialchars($row['vbrand']) ?></div>
            <div style="font-family:var(--ff-head);font-weight:700;color:var(--t1)"><?= htmlspecialchars($row['vname']) ?></div>
            <div style="font-size:.75rem;color:var(--t3)">Booking #<?= $row['id'] ?></div>
          </div>
        </div>
        <div class="booking-meta">
          <strong>Dates:</strong> <?= $row['start_date'] ?> → <?= $row['end_date'] ?><br>
          <strong>Duration:</strong> <span style="color:var(--cyan)"><?= $days ?> day<?= $days!=1?'s':'' ?></span><br>
          <strong>Total:</strong> <span style="color:var(--orange);font-weight:700">₹<?= number_format($row['total_price']) ?></span>
        </div>
        <div class="booking-footer">
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            <span class="badge <?= $sc ?>"><?= ucfirst($row['status']) ?></span>
            <span class="badge <?= $psc ?>"><?= ucfirst($row['payment_status']) ?></span>
          </div>
          <?php if($row['payment_status']==='paid'): ?>
            <a href="invoice.php?id=<?= $row['id'] ?>" class="btn btn-dark btn-xs">
              <i class="fa-solid fa-file-invoice me-1"></i>Invoice
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
  <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
</body></html>
