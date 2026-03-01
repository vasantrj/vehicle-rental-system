<?php
$status = $_GET['status'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment Result</title>
<link rel="stylesheet" href="../assets/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.success { color: green; }
.failed { color: red; }
.big-icon { font-size: 80px; }
</style>
</head>
<body class="text-center p-5">
<?php include "../includes/navbar.php"; ?>
<div class="container mt-4">
<?php if ($status == "success") { ?>
    <div class="big-icon success">✔</div>
    <h2 class="success">Payment Successful!</h2>
<?php } else { ?>
    <div class="big-icon failed">✖</div>
    <h2 class="failed">Payment Failed!</h2>
<?php } ?>

<a href="my-bookings.php" class="btn btn-primary mt-4">Go to My Bookings</a>
</div>
<?php include "../includes/footer.php"; ?>
</body>
</html>