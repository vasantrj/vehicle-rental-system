<?php

session_start();
include "../config/db.php";

$user_id=$_SESSION['user_id'];
$msg="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$current=$_POST['current'];
$new=$_POST['new'];

$stmt=mysqli_prepare($conn,"SELECT password FROM users WHERE id=?");
mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);

$result=mysqli_stmt_get_result($stmt);
$user=mysqli_fetch_assoc($result);

if(password_verify($current,$user['password'])){

$new_pass=password_hash($new,PASSWORD_DEFAULT);

$update=mysqli_prepare($conn,"UPDATE users SET password=? WHERE id=?");
mysqli_stmt_bind_param($update,"si",$new_pass,$user_id);
mysqli_stmt_execute($update);

$msg="Password updated successfully";

}else{

$msg="Incorrect current password";

}

}

?>

<?php include "../includes/navbar_user.php"; ?>

<!DOCTYPE html>
<html>
<head>

<title>Change Password</title>

<link rel="stylesheet" href="../assets/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container">

<div class="card p-4 mt-5" style="max-width:600px;margin:auto;">

<h3>Change Password</h3>

<?php if($msg!=""){ ?>

<div class="alert alert-info"><?= $msg ?></div>

<?php } ?>

<form method="post">

<div class="mb-3">

<label>Current Password</label>

<input type="password" name="current" class="form-control">

</div>

<div class="mb-3">

<label>New Password</label>

<input type="password" name="new" class="form-control">

</div>

<button class="btn btn-warning w-100">

Update Password

</button>

</form>

</div>

</div>

</body>
</html>