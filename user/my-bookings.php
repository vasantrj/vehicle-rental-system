<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {
    header("Location: ../auth/login.php");
    exit();
}

$email = $_SESSION['email'];

$userRes = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($userRes);
$user_id = $user['id'];

$result = mysqli_query($conn, "
    SELECT v.name, b.start_date, b.end_date, b.status
    FROM bookings b
    JOIN vehicles v ON b.vehicle_id = v.id
    WHERE b.user_id = $user_id
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Bookings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <h2>My Bookings</h2>

  <table class="table table-bordered">
    <tr>
      <th>Vehicle</th>
      <th>From</th>
      <th>To</th>
      <th>Status</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?= $row['name'] ?></td>
        <td><?= $row['start_date'] ?></td>
        <td><?= $row['end_date'] ?></td>
        <td><?= $row['status'] ?></td>
      </tr>
    <?php } ?>
  </table>

  <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>