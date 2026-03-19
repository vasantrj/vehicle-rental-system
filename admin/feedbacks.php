<?php
session_start();
$root='../';
include '../config/db.php';
if(!isset($_SESSION['role'])||$_SESSION['role']!=='admin'){ header("Location: ../auth/login.php"); exit(); }

if(isset($_GET['delete'])){
  $id=intval($_GET['delete']);
  mysqli_query($conn,"DELETE FROM feedback WHERE id=$id");
  header("Location: feedbacks.php?deleted=1"); exit();
}

$q=mysqli_query($conn,"SELECT * FROM feedback ORDER BY id DESC");
$total=mysqli_num_rows($q);
$avgRating = mysqli_fetch_assoc(mysqli_query($conn,"SELECT AVG(rating) avg FROM feedback"))['avg'] ?? 0;
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<title>Feedbacks — Admin</title>
</head><body>
<!-- BG -->
<div class="dash-bg" aria-hidden="true"><div class="dash-bg-blob1"></div><div class="dash-bg-blob2"></div><div class="dash-bg-blob3"></div><div class="dash-bg-grid"></div></div>
<?php include '../includes/navbar_admin.php'; ?>
<div class="page-wrap">
  <div class="dash-header">
    <div class="eyebrow">Reviews</div>
    <h1>Customer Feedback</h1>
    <div class="sub"><?= $total ?> review<?= $total!=1?'s':'' ?> · Avg Rating: <span style="color:var(--orange)"><?= $total>0?number_format($avgRating,1).' ★':'—' ?></span></div>
  </div>

  <?php if(isset($_GET['deleted'])): ?><div class="alert alert-success mb-4">🗑️ Feedback deleted.</div><?php endif; ?>

  <div class="row g-4 mb-5">
    <?php if($total===0): ?>
    <div class="col-12"><div class="empty-state"><div class="empty-icon">💬</div><h4>No feedback yet</h4><p>Customer reviews will appear here</p></div></div>
    <?php endif; ?>
    <?php while($row=mysqli_fetch_assoc($q)): ?>
    <div class="col-md-6">
      <div class="booking-card" style="position:relative">
        <div class="booking-card-title">
          <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--orange),var(--cyan));display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;color:#02040b;flex-shrink:0">
            <?= strtoupper(substr($row['name'],0,1)) ?>
          </div>
          <div>
            <div style="font-weight:700;color:var(--t1)"><?= htmlspecialchars($row['name']) ?></div>
            <div style="font-size:.75rem;color:var(--t3)"><?= htmlspecialchars($row['email']) ?></div>
          </div>
          <!-- Delete btn top right -->
          <a href="?delete=<?= $row['id'] ?>"
             class="btn btn-danger btn-xs"
             style="margin-left:auto"
             onclick="return confirm('Delete this feedback from <?= htmlspecialchars(addslashes($row['name'])) ?>?')">
            <i class="fa-solid fa-trash"></i>
          </a>
        </div>
        <div style="margin-bottom:10px">
          <?php for($i=1;$i<=5;$i++): ?>
            <span style="color:<?= $i<=($row['rating']??0)?'var(--orange)':'var(--t3)' ?>;font-size:1.1rem">★</span>
          <?php endfor; ?>
          <span style="color:var(--t2);font-size:.78rem;margin-left:6px"><?= $row['rating']??0 ?>/5</span>
        </div>
        <p style="color:var(--t2);font-size:.875rem;line-height:1.7;margin-bottom:12px"><?= nl2br(htmlspecialchars($row['message'])) ?></p>
        <div style="color:var(--t3);font-size:.75rem;border-top:1px solid var(--b1);padding-top:10px">
          <i class="fa-regular fa-clock me-1"></i><?= date('d M Y',strtotime($row['created_at']??'now')) ?>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>
<?php include '../includes/admin_footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
</body></html>
