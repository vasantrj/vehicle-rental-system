<?php
session_start();
$root='../';
include '../config/db.php';
if(!isset($_SESSION['role'])||$_SESSION['role']!=='admin'){ header("Location: ../auth/login.php"); exit(); }

$revenue  = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COALESCE(SUM(total_price),0) t FROM bookings"))['t'];
$bookings = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM bookings"))['c'];
$approved = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM bookings WHERE status='approved'"))['c'];
$pending  = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM bookings WHERE status='pending'"))['c'];
$rejected = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM bookings WHERE status='rejected'"))['c'];
$vehicles = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM vehicles"))['c'];
$users    = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM users WHERE role='user'"))['c'];
$feedback = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM feedback"))['c'];

// ── Visitor analytics ────────────────────────────────────────────────────────
$vis_total = 0; $vis_today = 0; $vis_week = 0; $vis_month = 0;
$vis_top_pages = []; $vis_daily_labels = []; $vis_daily_counts = [];
// Check if visitors table exists before querying
if(mysqli_query($conn,"SELECT 1 FROM visitors LIMIT 1")){
  $vis_total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM visitors"))['c'];
  $vis_today = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM visitors WHERE DATE(visited_at)=CURDATE()"))['c'];
  $vis_week  = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM visitors WHERE visited_at >= DATE_SUB(NOW(),INTERVAL 7 DAY)"))['c'];
  $vis_month = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM visitors WHERE visited_at >= DATE_SUB(NOW(),INTERVAL 30 DAY)"))['c'];
  $top_res   = mysqli_query($conn,"SELECT page, COUNT(*) c FROM visitors GROUP BY page ORDER BY c DESC LIMIT 5");
  while($r=mysqli_fetch_assoc($top_res)) $vis_top_pages[]=$r;
  for($i=6;$i>=0;$i--){
    $d   = date('Y-m-d', strtotime("-$i days"));
    $lbl = date('D', strtotime("-$i days"));
    $cnt = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM visitors WHERE DATE(visited_at)='$d'"))['c'];
    $vis_daily_labels[] = $lbl;
    $vis_daily_counts[] = $cnt;
  }
}
// ────────────────────────────────────────────────────────────────────────────

$recent   = mysqli_query($conn,"SELECT b.*,u.name uname,v.name vname FROM bookings b JOIN users u ON b.user_id=u.id JOIN vehicles v ON b.vehicle_id=v.id ORDER BY b.id DESC LIMIT 8");

// Visitor stats
$vis_total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM visitors"))['c'] ?? 0;
$vis_today = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM visitors WHERE DATE(visited_at)=CURDATE()"))['c'] ?? 0;
$vis_week  = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM visitors WHERE visited_at >= DATE_SUB(NOW(),INTERVAL 7 DAY)"))['c'] ?? 0;
$vis_month = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM visitors WHERE visited_at >= DATE_SUB(NOW(),INTERVAL 30 DAY)"))['c'] ?? 0;
$top_pages = mysqli_query($conn,"SELECT page, COUNT(*) c FROM visitors GROUP BY page ORDER BY c DESC LIMIT 5");
// Daily visitors last 7 days
$vis_days=[]; $vis_counts=[];
for($i=6;$i>=0;$i--){
  $vis_days[]   = date('D',strtotime("-$i days"));
  $cnt = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM visitors WHERE DATE(visited_at)=DATE_SUB(CURDATE(),INTERVAL $i DAY)"))['c'];
  $vis_counts[] = $cnt;
}

// Monthly revenue for chart - last 6 months
$months = []; $revenues = [];
for($i=5;$i>=0;$i--){
  $mn = date('Y-m', strtotime("-$i months"));
  $ml = date('M', strtotime("-$i months"));
  $rev = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COALESCE(SUM(total_price),0) t FROM bookings WHERE DATE_FORMAT(created_at,'%Y-%m')='$mn'"))['t'];
  $months[] = $ml; $revenues[] = $rev;
}
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<title>Admin Dashboard — DriveEase</title>
<style>
.admin-hero{background:linear-gradient(135deg,rgba(124,58,237,.1),rgba(0,245,212,.06));border:1px solid rgba(124,58,237,.25);border-radius:var(--r3);padding:32px;margin-bottom:36px;position:relative;overflow:hidden}
.admin-hero::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,#7c3aed,var(--cyan),#7c3aed);background-size:200% 100%;animation:borderFlow 3s linear infinite}
@keyframes borderFlow{0%{background-position:0%}100%{background-position:200%}}
.chart-card{background:var(--bg-glass);border:1px solid var(--b1);border-radius:var(--r3);padding:28px;backdrop-filter:blur(10px);height:100%}
</style>
</head><body>
<!-- DASHBOARD BACKGROUND -->
<div class="dash-bg" aria-hidden="true">
  <div class="dash-bg-blob1"></div>
  <div class="dash-bg-blob2"></div>
  <div class="dash-bg-blob3"></div>
  <div class="dash-bg-grid"></div>
</div>
<?php include '../includes/navbar_admin.php'; ?>
<div class="page-wrap">
  <!-- HEADER -->
  <div class="admin-hero">
    <div style="display:flex;align-items:center;gap:20px">
      <div style="width:56px;height:56px;border-radius:var(--r2);background:linear-gradient(135deg,#7c3aed,#a78bfa);display:flex;align-items:center;justify-content:center;font-size:1.5rem;box-shadow:0 8px 32px rgba(124,58,237,.35);flex-shrink:0">⚙️</div>
      <div>
        <div class="eyebrow" style="color:var(--purple-soft)">Admin Panel</div>
        <h1 style="margin:0">Dashboard Overview</h1>
        <div class="sub">Platform analytics &amp; quick controls</div>
      </div>
      <div style="margin-left:auto;text-align:right">
        <div style="font-size:.75rem;color:var(--t3)">Today</div>
        <div style="font-family:var(--ff-head);font-weight:700;color:var(--t1)"><?= date('d M Y') ?></div>
      </div>
    </div>
  </div>

  <!-- STATS ROW 1 -->
  <div class="row g-4 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
      <div class="stat-card stat-orange">
        <span class="stat-label">Revenue</span>
        <span class="stat-value" data-target="<?= $revenue ?>" data-prefix="₹">₹<?= number_format($revenue) ?></span>
        <span class="stat-icon-bg">💰</span>
      </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
      <div class="stat-card stat-blue">
        <span class="stat-label">Bookings</span>
        <span class="stat-value" data-target="<?= $bookings ?>"><?= $bookings ?></span>
        <span class="stat-icon-bg">📋</span>
      </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
      <div class="stat-card stat-green">
        <span class="stat-label">Approved</span>
        <span class="stat-value" data-target="<?= $approved ?>"><?= $approved ?></span>
        <span class="stat-icon-bg">✅</span>
      </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
      <div class="stat-card" style="border-top-color:transparent">
        <span class="stat-card-top" style="position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,#fde047,#fbbf24);border-radius:var(--r3) var(--r3) 0 0"></span>
        <span class="stat-label">Pending</span>
        <span class="stat-value" data-target="<?= $pending ?>"><?= $pending ?></span>
        <span class="stat-icon-bg">⏳</span>
      </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
      <div class="stat-card stat-purple">
        <span class="stat-label">Vehicles</span>
        <span class="stat-value" data-target="<?= $vehicles ?>"><?= $vehicles ?></span>
        <span class="stat-icon-bg">🚗</span>
      </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
      <div class="stat-card stat-blue">
        <span class="stat-label">Users</span>
        <span class="stat-value" data-target="<?= $users ?>"><?= $users ?></span>
        <span class="stat-icon-bg">👤</span>
      </div>
    </div>
  </div>

  <!-- CHARTS + QUICK ACTIONS -->
  <div class="row g-4 mb-5">
    <div class="col-lg-8">
      <div class="chart-card">
        <div class="panel-title"><div class="panel-title-dot"></div>Revenue — Last 6 Months</div>
        <canvas id="revenueChart" height="110"></canvas>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="chart-card d-flex flex-column gap-3">
        <div class="panel-title"><div class="panel-title-dot"></div>Quick Actions</div>
        <a href="add-vehicle.php" class="quick-action-card">
          <div class="quick-action-icon qa-orange">🚗</div>
          <div><h5>Add Vehicle</h5><p>Add to fleet</p></div>
          <i class="fa-solid fa-chevron-right quick-action-arrow"></i>
        </a>
        <a href="bookings.php" class="quick-action-card">
          <div class="quick-action-icon qa-cyan">📋</div>
          <div><h5>Bookings</h5><p><?= $pending ?> pending</p></div>
          <i class="fa-solid fa-chevron-right quick-action-arrow"></i>
        </a>
        <a href="manage-vehicles.php" class="quick-action-card">
          <div class="quick-action-icon qa-purple">🔧</div>
          <div><h5>Manage Fleet</h5><p><?= $vehicles ?> vehicles</p></div>
          <i class="fa-solid fa-chevron-right quick-action-arrow"></i>
        </a>
        <a href="users.php" class="quick-action-card">
          <div class="quick-action-icon qa-green">👥</div>
          <div><h5>Users</h5><p><?= $users ?> registered</p></div>
          <i class="fa-solid fa-chevron-right quick-action-arrow"></i>
        </a>
        <a href="feedbacks.php" class="quick-action-card">
          <div class="quick-action-icon" style="background:rgba(253,224,71,.1);border:1px solid rgba(253,224,71,.3)">⭐</div>
          <div><h5>Feedback</h5><p><?= $feedback ?> reviews</p></div>
          <i class="fa-solid fa-chevron-right quick-action-arrow"></i>
        </a>
      </div>
    </div>
  </div>

  <!-- STATUS PIE + RECENT -->
  <div class="row g-4 mb-5">
    <div class="col-md-4">
      <div class="chart-card">
        <div class="panel-title"><div class="panel-title-dot"></div>Booking Status</div>
        <canvas id="statusChart" height="200"></canvas>
        <div class="d-flex justify-content-center gap-3 mt-3" style="font-size:.75rem;color:var(--t2)">
          <span><span style="color:var(--orange)">●</span> Pending: <?= $pending ?></span>
          <span><span style="color:var(--green)">●</span> Approved: <?= $approved ?></span>
          <span><span style="color:var(--red)">●</span> Rejected: <?= $rejected ?></span>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <!-- RECENT BOOKINGS -->
      <div class="panel-title"><div class="panel-title-dot"></div>Recent Bookings</div>
      <div class="data-table-wrap" style="overflow-x:auto">
        <table class="data-table">
          <thead><tr><th>#</th><th>User</th><th>Vehicle</th><th>Dates</th><th>Amount</th><th>Status</th><th>Action</th></tr></thead>
          <tbody>
            <?php while($row=mysqli_fetch_assoc($recent)):
              $sc=$row['status']==='approved'?'badge-approved':($row['status']==='rejected'?'badge-rejected':'badge-pending');
            ?>
            <tr>
              <td>#<?= $row['id'] ?></td>
              <td style="font-weight:600;color:var(--t1)"><?= htmlspecialchars($row['uname']) ?></td>
              <td><?= htmlspecialchars($row['vname']) ?></td>
              <td style="font-size:.78rem;color:var(--t2)"><?= $row['start_date'] ?> → <?= $row['end_date'] ?></td>
              <td style="color:var(--orange);font-weight:700">₹<?= number_format($row['total_price']) ?></td>
              <td><span class="badge <?= $sc ?>"><?= ucfirst($row['status']) ?></span></td>
              <td>
                <?php if($row['status']==='pending'): ?>
                  <a href="bookings.php?approve=<?= $row['id'] ?>" class="btn btn-green btn-xs me-1" onclick="return confirm('Approve this booking?')">✓ Approve</a>
                  <a href="bookings.php?reject=<?= $row['id'] ?>"  class="btn btn-red btn-xs" onclick="return confirm('Reject this booking?')">✗ Reject</a>
                <?php else: ?><span style="color:var(--t3)">—</span><?php endif; ?>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <a href="bookings.php" class="btn btn-ghost btn-md mt-3">View All Bookings <i class="fa-solid fa-arrow-right ms-2"></i></a>
    </div>
  </div>

  <!-- ── VISITOR ANALYTICS ─────────────────────────────────────── -->
  <div class="row g-4 mb-5">
    <div class="col-12">
      <div class="chart-card">
        <div class="panel-title mb-4"><div class="panel-title-dot"></div>👁️ Website Visitors <span style="font-size:.72rem;color:var(--t3);font-weight:400;margin-left:8px">Bot-filtered · Admin visits excluded</span></div>
        <div class="row g-3 mb-4">
          <div class="col-6 col-md-3">
            <div class="stat-card stat-blue" style="padding:16px 18px">
              <span class="stat-label">All-Time</span>
              <span class="stat-value" style="font-size:1.6rem" data-target="<?= $vis_total ?>"><?= number_format($vis_total) ?></span>
              <span class="stat-icon-bg">👁️</span>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-card stat-green" style="padding:16px 18px">
              <span class="stat-label">Today</span>
              <span class="stat-value" style="font-size:1.6rem" data-target="<?= $vis_today ?>"><?= $vis_today ?></span>
              <span class="stat-icon-bg">📅</span>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-card stat-purple" style="padding:16px 18px">
              <span class="stat-label">This Week</span>
              <span class="stat-value" style="font-size:1.6rem" data-target="<?= $vis_week ?>"><?= $vis_week ?></span>
              <span class="stat-icon-bg">📊</span>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-card stat-orange" style="padding:16px 18px">
              <span class="stat-label">This Month</span>
              <span class="stat-value" style="font-size:1.6rem" data-target="<?= $vis_month ?>"><?= $vis_month ?></span>
              <span class="stat-icon-bg">🗓️</span>
            </div>
          </div>
        </div>
        <div class="row g-4">
          <div class="col-lg-8">
            <div style="font-size:.78rem;color:var(--t3);text-transform:uppercase;letter-spacing:.06em;font-weight:700;margin-bottom:12px">Daily Visitors — Last 7 Days</div>
            <canvas id="visitorChart" height="90"></canvas>
          </div>
          <div class="col-lg-4">
            <div style="font-size:.78rem;color:var(--t3);text-transform:uppercase;letter-spacing:.06em;font-weight:700;margin-bottom:12px">Top Pages</div>
            <?php if(empty($vis_top_pages)): ?>
              <p style="color:var(--t3);font-size:.85rem">No data yet. Visitors tracked on public pages.</p>
            <?php else: foreach($vis_top_pages as $pg): ?>
              <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--b1)">
                <span style="color:var(--t2);font-size:.85rem">📄 <?= htmlspecialchars($pg['page']) ?></span>
                <span style="color:var(--cyan);font-weight:700;font-size:.85rem"><?= $pg['c'] ?></span>
              </div>
            <?php endforeach; endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
<?php include '../includes/admin_footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
<script>
const chartDefaults = {
  plugins:{legend:{display:false}},
  scales:{
    x:{grid:{color:'rgba(255,255,255,.04)'},ticks:{color:'#8896ad',font:{size:11}}},
    y:{grid:{color:'rgba(255,255,255,.04)'},ticks:{color:'#8896ad',stepSize:1}}
  }
};
new Chart(document.getElementById('revenueChart'),{
  type:'line',
  data:{
    labels:[<?= "'".implode("','",$months)."'" ?>],
    datasets:[{
      label:'Revenue ₹',
      data:[<?= implode(',',$revenues) ?>],
      borderColor:'#f97316',
      backgroundColor:'rgba(249,115,22,.08)',
      borderWidth:2.5,fill:true,tension:.4,
      pointBackgroundColor:'#f97316',pointRadius:4,pointHoverRadius:7
    },{
      label:'',
      data:[<?= implode(',',$revenues) ?>],
      borderColor:'rgba(0,245,212,.4)',
      backgroundColor:'transparent',
      borderWidth:1,fill:false,tension:.4,
      pointRadius:0,borderDash:[4,4]
    }]
  },
  options:{...chartDefaults,plugins:{legend:{display:false}}}
});
new Chart(document.getElementById('statusChart'),{
  type:'doughnut',
  data:{
    labels:['Pending','Approved','Rejected'],
    datasets:[{
      data:[<?= $pending ?>,<?= $approved ?>,<?= $rejected ?>],
      backgroundColor:['rgba(253,224,71,.3)','rgba(16,185,129,.3)','rgba(239,68,68,.3)'],
      borderColor:['#fde047','#10b981','#ef4444'],
      borderWidth:2,hoverOffset:8
    }]
  },
  options:{
    cutout:'65%',
    plugins:{legend:{display:false}},
    responsive:true
  }
});
new Chart(document.getElementById('visitorChart'),{
  type:'bar',
  data:{
    labels:[<?= "'" . implode("','", $vis_daily_labels) . "'" ?>],
    datasets:[{
      label:'Visitors',
      data:[<?= implode(',', $vis_daily_counts) ?>],
      backgroundColor:'rgba(0,245,212,.15)',
      borderColor:'rgba(0,245,212,.7)',
      borderWidth:2,
      borderRadius:4,
      hoverBackgroundColor:'rgba(0,245,212,.3)'
    }]
  },
  options:{
    plugins:{legend:{display:false}},
    scales:{
      x:{grid:{color:'rgba(255,255,255,.04)'},ticks:{color:'#8896ad',font:{size:11}}},
      y:{grid:{color:'rgba(255,255,255,.04)'},ticks:{color:'#8896ad',stepSize:1,precision:0}}
    }
  }
});
</script>
</body></html>
