<?php if(session_status()===PHP_SESSION_NONE) session_start(); ?>
<nav class="site-nav">
  <div class="nav-inner">
    <a href="<?= $root ?>index.php" class="nav-brand">
      <div class="nav-brand-icon">🚗</div>
      <span class="nav-brand-text">DriveEase</span>
    </a>
    <div class="nav-links" id="navLinks">
      <a href="<?= $root ?>index.php" class="nav-link-item active">Home</a>
      <a href="<?= $root ?>pages/about.php" class="nav-link-item">About</a>
      <a href="<?= $root ?>pages/contact.php" class="nav-link-item">Contact</a>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="<?= $root ?>user/dashboard.php" class="nav-btn-ghost">Dashboard</a>
        <a href="<?= $root ?>auth/logout.php" class="nav-logout">Logout</a>
      <?php else: ?>
        <a href="<?= $root ?>auth/login.php" class="nav-btn-ghost">Sign In</a>
        <a href="<?= $root ?>auth/register.php" class="nav-btn">Get Started <i class="fa-solid fa-arrow-right ms-1"></i></a>
      <?php endif; ?>
    </div>
    <button class="nav-toggle" id="navToggle"><i class="fa-solid fa-bars"></i></button>
  </div>
</nav>
