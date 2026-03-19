<?php
session_start();
$root='../';
include '../config/db.php';
if(!isset($_SESSION['role'])||$_SESSION['role']!=='admin'){ header("Location: ../auth/login.php"); exit(); }

if(isset($_GET['delete'])){
  $id=intval($_GET['delete']);
  $v=mysqli_fetch_assoc(mysqli_query($conn,"SELECT image FROM vehicles WHERE id=$id"));
  $defaults=['swift.jpg','creta.jpg','re.jpg','hero-car.jpg'];
  if($v['image'] && !in_array($v['image'],$defaults)) @unlink('../assets/images/'.$v['image']);
  mysqli_query($conn,"DELETE FROM vehicles WHERE id=$id");
  header("Location: manage-vehicles.php?deleted=1"); exit();
}
$search = $_GET['search'] ?? '';
$where = "1=1";
if($search) $where .= " AND (name LIKE '%".mysqli_real_escape_string($conn,$search)."%' OR brand LIKE '%".mysqli_real_escape_string($conn,$search)."%')";
$result=mysqli_query($conn,"SELECT * FROM vehicles WHERE $where ORDER BY id DESC");
$total=mysqli_num_rows($result);
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<title>Manage Vehicles — Admin</title>
</head><body>
<!-- BG -->
<div class="dash-bg" aria-hidden="true"><div class="dash-bg-blob1"></div><div class="dash-bg-blob2"></div><div class="dash-bg-blob3"></div><div class="dash-bg-grid"></div></div>
<?php include '../includes/navbar_admin.php'; ?>
<div class="page-wrap">
  <div class="dash-header">
    <div class="eyebrow">Fleet Management</div>
    <h1>Manage Vehicles</h1>
    <div class="sub"><?= $total ?> vehicle<?= $total!=1?'s':'' ?> in fleet</div>
  </div>

  <?php if(isset($_GET['deleted'])): ?><div class="alert alert-success mb-4">🗑️ Vehicle deleted successfully.</div><?php endif; ?>
  <?php if(isset($_GET['updated'])): ?><div class="alert alert-success mb-4">✅ Vehicle updated successfully.</div><?php endif; ?>
  <?php if(isset($_GET['added'])): ?><div class="alert alert-success mb-4">✅ Vehicle added successfully.</div><?php endif; ?>

  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <form method="GET" style="display:flex;gap:8px">
      <div class="search-wrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" name="search" class="input-field search-input" placeholder="Search vehicles..." value="<?= htmlspecialchars($search) ?>" style="width:220px">
      </div>
      <button type="submit" class="btn btn-dark btn-md"><i class="fa-solid fa-filter"></i></button>
      <?php if($search): ?><a href="manage-vehicles.php" class="btn btn-ghost btn-md">Clear</a><?php endif; ?>
    </form>
    <a href="add-vehicle.php" class="btn btn-primary btn-md"><i class="fa-solid fa-plus me-2"></i>Add Vehicle</a>
  </div>

  <div class="row g-4 mb-5">
    <?php if($total===0): ?>
    <div class="col-12"><div class="empty-state"><div class="empty-icon">🚗</div><h4>No vehicles found</h4><p>Try a different search or add a new vehicle</p></div></div>
    <?php endif; ?>
    <?php while($row=mysqli_fetch_assoc($result)): ?>
    <div class="col-md-4 col-sm-6">
      <div class="v-card">
        <div class="v-card-img">
          <span class="v-card-badge"><?= ucfirst($row['status']??'Available') ?></span>
          <img src="../assets/images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
        </div>
        <div class="v-card-body">
          <div class="v-card-brand"><?= htmlspecialchars($row['brand']) ?></div>
          <div class="v-card-name"><?= htmlspecialchars($row['name']) ?></div>
          <div class="v-card-price"><span class="amount">₹<?= number_format($row['price_per_day']) ?></span><span class="per">/ day</span></div>
          <div style="color:var(--t3);font-size:.75rem;margin-bottom:14px">ID: #<?= $row['id'] ?></div>
          <div class="d-flex gap-2">
            <a href="edit-vehicle.php?id=<?= $row['id'] ?>" class="btn btn-blue btn-sm" style="flex:1">
              <i class="fa-solid fa-pen me-1"></i> Edit
            </a>
            <a href="?delete=<?= $row['id'] ?><?= $search?"&search=".urlencode($search):'' ?>"
               class="btn btn-red btn-sm" style="flex:1"
               onclick="return confirm('Delete <?= htmlspecialchars(addslashes($row['name'])) ?>? This cannot be undone.')">
              <i class="fa-solid fa-trash me-1"></i> Delete
            </a>
          </div>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>
<?php include '../includes/admin_footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
</body></html>
