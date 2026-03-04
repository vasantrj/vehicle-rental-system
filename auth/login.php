<?php
session_start();
include "../config/db.php";

$msg="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$email=$_POST['email'];
$password=$_POST['password'];

$stmt=mysqli_prepare($conn,"SELECT * FROM users WHERE email=?");
mysqli_stmt_bind_param($stmt,"s",$email);
mysqli_stmt_execute($stmt);
$result=mysqli_stmt_get_result($stmt);

$user=mysqli_fetch_assoc($result);

if($user && password_verify($password,$user['password'])){

$_SESSION['user_id']=$user['id'];
$_SESSION['role']=$user['role'];

if($user['role']=="admin"){
header("Location: ../admin/dashboard.php");
}else{
header("Location: ../user/dashboard.php");
}

exit();

}else{
$msg="Invalid credentials";
}

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Login</title>

<link rel="stylesheet" href="../assets/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="auth-wrapper">

<div class="auth-card">

<h3 class="text-center mb-4">Login</h3>

<?php if($msg) echo "<p class='text-danger'>$msg</p>"; ?>

<form method="post">

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<button class="btn btn-primary w-100">
Login
</button>

<p class="text-center mt-3">

Don't have an account?

<a href="register.php">Register</a>

</p>

</form>

</div>

</div>

</body>
</html>