<?php
session_start();
$root = '../';
include '../config/db.php';
if(isset($_SESSION['user_id'])) { header("Location: ../user/dashboard.php"); exit(); }

$error=''; $success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name  = mysqli_real_escape_string($conn,trim($_POST['name']));
  $email = mysqli_real_escape_string($conn,trim($_POST['email']));
  $pass  = $_POST['password'];
  $phone = mysqli_real_escape_string($conn,trim($_POST['phone']??''));
  if(strlen($pass)<6){ $error="Password must be at least 6 characters."; }
  elseif(mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM users WHERE email='$email'"))){ $error="Email already registered."; }
  else {
    $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
    mysqli_query($conn,"INSERT INTO users(name,email,password,phone,role) VALUES('$name','$email','$hashedPass','$phone','user')");
    $success="Account created! <a href='login.php'>Sign in now →</a>";
  }
}
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<title>Register — DriveEase</title>
</head><body>
<div class="auth-wrap">
  <div class="auth-box">
    <div class="auth-card">
      <div class="auth-logo">
        <div class="auth-logo-icon">🚗</div>
        <div style="font-family:var(--ff-head);font-size:1rem;font-weight:700;color:var(--t2)">DriveEase</div>
      </div>
      <h2>Create Account</h2>
      <p class="auth-sub">Join thousands of happy renters today</p>
      <?php if($error): ?><div class="auth-error"><i class="fa-solid fa-triangle-exclamation me-2"></i><?= $error ?></div><?php endif; ?>
      <?php if($success): ?><div class="auth-success"><i class="fa-solid fa-circle-check me-2"></i><?= $success ?></div><?php endif; ?>
      <form method="POST">
        <div class="input-group">
          <label class="input-label">Full Name</label>
          <input type="text" name="name" class="input-field" placeholder="Rahul Sharma" required>
        </div>
        <div class="input-group">
          <label class="input-label">Email Address</label>
          <input type="email" name="email" class="input-field" placeholder="you@example.com" required>
        </div>
        <div class="input-group">
          <label class="input-label">Phone (optional)</label>
          <input type="text" name="phone" class="input-field" placeholder="+91 XXXXX XXXXX">
        </div>
        <div class="input-group">
          <label class="input-label">Password</label>
          <input type="password" name="password" class="input-field" placeholder="Min 6 characters" required>
        </div>
        <button type="submit" class="auth-submit"><i class="fa-solid fa-user-plus me-2"></i>Create Free Account</button>
      </form>
      <div class="auth-footer">Already have an account? <a href="login.php">Sign in →</a></div>
    </div>
  </div>
</div>
<?php include '../includes/scripts.php'; ?>
</body></html>
