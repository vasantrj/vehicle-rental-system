<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "
    SELECT b.id, v.name AS vehicle_name,
           b.start_date, b.end_date,
           b.total_price, b.status
    FROM bookings b
    JOIN vehicles v ON b.vehicle_id = v.id
    WHERE b.user_id=?
    ORDER BY b.id DESC
");

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
<title>My Bookings</title>
<link rel="stylesheet" href="../assets/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<?php include "../includes/navbar.php"; ?>
<div class="container mt-4">
<h2>My Bookings</h2>

<table class="table table-bordered">
<tr>
<th>Vehicle</th>
<th>From</th>
<th>To</th>
<th>Total</th>
<th>Status</th>
<th>Invoice</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><?= htmlspecialchars($row['vehicle_name']) ?></td>
<td><?= $row['start_date'] ?></td>
<td><?= $row['end_date'] ?></td>
<td>₹<?= $row['total_price'] ?></td>
<td><?= $row['status'] ?></td>
<td>
<?php if($row['status']=='approved') { ?>
<a class="btn btn-primary btn-sm"
   href="invoice.php?id=<?= $row['id'] ?>">
Invoice
</a>
<?php } else { echo "-"; } ?>
</td>
</tr>
<?php } ?>

</table>

<a href="dashboard.php" class="btn btn-secondary">Back</a>
</div>
<?php include "../includes/footer.php"; ?>
</body>
</html>