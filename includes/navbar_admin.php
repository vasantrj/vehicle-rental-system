<?php if(session_status()===PHP_SESSION_NONE) session_start(); ?>
<nav class="site-nav">
  <div class="nav-inner">
    <a href="<?= $root ?>admin/dashboard.php" class="nav-brand">
      <div class="nav-brand-icon" style="background:linear-gradient(135deg,#7c3aed,#a78bfa);box-shadow:0 4px 16px rgba(124,58,237,.4)">⚙️</div>
      <span class="nav-brand-text">Admin Panel</span>
    </a>
    <div class="nav-links" id="navLinks">
      <a href="<?= $root ?>admin/dashboard.php" class="nav-link-item">Dashboard</a>
      <a href="<?= $root ?>admin/add-vehicle.php" class="nav-link-item">Add Vehicle</a>
      <a href="<?= $root ?>admin/manage-vehicles.php" class="nav-link-item">Vehicles</a>
      <a href="<?= $root ?>admin/bookings.php" class="nav-link-item">Bookings</a>
      <a href="<?= $root ?>admin/users.php" class="nav-link-item">Users</a>
      <a href="<?= $root ?>admin/feedbacks.php" class="nav-link-item">Feedback</a>
      <a href="<?= $root ?>auth/logout.php" class="nav-logout"><i class="fa-solid fa-right-from-bracket me-1"></i>Logout</a>
    </div>
    <button class="nav-toggle" id="navToggle"><i class="fa-solid fa-bars"></i></button>
  </div>
</nav>
