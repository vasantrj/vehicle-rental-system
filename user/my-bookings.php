<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* --------------------------
   CANCEL BOOKING (SECURED)
-------------------------- */
if (isset($_GET['cancel'])) {

    $booking_id = intval($_GET['cancel']);

    // Check booking belongs to user and is pending
    $stmt = mysqli_prepare($conn,
        "SELECT vehicle_id FROM bookings 
         WHERE id=? AND user_id=? AND status='pending'"
    );

    mysqli_stmt_bind_param($stmt, "ii", $booking_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $booking = mysqli_fetch_assoc($result);

    if ($booking) {

        // Update booking status
        $update_stmt = mysqli_prepare($conn,
            "UPDATE bookings SET status='rejected' WHERE id=?"
        );

        mysqli_stmt_bind_param($update_stmt, "i", $booking_id);
        mysqli_stmt_execute($update_stmt);

        // Make vehicle available again
        $vehicle_stmt = mysqli_prepare($conn,
            "UPDATE vehicles SET status='available' WHERE id=?"
        );

        mysqli_stmt_bind_param($vehicle_stmt, "i", $booking['vehicle_id']);
        mysqli_stmt_execute($vehicle_stmt);
    }
}

/* --------------------------
   FETCH BOOKINGS (SECURED)
-------------------------- */
$stmt = mysqli_prepare($conn, "
    SELECT b.id, v.name AS vehicle_name, 
           b.start_date, b.end_date, 
           b.total_price, b.status
    FROM bookings b
    JOIN vehicles v ON b.vehicle_id = v.id
    WHERE b.user_id = ?
");

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
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
<th>Total</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><?= htmlspecialchars($row['vehicle_name']) ?></td>
<td><?= $row['start_date'] ?></td>
<td><?= $row['end_date'] ?></td>
<td>₹<?= $row['total_price'] ?></td>
<td><?= $row['status'] ?></td>
<td>

<?php if($row['status'] == 'approved') { ?>
    <a class="btn btn-primary btn-sm"
       href="invoice.php?id=<?= $row['id'] ?>">
       Download Invoice
    </a>

<?php } elseif($row['status'] == 'pending') { ?>
    <a class="btn btn-danger btn-sm"
       href="?cancel=<?= $row['id'] ?>">
       Cancel
    </a>

<?php } else { ?>
    No Action
<?php } ?>

</td>
</tr>
<?php } ?>

</table>

<a class="btn btn-secondary" href="dashboard.php">Back</a>

</body>
</html>