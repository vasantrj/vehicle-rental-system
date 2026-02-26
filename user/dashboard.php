<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Welcome User 😊</h2>
    <p>You can browse vehicles and book them.</p>
    <a class="btn btn-danger" href="../auth/logout.php">Logout</a>
</div>

</body>
</html>