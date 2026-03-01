<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container">
    <a class="navbar-brand" href="#">🚗 Vehicle Rental</a>

    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">

        <?php if(isset($_SESSION['role']) && $_SESSION['role']=="user") { ?>
          <li class="nav-item">
            <a class="nav-link" href="../user/dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../user/my-bookings.php">My Bookings</a>
          </li>
        <?php } ?>

        <?php if(isset($_SESSION['role']) && $_SESSION['role']=="admin") { ?>
          <li class="nav-item">
            <a class="nav-link" href="../admin/dashboard.php">Admin Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../admin/bookings.php">Bookings</a>
          </li>
        <?php } ?>

        <?php if(isset($_SESSION['role'])) { ?>
          <li class="nav-item">
            <a class="nav-link" href="../auth/logout.php">Logout</a>
          </li>
        <?php } ?>

      </ul>
    </div>
  </div>
</nav>