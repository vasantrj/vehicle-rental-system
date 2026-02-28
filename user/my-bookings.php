<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* --------------------------
   FILTER VALUES
-------------------------- */
$status = $_GET['status'] ?? '';
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';
$vehicle_name = $_GET['vehicle_name'] ?? '';

/* --------------------------
   BUILD DYNAMIC QUERY
-------------------------- */
$sql = "
    SELECT b.id, v.name AS vehicle_name,
           b.start_date, b.end_date,
           b.total_price, b.status
    FROM bookings b
    JOIN vehicles v ON b.vehicle_id = v.id
    WHERE b.user_id = ?
";

$params = [$user_id];
$types = "i";

if (!empty($status)) {
    $sql .= " AND b.status = ?";
    $params[] = $status;
    $types .= "s";
}

if (!empty($from_date)) {
    $sql .= " AND b.start_date >= ?";
    $params[] = $from_date;
    $types .= "s";
}

if (!empty($to_date)) {
    $sql .= " AND b.end_date <= ?";
    $params[] = $to_date;
    $types .= "s";
}

if (!empty($vehicle_name)) {
    $sql .= " AND v.name LIKE ?";
    $params[] = "%$vehicle_name%";
    $types .= "s";
}

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, $types, ...$params);
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

<!-- FILTER FORM -->
<form method="get" class="row g-3 mb-4">

<div class="col-md-3">
<label>Status</label>
<select name="status" class="form-control">
<option value="">All</option>
<option value="approved" <?= ($status=='approved')?'selected':'' ?>>Approved</option>
<option value="pending" <?= ($status=='pending')?'selected':'' ?>>Pending</option>
<option value="rejected" <?= ($status=='rejected')?'selected':'' ?>>Rejected</option>
</select>
</div>

<div class="col-md-3">
<label>From Date</label>
<input type="date" name="from_date" value="<?= htmlspecialchars($from_date) ?>" class="form-control">
</div>

<div class="col-md-3">
<label>To Date</label>
<input type="date" name="to_date" value="<?= htmlspecialchars($to_date) ?>" class="form-control">
</div>

<div class="col-md-3">
<label>Vehicle Name</label>
<input type="text" name="vehicle_name" value="<?= htmlspecialchars($vehicle_name) ?>" class="form-control">
</div>

<div class="col-md-12">
<button class="btn btn-primary">Apply Filters</button>
<a href="my-bookings.php" class="btn btn-secondary">Reset</a>
</div>

</form>

<!-- BOOKINGS TABLE -->
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
       Invoice
    </a>
<?php } else { ?>
    -
<?php } ?>

</td>
</tr>
<?php } ?>

</table>

<a class="btn btn-secondary" href="dashboard.php">Back</a>

</body>
</html>