<?php
session_start();
$root = '../';
include '../config/db.php';
if(!isset($_SESSION['user_id'])){ header("Location: ../auth/login.php"); exit(); }

$brand  = $_GET['brand'] ?? '';
$sort   = $_GET['sort']  ?? '';
$search = $_GET['search']?? '';

$where = "1=1";
if($brand)  $where .= " AND brand='".mysqli_real_escape_string($conn,$brand)."'";
if($search) $where .= " AND (name LIKE '%".mysqli_real_escape_string($conn,$search)."%' OR brand LIKE '%".mysqli_real_escape_string($conn,$search)."%')";
$order = $sort==='price_asc' ? "price_per_day ASC" : ($sort==='price_desc'?"price_per_day DESC":"id ASC");

$result = mysqli_query($conn,"SELECT * FROM vehicles WHERE $where ORDER BY $order");
$brands = mysqli_query($conn,"SELECT DISTINCT brand FROM vehicles ORDER BY brand");
$total  = mysqli_num_rows($result);
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<title>Browse Vehicles — DriveEase</title>
<style>
.fp-dark .flatpickr-input{
  background:rgba(2,4,11,.7)!important;border:1px solid rgba(255,255,255,.12)!important;
  border-radius:6px;color:#edf2ff!important;padding:10px 14px;width:100%;
  font-size:.875rem;outline:none;font-family:'DM Sans',sans-serif
}
.fp-dark .flatpickr-input:focus{border-color:#f97316!important}
.fp-dark .flatpickr-input::placeholder{color:#3d4f68!important}
</style>
</head><body>
<!-- BG -->
<div class="dash-bg" aria-hidden="true">
  <div class="dash-bg-blob1"></div><div class="dash-bg-blob2"></div>
  <div class="dash-bg-blob3"></div><div class="dash-bg-grid"></div>
</div>
<?php include '../includes/navbar_user.php'; ?>
<div class="page-wrap">
  <div class="dash-header">
    <div class="eyebrow">Fleet</div>
    <h1>Browse Vehicles</h1>
    <div class="sub"><?= $total ?> vehicle<?= $total!=1?'s':'' ?> available for your next trip</div>
  </div>

  <!-- FILTER BAR -->
  <form method="GET" class="filter-bar">
    <div class="filter-group" style="flex:2">
      <div class="filter-label">Search</div>
      <div class="search-wrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" name="search" class="input-field search-input"
          placeholder="Search by name or brand..."
          value="<?= htmlspecialchars($search) ?>">
      </div>
    </div>
    <div class="filter-group">
      <div class="filter-label">Brand</div>
      <select name="brand" class="filter-select">
        <option value="">All Brands</option>
        <?php while($b=mysqli_fetch_assoc($brands)): ?>
          <option value="<?= $b['brand'] ?>" <?= $brand===$b['brand']?'selected':'' ?>><?= htmlspecialchars($b['brand']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="filter-group">
      <div class="filter-label">Sort By</div>
      <select name="sort" class="filter-select">
        <option value="">Default</option>
        <option value="price_asc"  <?= $sort==='price_asc'?'selected':'' ?>>Price: Low → High</option>
        <option value="price_desc" <?= $sort==='price_desc'?'selected':'' ?>>Price: High → Low</option>
      </select>
    </div>
    <div class="filter-group" style="flex:0;justify-content:flex-end">
      <div class="filter-label">&nbsp;</div>
      <div style="display:flex;gap:8px">
        <button type="submit" class="btn btn-primary btn-md"><i class="fa-solid fa-filter"></i></button>
        <?php if($search||$brand||$sort): ?>
          <a href="vehicles.php" class="btn btn-ghost btn-md"><i class="fa-solid fa-rotate-left"></i></a>
        <?php endif; ?>
      </div>
    </div>
  </form>

  <?php if($total===0): ?>
  <div class="empty-state">
    <div class="empty-icon">🚗</div>
    <h4>No vehicles found</h4>
    <p>Try a different search or filter</p>
    <a href="vehicles.php" class="btn btn-primary btn-md">Clear Filters</a>
  </div>
  <?php else: ?>
  <div class="row g-4 mb-5">
    <?php while($row=mysqli_fetch_assoc($result)): ?>
    <div class="col-lg-4 col-md-6 v-card-wrap" data-cat="<?= strtolower($row['brand']) ?>">
      <div class="v-card">
        <div class="v-card-img">
          <span class="v-card-badge"><?= ucfirst($row['status']??'Available') ?></span>
          <img src="../assets/images/<?= htmlspecialchars($row['image']) ?>"
               alt="<?= htmlspecialchars($row['name']) ?>"
               loading="lazy">
        </div>
        <div class="v-card-body">
          <div class="v-card-brand"><?= htmlspecialchars($row['brand']) ?></div>
          <div class="v-card-name"><?= htmlspecialchars($row['name']) ?></div>
          <div class="v-card-price">
            <span class="amount">₹<?= number_format($row['price_per_day']) ?></span>
            <span class="per">/ day</span>
          </div>
          <div class="v-card-features">
            <span class="v-feat">✓ Insured</span>
            <span class="v-feat">✓ Verified</span>
            <span class="v-feat">✓ 24/7 Support</span>
          </div>
          <!-- BOOKING FORM -->
          <div class="book-form-card">
            <form action="payment.php" method="post" class="booking-form" data-price="<?= $row['price_per_day'] ?>">
              <input type="hidden" name="vehicle_id" value="<?= $row['id'] ?>">
              <input type="hidden" name="price_per_day" value="<?= $row['price_per_day'] ?>">
              <div class="row g-2 mb-2">
                <div class="col-6">
                  <div class="filter-label" style="font-size:.65rem;margin-bottom:4px">Start Date</div>
                  <div class="fp-dark"><input type="text" name="start_date" class="start-date" placeholder="From" required readonly></div>
                </div>
                <div class="col-6">
                  <div class="filter-label" style="font-size:.65rem;margin-bottom:4px">End Date</div>
                  <div class="fp-dark"><input type="text" name="end_date" class="end-date" placeholder="To" required readonly></div>
                </div>
              </div>
              <!-- Price Calculator -->
              <div class="price-calc">
                <div class="price-row"><span>Rate</span><span class="amt">₹<?= number_format($row['price_per_day']) ?>/day</span></div>
                <div class="price-row"><span>Duration</span><span class="amt calc-days">—</span></div>
                <div class="price-row total"><span>Total</span><span class="amt calc-total">—</span></div>
              </div>
              <button type="submit" class="btn btn-primary btn-sm" style="width:100%;margin-top:10px">
                <i class="fa-solid fa-lock me-1"></i> Pay &amp; Book
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
<script>
document.querySelectorAll('.booking-form').forEach(form=>{
  const vid = form.querySelector('[name=vehicle_id]').value;

  function initPickers(disabled){
    const baseOpts = {minDate:'today', dateFormat:'Y-m-d'};
    if(disabled && disabled.length) baseOpts.disable = disabled;

    // Init end picker first so start picker onChange can reference it
    const endPicker = flatpickr(form.querySelector('.end-date'),{
      ...baseOpts,
      minDate: null
    });

    // Start picker: when date chosen, set end picker minDate to same day (allows 1-day booking)
    flatpickr(form.querySelector('.start-date'),{
      ...baseOpts,
      onChange: function(selectedDates){
        if(!selectedDates.length) return;
        const startDate = selectedDates[0];
        // End date can be same day (1-day booking) or later
        endPicker.set('minDate', startDate);
        // Clear end date only if it is strictly before the new start
        if(endPicker.selectedDates.length && endPicker.selectedDates[0] < startDate){
          endPicker.clear();
          const calc = form.querySelector('.price-calc');
          if(calc) calc.classList.remove('show');
        }
      }
    });
  }

  fetch('get-booked-dates.php?vehicle_id='+vid)
    .then(r=>r.json())
    .then(disabled=> initPickers(disabled))
    .catch(()=> initPickers([]));
});
</script>
</body></html>
