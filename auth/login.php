<?php
session_start();
$root = '../';
include '../config/db.php';
if(isset($_SESSION['user_id'])) { header("Location: ../user/dashboard.php"); exit(); }
if(isset($_SESSION['role']) && $_SESSION['role']==='admin') { header("Location: ../admin/dashboard.php"); exit(); }

$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email = mysqli_real_escape_string($conn,$_POST['email']);
  $pass  = $_POST['password'];
  $user  = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE email='$email'"));
  if($user && $user['password']===$pass){
    $_SESSION['user_id']=$user['id'];
    $_SESSION['user_name']=$user['name'];
    $_SESSION['role']=$user['role'];
    if($user['role']==='admin') header("Location: ../admin/dashboard.php");
    else header("Location: ../user/dashboard.php");
    exit();
  } else { $error="Invalid email or password."; }
}
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<title>Sign In — DriveEase</title>
</head><body>
<div class="auth-wrap">
  <div class="auth-box">
    <div class="auth-card">
      <div class="auth-logo">
        <div class="auth-logo-icon">🚗</div>
        <div style="font-family:var(--ff-head);font-size:1rem;font-weight:700;color:var(--t2)">DriveEase</div>
      </div>
      <h2>Welcome Back</h2>
      <p class="auth-sub">Sign in to your account to continue</p>
      <?php if($error): ?><div class="auth-error"><i class="fa-solid fa-triangle-exclamation me-2"></i><?= $error ?></div><?php endif; ?>
      <form method="POST">
        <div class="input-group">
          <label class="input-label">Email Address</label>
          <input type="email" name="email" class="input-field" placeholder="you@example.com" value="<?= htmlspecialchars($_POST['email']??'') ?>" required autofocus>
        </div>
        <div class="input-group">
          <label class="input-label">Password</label>
          <input type="password" name="password" class="input-field" placeholder="Your password" required>
        </div>
        <button type="submit" class="auth-submit"><i class="fa-solid fa-arrow-right-to-bracket me-2"></i>Sign In</button>
      </form>
      <div class="auth-footer">Don't have an account? <a href="register.php">Create one free →</a></div>
    </div>
  </div>
</div>
<?php include '../includes/scripts.php'; ?>
</body></html>
