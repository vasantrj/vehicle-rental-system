<?php
session_start();
$root='../';
include '../config/db.php';
if(!isset($_SESSION['role'])||$_SESSION['role']!=='admin'){ header("Location: ../auth/login.php"); exit(); }

if(isset($_GET['delete'])){
  $id=intval($_GET['delete']);
  // Don't delete the admin
  $u=mysqli_fetch_assoc(mysqli_query($conn,"SELECT role FROM users WHERE id=$id"));
  if($u && $u['role']!=='admin'){
    mysqli_query($conn,"DELETE FROM bookings WHERE user_id=$id");
    mysqli_query($conn,"DELETE FROM feedback WHERE user_id=$id");
    mysqli_query($conn,"DELETE FROM users WHERE id=$id");
  }
  header("Location: users.php?deleted=1"); exit();
}

$search = $_GET['search'] ?? '';
$where  = "role='user'";
if($search) $where .= " AND (name LIKE '%".mysqli_real_escape_string($conn,$search)."%' OR email LIKE '%".mysqli_real_escape_string($conn,$search)."%')";
$q=mysqli_query($conn,"SELECT u.*,(SELECT COUNT(*) FROM bookings WHERE user_id=u.id) bcount,(SELECT COALESCE(SUM(total_price),0) FROM bookings WHERE user_id=u.id) bspent FROM users u WHERE $where ORDER BY u.id DESC");
$total=mysqli_num_rows($q);
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<title>Users — Admin</title>
</head><body>
<!-- BG -->
<div class="dash-bg" aria-hidden="true"><div class="dash-bg-blob1"></div><div class="dash-bg-blob2"></div><div class="dash-bg-blob3"></div><div class="dash-bg-grid"></div></div>
<?php include '../includes/navbar_admin.php'; ?>
<div class="page-wrap">
  <div class="dash-header">
    <div class="eyebrow">Management</div>
    <h1>All Users</h1>
    <div class="sub"><?= $total ?> registered user<?= $total!=1?'s':'' ?></div>
  </div>

  <?php if(isset($_GET['deleted'])): ?><div class="alert alert-success mb-4">User deleted successfully.</div><?php endif; ?>

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <form method="GET" style="display:flex;gap:8px">
      <div class="search-wrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" name="search" class="input-field search-input" placeholder="Search by name or email..." value="<?= htmlspecialchars($search) ?>" style="width:260px">
      </div>
      <button type="submit" class="btn btn-dark btn-md"><i class="fa-solid fa-filter"></i></button>
      <?php if($search): ?><a href="users.php" class="btn btn-ghost btn-md">Clear</a><?php endif; ?>
    </form>
    <div style="color:var(--t2);font-size:.85rem">Total users: <strong style="color:var(--t1)"><?= $total ?></strong></div>
  </div>

  <div class="data-table-wrap mb-5" style="overflow-x:auto">
    <table class="data-table">
      <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Bookings</th><th>Total Spent</th><th>Joined</th><th>Action</th></tr></thead>
      <tbody>
        <?php if($total===0): ?>
        <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--t2)">No users found</td></tr>
        <?php endif; ?>
        <?php while($row=mysqli_fetch_assoc($q)): ?>
        <tr>
          <td>#<?= $row['id'] ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--orange),var(--cyan));display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;color:#02040b;flex-shrink:0">
                <?= strtoupper(substr($row['name'],0,1)) ?>
              </div>
              <span style="font-weight:600;color:var(--t1)"><?= htmlspecialchars($row['name']) ?></span>
            </div>
          </td>
          <td style="color:var(--t2)"><?= htmlspecialchars($row['email']) ?></td>
          <td style="color:var(--t2)"><?= htmlspecialchars($row['phone']??'—') ?></td>
          <td><span class="badge badge-approved"><?= $row['bcount'] ?></span></td>
          <td style="color:var(--orange);font-weight:700">₹<?= number_format($row['bspent']??0) ?></td>
          <td style="color:var(--t3);font-size:.8rem"><?= date('d M Y',strtotime($row['created_at']??'now')) ?></td>
          <td>
            <a href="?delete=<?= $row['id'] ?><?= $search?"&search=".urlencode($search):'' ?>"
               class="btn btn-danger btn-xs"
               onclick="return confirm('Delete user <?= htmlspecialchars(addslashes($row['name'])) ?>?\nThis will also delete their bookings and feedback.')">
              <i class="fa-solid fa-trash me-1"></i> Delete
            </a>
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
