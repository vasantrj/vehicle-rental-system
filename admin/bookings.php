<?php
session_start();
$root='../';
include '../config/db.php';
if(!isset($_SESSION['role'])||$_SESSION['role']!=='admin'){ header("Location: ../auth/login.php"); exit(); }

if(isset($_GET['approve'])){ $id=intval($_GET['approve']); mysqli_query($conn,"UPDATE bookings SET status='approved' WHERE id=$id"); header("Location: bookings.php?msg=approved"); exit(); }
if(isset($_GET['reject'])) { $id=intval($_GET['reject']);  mysqli_query($conn,"UPDATE bookings SET status='rejected' WHERE id=$id"); header("Location: bookings.php?msg=rejected"); exit(); }

$filter=$_GET['filter']??'all';
$where="1=1";
if($filter==='pending')  $where="b.status='pending'";
if($filter==='approved') $where="b.status='approved'";
if($filter==='rejected') $where="b.status='rejected'";
if($filter==='paid')     $where="b.payment_status='paid'";

$search = $_GET['search'] ?? '';
if($search) $where .= " AND (u.name LIKE '%".mysqli_real_escape_string($conn,$search)."%' OR v.name LIKE '%".mysqli_real_escape_string($conn,$search)."%')";

$result=mysqli_query($conn,"SELECT b.*,u.name uname,v.name vname FROM bookings b JOIN users u ON b.user_id=u.id JOIN vehicles v ON b.vehicle_id=v.id WHERE $where ORDER BY b.id DESC");
$total=mysqli_num_rows($result);
$counts=[
  'all'     => mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM bookings"))['c'],
  'pending' => mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM bookings WHERE status='pending'"))['c'],
  'approved'=> mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM bookings WHERE status='approved'"))['c'],
  'rejected'=> mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM bookings WHERE status='rejected'"))['c'],
  'paid'    => mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM bookings WHERE payment_status='paid'"))['c'],
];
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<title>Bookings — Admin</title>
</head><body>
<!-- BG -->
<div class="dash-bg" aria-hidden="true"><div class="dash-bg-blob1"></div><div class="dash-bg-blob2"></div><div class="dash-bg-blob3"></div><div class="dash-bg-grid"></div></div>
<?php include '../includes/navbar_admin.php'; ?>
<div class="page-wrap">
  <div class="dash-header">
    <div class="eyebrow">Management</div>
    <h1>Manage Bookings</h1>
    <div class="sub"><?= $total ?> booking<?= $total!=1?'s':'' ?> found<?= $search?" for \"$search\"":'' ?></div>
  </div>

  <?php if(isset($_GET['msg'])): ?>
    <div class="alert alert-<?= $_GET['msg']==='approved'?'success':'danger' ?> mb-4">
      <?= $_GET['msg']==='approved'?'✅ Booking approved successfully!':'❌ Booking rejected.' ?>
    </div>
  <?php endif; ?>

  <!-- FILTER + SEARCH -->
  <div class="d-flex gap-3 align-items-center mb-4 flex-wrap">
    <div class="cat-tabs mb-0" style="margin:0">
      <?php foreach(['all'=>'All','pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected','paid'=>'Paid'] as $k=>$l): ?>
        <a href="?filter=<?= $k ?>" class="cat-tab <?= $filter===$k?'active':'' ?>"><?= $l ?> <span style="opacity:.6">(<?= $counts[$k] ?>)</span></a>
      <?php endforeach; ?>
    </div>
    <form method="GET" style="margin-left:auto;display:flex;gap:8px">
      <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
      <input type="text" name="search" class="input-field" placeholder="Search user or vehicle..." value="<?= htmlspecialchars($search) ?>" style="width:220px">
      <button type="submit" class="btn btn-dark btn-md"><i class="fa-solid fa-magnifying-glass"></i></button>
      <?php if($search): ?><a href="bookings.php" class="btn btn-ghost btn-md">Clear</a><?php endif; ?>
    </form>
  </div>

  <div class="data-table-wrap mb-5" style="overflow-x:auto">
    <table class="data-table">
      <thead><tr><th>#</th><th>User</th><th>Vehicle</th><th>From</th><th>To</th><th>Days</th><th>Total</th><th>Booking</th><th>Payment</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if($total===0): ?>
        <tr><td colspan="10" style="text-align:center;padding:40px;color:var(--t2)">No bookings found</td></tr>
        <?php endif; ?>
        <?php while($row=mysqli_fetch_assoc($result)):
          $sc=$row['status']==='approved'?'badge-approved':($row['status']==='rejected'?'badge-rejected':'badge-pending');
          $psc=$row['payment_status']==='paid'?'badge-paid':'badge-pending';
          $start=new DateTime($row['start_date']); $end=new DateTime($row['end_date']);
          $days=$end->diff($start)->days+1;
        ?>
        <tr>
          <td>#<?= $row['id'] ?></td>
          <td style="font-weight:600;color:var(--t1)"><?= htmlspecialchars($row['uname']) ?></td>
          <td><?= htmlspecialchars($row['vname']) ?></td>
          <td><?= $row['start_date'] ?></td>
          <td><?= $row['end_date'] ?></td>
          <td style="color:var(--cyan)"><?= $days ?>d</td>
          <td style="color:var(--orange);font-weight:700">₹<?= number_format($row['total_price']) ?></td>
          <td><span class="badge <?= $sc ?>"><?= ucfirst($row['status']) ?></span></td>
          <td><span class="badge <?= $psc ?>"><?= ucfirst($row['payment_status']) ?></span></td>
          <td>
            <?php if($row['status']==='pending'): ?>
              <a href="?approve=<?= $row['id'] ?>&filter=<?= $filter ?>" class="btn btn-green btn-xs me-1" onclick="return confirm('Approve booking #<?= $row['id'] ?>?')">✓ Approve</a>
              <a href="?reject=<?= $row['id'] ?>&filter=<?= $filter ?>"  class="btn btn-red btn-xs" onclick="return confirm('Reject booking #<?= $row['id'] ?>?')">✗ Reject</a>
            <?php else: ?><span style="color:var(--t3)">—</span><?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../includes/admin_footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
</body></html>
