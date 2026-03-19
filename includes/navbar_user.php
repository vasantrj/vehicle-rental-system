<?php if(session_status()===PHP_SESSION_NONE) session_start(); ?>
<nav class="site-nav">
  <div class="nav-inner">
    <a href="<?= $root ?>index.php" class="nav-brand">
      <div class="nav-brand-icon">🚗</div>
      <span class="nav-brand-text">DriveEase</span>
    </a>
    <div class="nav-links" id="navLinks">
      <a href="<?= $root ?>user/dashboard.php" class="nav-link-item">Dashboard</a>
      <a href="<?= $root ?>user/vehicles.php" class="nav-link-item">Vehicles</a>
      <a href="<?= $root ?>user/my-bookings.php" class="nav-link-item">My Bookings</a>
      <a href="<?= $root ?>user/feedback.php" class="nav-link-item">Feedback</a>
      <a href="<?= $root ?>user/profile.php" class="nav-link-item">Profile</a>
      <a href="<?= $root ?>auth/logout.php" class="nav-logout"><i class="fa-solid fa-right-from-bracket me-1"></i>Logout</a>
    </div>
    <button class="nav-toggle" id="navToggle"><i class="fa-solid fa-bars"></i></button>
  </div>
</nav>
