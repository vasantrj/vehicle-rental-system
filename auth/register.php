<?php

session_start();
include "../config/db.php";

$error="";
$success="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$name=$_POST['name'];
$email=$_POST['email'];
$password=$_POST['password'];

$hashed=password_hash($password,PASSWORD_DEFAULT);

try{

$stmt=mysqli_prepare($conn,"INSERT INTO users(name,email,password) VALUES(?,?,?)");
mysqli_stmt_bind_param($stmt,"sss",$name,$email,$hashed);

mysqli_stmt_execute($stmt);

$success="Registration successful. You can login now.";

}catch(mysqli_sql_exception $e){

$error="Email already registered.";

}

}

?>

<!DOCTYPE html>
<html>
<head>

<title>Register</title>

<link rel="stylesheet" href="../assets/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:linear-gradient(120deg,#ff7a18,#ff4e00);
height:100vh;
display:flex;
align-items:center;
justify-content:center;
}

.card{
width:420px;
padding:35px;
border-radius:14px;
box-shadow:0 15px 40px rgba(0,0,0,0.2);
}

</style>

</head>

<body>

<div class="card">

<h3 class="mb-4 text-center">Register</h3>

<?php if($error!=""){ ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<?php if($success!=""){ ?>
<div class="alert alert-success"><?= $success ?></div>
<?php } ?>

<form method="POST">

<input type="text" name="name" class="form-control mb-3" placeholder="Name" required>

<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<button class="btn btn-primary w-100">

Register

</button>

</form>

<p class="text-center mt-3">

Already have an account?  
<a href="login.php">Login</a>

</p>

</div>

</body>
</html>