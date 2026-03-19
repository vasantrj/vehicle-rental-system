<?php
session_start();
$root = '../';
include '../config/db.php';
if(!isset($_SESSION['user_id'])){ header("Location: ../auth/login.php"); exit(); }

$uid      = $_SESSION['user_id'];
$user     = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE id=$uid"));
$bookings = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM bookings WHERE user_id=$uid"))['c'];
$spent    = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COALESCE(SUM(total_price),0) t FROM bookings WHERE user_id=$uid"))['t'];
$pending  = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM bookings WHERE user_id=$uid AND status='pending'"))['c'];
$approved = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM bookings WHERE user_id=$uid AND status='approved'"))['c'];
$vehicles = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM vehicles WHERE status='available'"))['c'];
$hour     = (int)date('G');
$greet    = $hour<12?'Good Morning ☀️':($hour<17?'Good Afternoon 🌤️':'Good Evening 🌙');
$initials = strtoupper(substr($user['name'],0,1));
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<title>Dashboard — DriveEase</title>
</head><body>
<!-- DASHBOARD BACKGROUND -->
<div class="dash-bg" aria-hidden="true">
  <div class="dash-bg-blob1"></div>
  <div class="dash-bg-blob2"></div>
  <div class="dash-bg-blob3"></div>
  <div class="dash-bg-grid"></div>
</div>
<?php include '../includes/navbar_user.php'; ?>
<div class="page-wrap">
  <!-- GREETING -->
  <div class="dash-header">
    <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
      <div class="profile-avatar"><?= $initials ?></div>
      <div>
        <div class="eyebrow"><?= $greet ?></div>
        <h1><?= htmlspecialchars($user['name']) ?></h1>
        <div class="sub">Here's your rental overview</div>
      </div>
      <a href="vehicles.php" class="btn btn-primary btn-md" style="margin-left:auto">
        <i class="fa-solid fa-car me-2"></i>Browse All Vehicles
      </a>
    </div>
  </div>

  <!-- STATS -->
  <div class="row g-4 mb-5">
    <div class="col-6 col-md-3"><div class="stat-card stat-orange">
      <span class="stat-label">My Bookings</span>
      <span class="stat-value" data-target="<?= $bookings ?>"><?= $bookings ?></span>
      <span class="stat-icon-bg">📋</span>
    </div></div>
    <div class="col-6 col-md-3"><div class="stat-card stat-green">
      <span class="stat-label">Total Spent</span>
      <span class="stat-value" data-target="<?= $spent ?>" data-prefix="₹">₹<?= number_format($spent) ?></span>
      <span class="stat-icon-bg">💰</span>
    </div></div>
    <div class="col-6 col-md-3"><div class="stat-card stat-blue">
      <span class="stat-label">Pending</span>
      <span class="stat-value" data-target="<?= $pending ?>"><?= $pending ?></span>
      <span class="stat-icon-bg">⏳</span>
    </div></div>
    <div class="col-6 col-md-3"><div class="stat-card stat-purple">
      <span class="stat-label">Available Vehicles</span>
      <span class="stat-value" data-target="<?= $vehicles ?>"><?= $vehicles ?></span>
      <span class="stat-icon-bg">🚗</span>
    </div></div>
  </div>

  <!-- QUICK ACTIONS -->
  <div class="section-divider"></div>
  <div class="panel-title"><div class="panel-title-dot"></div>Quick Actions</div>
  <div class="d-flex gap-3 flex-wrap mb-5">
    <a href="vehicles.php" class="btn btn-primary btn-md"><i class="fa-solid fa-car me-2"></i>Browse Vehicles</a>
    <a href="my-bookings.php" class="btn btn-blue btn-md"><i class="fa-solid fa-list me-2"></i>My Bookings</a>
    <a href="feedback.php" class="btn btn-dark btn-md"><i class="fa-solid fa-star me-2"></i>Give Feedback</a>
    <a href="profile.php" class="btn btn-dark btn-md"><i class="fa-solid fa-user me-2"></i>Profile</a>
  </div>

  <!-- RECENT BOOKINGS -->
  <div class="panel-title"><div class="panel-title-dot"></div>Recent Bookings</div>
  <?php
  $rq = mysqli_query($conn,"SELECT b.*,v.name vname,v.image vimg,v.brand vbrand FROM bookings b JOIN vehicles v ON b.vehicle_id=v.id WHERE b.user_id=$uid ORDER BY b.id DESC LIMIT 3");
  if(mysqli_num_rows($rq)===0):
  ?>
  <div class="empty-state mb-5">
    <div class="empty-icon">🚗</div>
    <h4>No bookings yet</h4>
    <p>Start your journey by browsing our fleet below</p>
  </div>
  <?php else: ?>
  <div class="row g-4 mb-5">
    <?php while($row=mysqli_fetch_assoc($rq)):
      $sc=$row['status']==='approved'?'badge-approved':($row['status']==='rejected'?'badge-rejected':'badge-pending');
      $start=new DateTime($row['start_date']); $end=new DateTime($row['end_date']);
      $days=$end->diff($start)->days+1;
    ?>
    <div class="col-md-4">
      <div class="booking-card">
        <div class="booking-card-title"><i class="fa-solid fa-car" style="color:var(--orange)"></i><?= htmlspecialchars($row['vname']) ?></div>
        <div class="booking-meta">
          <strong>From:</strong> <?= $row['start_date'] ?><br>
          <strong>To:</strong> <?= $row['end_date'] ?><br>
          <strong>Duration:</strong> <span style="color:var(--cyan)"><?= $days ?> day<?= $days!=1?'s':'' ?></span><br>
          <strong>Amount:</strong> <span style="color:var(--orange)">₹<?= number_format($row['total_price']) ?></span>
        </div>
        <div class="booking-footer">
          <span class="badge <?= $sc ?>"><?= ucfirst($row['status']) ?></span>
          <?php if($row['payment_status']==='paid'): ?>
            <a href="invoice.php?id=<?= $row['id'] ?>" class="btn btn-dark btn-xs"><i class="fa-solid fa-file-invoice me-1"></i>Invoice</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
  <a href="my-bookings.php" class="btn btn-ghost btn-md mb-5">View All Bookings <i class="fa-solid fa-arrow-right ms-2"></i></a>
  <?php endif; ?>

  <!-- VEHICLE CARDS — with inline booking form -->
  <div class="section-divider"></div>
  <div class="panel-title"><div class="panel-title-dot"></div>Available Now — Pick Dates &amp; Book</div>
  <div class="row g-4 mb-5">
    <?php
    $vq=mysqli_query($conn,"SELECT * FROM vehicles WHERE status='available' LIMIT 6");
    while($v=mysqli_fetch_assoc($vq)):
    ?>
    <div class="col-md-4 col-sm-6 v-card-wrap" data-cat="<?= strtolower($v['brand']) ?>">
      <div class="v-card">
        <div class="v-card-img">
          <span class="v-card-badge">Available</span>
          <img src="../assets/images/<?= htmlspecialchars($v['image']) ?>" alt="<?= htmlspecialchars($v['name']) ?>">
        </div>
        <div class="v-card-body">
          <div class="v-card-brand"><?= htmlspecialchars($v['brand']) ?></div>
          <div class="v-card-name"><?= htmlspecialchars($v['name']) ?></div>
          <div class="v-card-price">
            <span class="amount">₹<?= number_format($v['price_per_day']) ?></span>
            <span class="per">/ day</span>
          </div>
          <div class="v-card-features">
            <span class="v-feat">✓ Insured</span><span class="v-feat">✓ Verified</span>
          </div>
          <!-- INLINE BOOKING FORM -->
          <div class="book-form-card">
            <form action="payment.php" method="post" class="booking-form" data-price="<?= $v['price_per_day'] ?>">
              <input type="hidden" name="vehicle_id" value="<?= $v['id'] ?>">
              <input type="hidden" name="price_per_day" value="<?= $v['price_per_day'] ?>">
              <div class="row g-2 mb-2">
                <div class="col-6">
                  <div style="font-size:.68rem;font-weight:700;color:var(--t2);text-transform:uppercase;letter-spacing:.06em;margin-bottom:5px">Start</div>
                  <div class="fp-dark"><input type="text" name="start_date" class="start-date" placeholder="From" required readonly></div>
                </div>
                <div class="col-6">
                  <div style="font-size:.68rem;font-weight:700;color:var(--t2);text-transform:uppercase;letter-spacing:.06em;margin-bottom:5px">End</div>
                  <div class="fp-dark"><input type="text" name="end_date" class="end-date" placeholder="To" required readonly></div>
                </div>
              </div>
              <div class="price-calc">
                <div class="price-row"><span>Rate</span><span class="amt">₹<?= number_format($v['price_per_day']) ?>/day</span></div>
                <div class="price-row"><span>Duration</span><span class="amt calc-days">—</span></div>
                <div class="price-row total"><span>Total</span><span class="amt calc-total">—</span></div>
              </div>
              <button type="submit" class="btn btn-primary btn-sm" style="width:100%;margin-top:10px">
                <i class="fa-solid fa-lock me-1"></i> Pay &amp; Book Now
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
  <a href="vehicles.php" class="btn btn-ghost btn-md mb-5">See All Vehicles <i class="fa-solid fa-arrow-right ms-2"></i></a>
</div>
<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
<script>
document.querySelectorAll('.booking-form').forEach(form=>{
  const vid = form.querySelector('[name=vehicle_id]').value;
  fetch('get-booked-dates.php?vehicle_id='+vid)
    .then(r=>r.json())
    .then(disabled=>{
      const opts={minDate:'today',disable:disabled,dateFormat:'Y-m-d'};
      flatpickr(form.querySelector('.start-date'),opts);
      flatpickr(form.querySelector('.end-date'),opts);
    })
    .catch(()=>{
      const opts={minDate:'today',dateFormat:'Y-m-d'};
      flatpickr(form.querySelector('.start-date'),opts);
      flatpickr(form.querySelector('.end-date'),opts);
    });
});
</script>
</body></html>
