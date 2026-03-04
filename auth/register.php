<?php
session_start();
include "../config/db.php";

$msg="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$name=$_POST['name'];
$email=$_POST['email'];
$password=password_hash($_POST['password'],PASSWORD_DEFAULT);

$role="user";

if($email=="admin@gmail.com"){
$role="admin";
}

$stmt=mysqli_prepare($conn,"INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)");
mysqli_stmt_bind_param($stmt,"ssss",$name,$email,$password,$role);

if(mysqli_stmt_execute($stmt)){
header("Location: login.php");
exit();
}else{
$msg="Registration failed.";
}

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Register</title>

<link rel="stylesheet" href="../assets/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="auth-wrapper">

<div class="auth-card">

<h3 class="text-center mb-4">Create Account</h3>

<?php if($msg) echo "<p class='text-danger'>$msg</p>"; ?>

<form method="post">

<div class="mb-3">
<label>Name</label>
<input type="text" name="name" class="form-control" required>
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<button class="btn btn-primary w-100">
Register
</button>

<p class="text-center mt-3">

Already have an account?

<a href="login.php">Login</a>

</p>

</form>

</div>

</div>

</body>
</html>