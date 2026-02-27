<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Vehicle not selected.");
}

$vehicle_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT name, price_per_day FROM vehicles WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $vehicle_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$vehicle = mysqli_fetch_assoc($result);

if (!$vehicle) {
    die("Vehicle not found.");
}

$price_per_day = $vehicle['price_per_day'];
$msg = "";

if (isset($_POST['book'])) {

    $start = $_POST['start'];
    $end = $_POST['end'];

    if ($start > $end) {
        $msg = "Invalid date selection.";
    } else {

        $conflict_stmt = mysqli_prepare($conn, "
            SELECT id FROM bookings 
            WHERE vehicle_id = ?
            AND status = 'approved'
            AND (
                (? BETWEEN start_date AND end_date)
                OR
                (? BETWEEN start_date AND end_date)
                OR
                (start_date BETWEEN ? AND ?)
            )
        ");

        mysqli_stmt_bind_param($conflict_stmt, "issss",
            $vehicle_id, $start, $end, $start, $end
        );

        mysqli_stmt_execute($conflict_stmt);
        $conflict_result = mysqli_stmt_get_result($conflict_stmt);

        if (mysqli_num_rows($conflict_result) > 0) {
            $msg = "Vehicle already booked for selected dates.";
        } else {

            $start_date = new DateTime($start);
            $end_date = new DateTime($end);
            $days = $start_date->diff($end_date)->days + 1;
            $total_price = $days * $price_per_day;

            $insert_stmt = mysqli_prepare($conn, "
                INSERT INTO bookings 
                (user_id, vehicle_id, start_date, end_date, total_price, status)
                VALUES (?, ?, ?, ?, ?, 'pending')
            ");

            mysqli_stmt_bind_param($insert_stmt, "iissi",
                $user_id, $vehicle_id, $start, $end, $total_price
            );

            mysqli_stmt_execute($insert_stmt);

            $msg = "Booking request sent! Total Cost: ₹$total_price";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Book Vehicle</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2>Book <?= htmlspecialchars($vehicle['name']) ?> (₹<?= $price_per_day ?>/day)</h2>

<?php if($msg) echo "<p class='text-danger'>$msg</p>"; ?>

<form method="post" class="w-50">
<label>Start Date</label>
<input class="form-control mb-2" type="date" name="start" required>

<label>End Date</label>
<input class="form-control mb-2" type="date" name="end" required>

<button class="btn btn-primary" name="book">Confirm Booking</button>
</form>

</body>
</html>