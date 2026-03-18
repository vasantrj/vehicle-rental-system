<?php
session_start();
include "../config/db.php";

if(isset($_POST['register'])){

$name=$_POST['name'];
$email=$_POST['email'];
$password=$_POST['password'];

$q=mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

if(mysqli_num_rows($q)>0){

$error="Email already registered";

}else{

mysqli_query($conn,"INSERT INTO users(name,email,password,role)
VALUES('$name','$email','$password','user')");

header("Location: login.php");

}

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Register</title>

<link rel="stylesheet" href="../assets/style.css">

</head>

<body class="auth-page">

<div class="auth-card">

<h2>Create Account</h2>

<?php if(isset($error)){ ?>
<p style="color:red;text-align:center;"><?php echo $error; ?></p>
<?php } ?>

<form method="POST">

<input type="text" name="name" placeholder="Full Name" class="auth-input" required>

<input type="email" name="email" placeholder="Email" class="auth-input" required>

<input type="password" name="password" placeholder="Password" class="auth-input" required>

<button type="submit" name="register" class="auth-btn">
Register
</button>

</form>

<div class="auth-link">

Already have an account?

<a href="login.php">Login</a>

</div>

</div>

</body>
</html>