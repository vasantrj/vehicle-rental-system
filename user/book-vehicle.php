<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {
    header("Location: ../auth/login.php");
    exit();
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

/* Fetch approved bookings for disabling dates */
$disabled_ranges = [];
$date_stmt = mysqli_prepare($conn, "
    SELECT start_date, end_date 
    FROM bookings 
    WHERE vehicle_id=? AND status='approved'
");
mysqli_stmt_bind_param($date_stmt, "i", $vehicle_id);
mysqli_stmt_execute($date_stmt);
$date_result = mysqli_stmt_get_result($date_stmt);

while ($row = mysqli_fetch_assoc($date_result)) {
    $disabled_ranges[] = [
        "from" => $row['start_date'],
        "to" => $row['end_date']
    ];
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $start = $_POST['start'];
    $end = $_POST['end'];

    if ($start > $end) {
        $msg = "End date must be same or after start date.";
    } else {

        $days = (new DateTime($start))->diff(new DateTime($end))->days + 1;
        $total_price = $days * $price_per_day;

        $insert_stmt = mysqli_prepare($conn, "
            INSERT INTO bookings 
            (user_id, vehicle_id, start_date, end_date, total_price, status, payment_status)
            VALUES (?, ?, ?, ?, ?, 'pending', 'pending')
        ");

        mysqli_stmt_bind_param($insert_stmt, "iissi",
            $user_id, $vehicle_id, $start, $end, $total_price
        );

        mysqli_stmt_execute($insert_stmt);
        $booking_id = mysqli_insert_id($conn);

        header("Location: payment.php?id=" . $booking_id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Book Vehicle</title>

<link rel="stylesheet" href="../assets/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</head>
<body class="p-4">
<?php include "../includes/navbar.php"; ?>
<div class="container mt-4">
<h2>Book <?= htmlspecialchars($vehicle['name']) ?> (₹<?= $price_per_day ?>/day)</h2>

<div class="alert alert-warning">
🔒 Some dates are disabled because the vehicle is already booked.
</div>

<?php if($msg) echo "<p class='text-danger'>$msg</p>"; ?>

<form method="post" class="w-50">
<label>Start Date</label>
<input class="form-control mb-2" type="text" id="start_date" name="start" required>

<label>End Date</label>
<input class="form-control mb-2" type="text" id="end_date" name="end" required>

<button class="btn btn-primary">Proceed to Payment</button>
</form>

<script>

document.addEventListener("DOMContentLoaded", function(){

let disabledRanges = <?= json_encode($disabled_ranges); ?>;

const startPicker = flatpickr("#start_date",{

dateFormat:"Y-m-d",

minDate:"today",

disable:disabledRanges,

onChange:function(selectedDates,dateStr){

endPicker.set("minDate",dateStr);

}

});

const endPicker = flatpickr("#end_date",{

dateFormat:"Y-m-d",

minDate:"today",

disable:disabledRanges

});

});

</script>
</div>
<?php include "../includes/footer.php"; ?>
</body>
</html>