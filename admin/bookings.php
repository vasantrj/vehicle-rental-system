<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_GET['approve'])) {

    $booking_id = intval($_GET['approve']);

    $stmt = mysqli_prepare($conn,
        "UPDATE bookings SET status='approved' WHERE id=?"
    );
    mysqli_stmt_bind_param($stmt, "i", $booking_id);
    mysqli_stmt_execute($stmt);
}

if (isset($_GET['reject'])) {

    $booking_id = intval($_GET['reject']);

    $stmt = mysqli_prepare($conn,
        "UPDATE bookings SET status='rejected' WHERE id=?"
    );
    mysqli_stmt_bind_param($stmt, "i", $booking_id);
    mysqli_stmt_execute($stmt);
}

$result = mysqli_query($conn, "
    SELECT b.id, u.name AS user_name, v.name AS vehicle_name,
           b.start_date, b.end_date, b.total_price, b.status
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
<th>Total</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><?= htmlspecialchars($row['user_name']) ?></td>
<td><?= htmlspecialchars($row['vehicle_name']) ?></td>
<td><?= $row['start_date'] ?></td>
<td><?= $row['end_date'] ?></td>
<td>₹<?= $row['total_price'] ?></td>
<td><?= $row['status'] ?></td>
<td>
<?php if($row['status']=='pending') { ?>
<a class="btn btn-success btn-sm" href="?approve=<?= $row['id'] ?>">Approve</a>
<a class="btn btn-danger btn-sm" href="?reject=<?= $row['id'] ?>">Reject</a>
<?php } else { echo "No Action"; } ?>
</td>
</tr>
<?php } ?>

</table>

</body>
</html>