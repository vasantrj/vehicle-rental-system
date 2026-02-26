<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

// Approve booking
if (isset($_GET['approve'])) {
    $booking_id = $_GET['approve'];

    // Get vehicle id for this booking
    $res = mysqli_query($conn, "SELECT vehicle_id FROM bookings WHERE id=$booking_id");
    $row = mysqli_fetch_assoc($res);
    $vehicle_id = $row['vehicle_id'];

    // Update booking + vehicle status
    mysqli_query($conn, "UPDATE bookings SET status='approved' WHERE id=$booking_id");
    mysqli_query($conn, "UPDATE vehicles SET status='booked' WHERE id=$vehicle_id");
}

// Reject booking
if (isset($_GET['reject'])) {
    $booking_id = $_GET['reject'];
    mysqli_query($conn, "UPDATE bookings SET status='rejected' WHERE id=$booking_id");
}

$result = mysqli_query($conn, "
    SELECT b.id, u.name AS user_name, v.name AS vehicle_name, 
           b.start_date, b.end_date, b.status
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN vehicles v ON b.vehicle_id = v.id
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>All Bookings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <h2>All Bookings</h2>

  <table class="table table-bordered">
    <tr>
      <th>User</th>
      <th>Vehicle</th>
      <th>From</th>
      <th>To</th>
      <th>Status</th>
      <th>Action</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?= $row['user_name'] ?></td>
        <td><?= $row['vehicle_name'] ?></td>
        <td><?= $row['start_date'] ?></td>
        <td><?= $row['end_date'] ?></td>
        <td><?= $row['status'] ?></td>
        <td>
          <?php if($row['status'] === 'pending') { ?>
            <a class="btn btn-success btn-sm" href="?approve=<?= $row['id'] ?>">Approve</a>
            <a class="btn btn-danger btn-sm" href="?reject=<?= $row['id'] ?>">Reject</a>
          <?php } else { ?>
            <span class="text-muted">No Action</span>
          <?php } ?>
        </td>
      </tr>
    <?php } ?>
  </table>

  <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>