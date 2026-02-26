<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {
    header("Location: ../auth/login.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM vehicles WHERE status='available'");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Available Vehicles</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <h2>Available Vehicles</h2>

  <table class="table table-bordered">
    <tr>
      <th>Name</th>
      <th>Brand</th>
      <th>Price/Day</th>
      <th>Action</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?= $row['name'] ?></td>
        <td><?= $row['brand'] ?></td>
        <td><?= $row['price_per_day'] ?></td>
        <td>
          <a class="btn btn-success btn-sm" href="book-vehicle.php?id=<?= $row['id'] ?>">Book</a>
        </td>
      </tr>
    <?php } ?>
  </table>

  <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>