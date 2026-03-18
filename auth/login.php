<?php
session_start();
include "../config/db.php";

if(isset($_POST['login'])){

$email=$_POST['email'];
$password=$_POST['password'];

$q=mysqli_query($conn,"SELECT * FROM users WHERE email='$email' AND password='$password'");

if(mysqli_num_rows($q)>0){

$user=mysqli_fetch_assoc($q);

$_SESSION['user_id']=$user['id'];
$_SESSION['role']=$user['role'];

if($user['role']=="admin"){
header("Location: ../admin/dashboard.php");
}else{
header("Location: ../user/dashboard.php");
}

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

</head>

<body class="auth-page">

<div class="auth-card">

<h2>Login</h2>

<?php if(isset($error)){ ?>
<p style="color:red;text-align:center;"><?php echo $error; ?></p>
<?php } ?>

<form method="POST">

<input type="email" name="email" placeholder="Email" class="auth-input" required>

<input type="password" name="password" placeholder="Password" class="auth-input" required>

<button type="submit" name="login" class="auth-btn">
Login
</button>

</form>

<div class="auth-link">

Don't have an account?

<a href="register.php">Register</a>

</div>

</div>

</body>
</html>