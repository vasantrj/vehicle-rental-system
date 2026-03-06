<?php

session_start();
include "../config/db.php";

if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
exit();
}

$result=mysqli_query($conn,"SELECT * FROM vehicles");

?>

<?php include "../includes/navbar_user.php"; ?>

<!DOCTYPE html>
<html>
<head>

<title>Book Vehicle</title>

<link rel="stylesheet" href="../assets/style.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</head>

<body>

<div class="container mt-5">

<h2>Available Vehicles</h2>

<div class="row mt-4">

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<div class="col-md-4">

<div class="card vehicle-card p-3">

<img src="../assets/images/<?= $row['image'] ?>" class="img-fluid mb-3">

<h5><?= $row['name'] ?></h5>

<p>Price per day: ₹<?= $row['price_per_day'] ?></p>

<form action="payment.php" method="post">

<input type="hidden" name="vehicle_id" value="<?= $row['id'] ?>">

<div class="mb-2">

<label>Start Date</label>

<input type="text" name="start_date" class="form-control start-date" required>

</div>

<div class="mb-2">

<label>End Date</label>

<input type="text" name="end_date" class="form-control end-date" required>

</div>

<button class="btn btn-primary w-100">
Proceed to Pay
</button>

</form>

</div>

</div>

<?php } ?>

</div>

</div>

<script>
document.querySelectorAll("form").forEach(form => {

let vehicle_id=form.querySelector("input[name='vehicle_id']").value;

fetch("get-booked-dates.php?vehicle_id="+vehicle_id)
.then(res=>res.json())
.then(disabledDates=>{

flatpickr(form.querySelector(".start-date"),{
minDate:"today",
disable:disabledDates
});

flatpickr(form.querySelector(".end-date"),{
minDate:"today",
disable:disabledDates
});

});

});
</script>

</body>
</html>