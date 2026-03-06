<?php

session_start();
include "../config/db.php";

$user_id=$_SESSION['user_id'];

if($_SERVER["REQUEST_METHOD"]=="POST"){

$name=$_POST['name'];
$phone=$_POST['phone'];

$stmt=mysqli_prepare($conn,"UPDATE users SET name=?,phone=? WHERE id=?");
mysqli_stmt_bind_param($stmt,"ssi",$name,$phone,$user_id);
mysqli_stmt_execute($stmt);

header("Location: profile.php");
exit();

}

$stmt=mysqli_prepare($conn,"SELECT * FROM users WHERE id=?");
mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);
$result=mysqli_stmt_get_result($stmt);
$user=mysqli_fetch_assoc($result);

?>

<?php include "../includes/navbar_user.php"; ?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Profile</title>

<link rel="stylesheet" href="../assets/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container">

<div class="card p-4 mt-5" style="max-width:600px;margin:auto;">

<h3>Edit Profile</h3>

<form method="post">

<div class="mb-3">

<label>Name</label>

<input type="text" name="name" value="<?= $user['name'] ?>" class="form-control">

</div>

<div class="mb-3">

<label>Phone</label>

<input type="text" name="phone" value="<?= $user['phone'] ?>" class="form-control">

</div>

<button class="btn btn-primary w-100">

Update Profile

</button>

</form>

</div>

</div>

</body>
</html>