<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_GET['approve'])) {
    $id = $_GET['approve'];
    mysqli_query($conn, "UPDATE bookings SET status='approved' WHERE id=$id");
}

if (isset($_GET['reject'])) {
    $id = $_GET['reject'];
    mysqli_query($conn, "UPDATE bookings SET status='rejected' WHERE id=$id");
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
          <a class="btn btn-success btn-sm" href="?approve=<?= $row['id'] ?>">Approve</a>
          <a class="btn btn-danger btn-sm" href="?reject=<?= $row['id'] ?>">Reject</a>
        </td>
      </tr>
    <?php } ?>
  </table>

  <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>