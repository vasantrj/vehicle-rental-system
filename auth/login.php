<?php
session_start();
include "../config/db.php";

$error="";

if(isset($_POST['login'])){

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = mysqli_prepare($conn,"SELECT * FROM users WHERE email=?");
mysqli_stmt_bind_param($stmt,"s",$email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result)==1){

$user = mysqli_fetch_assoc($result);

/* If password stored as plain text (admin) */
if($user['password'] === $password){

/* convert to hashed automatically */
$newhash = password_hash($password,PASSWORD_DEFAULT);

$update = mysqli_prepare($conn,"UPDATE users SET password=? WHERE id=?");
mysqli_stmt_bind_param($update,"si",$newhash,$user['id']);
mysqli_stmt_execute($update);

$_SESSION['user_id']=$user['id'];
$_SESSION['role']=$user['role'];

if($user['role']=="admin"){
header("Location: ../admin/dashboard.php");
}else{
header("Location: ../user/dashboard.php");
}

exit();
}

/* normal hashed login */
if(password_verify($password,$user['password'])){

$_SESSION['user_id']=$user['id'];
$_SESSION['role']=$user['role'];

if($user['role']=="admin"){
header("Location: ../admin/dashboard.php");
}else{
header("Location: ../user/dashboard.php");
}

exit();

}

$error="Invalid Email or Password";

}else{

$error="Invalid Email or Password";

}

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Login</title>

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

.login-card{
background:white;
padding:40px;
border-radius:12px;
width:380px;
box-shadow:0 15px 40px rgba(0,0,0,0.2);
}

</style>

</head>

<body>

<div class="login-card">

<h3 class="text-center mb-4">Login</h3>

<?php if($error!=""){ ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<form method="POST">

<input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

<button name="login" class="btn btn-primary w-100">Login</button>

</form>

<p class="text-center mt-3">
Don't have an account?
<a href="register.php">Register</a>
</p>

</div>

</body>
</html>