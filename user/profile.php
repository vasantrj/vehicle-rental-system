<?php
session_start();
$root = '../';
include '../config/db.php';
if(!isset($_SESSION['user_id'])){ header("Location: ../auth/login.php"); exit(); }

$uid = $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE id=$uid"));
$success=''; $error='';

if($_SERVER['REQUEST_METHOD']==='POST'){
  $name  = mysqli_real_escape_string($conn,trim($_POST['name']));
  $phone = mysqli_real_escape_string($conn,trim($_POST['phone']));
  if(empty($name)){ $error="Name cannot be empty."; }
  else {
    if(!empty($_POST['new_password'])){
      if($_POST['new_password']!=$_POST['confirm_password']){ $error="Passwords do not match."; }
      elseif(strlen($_POST['new_password'])<6){ $error="Password must be at least 6 characters."; }
      else {
        $pw = mysqli_real_escape_string($conn,$_POST['new_password']);
        mysqli_query($conn,"UPDATE users SET name='$name',phone='$phone',password='$pw' WHERE id=$uid");
        $success="Profile and password updated!";
      }
    } else {
      mysqli_query($conn,"UPDATE users SET name='$name',phone='$phone' WHERE id=$uid");
      $success="Profile updated successfully!";
    }
    if(!$error){
      $_SESSION['user_name']=$name;
      $user=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE id=$uid"));
    }
  }
}
$initials=strtoupper(substr($user['name'],0,1));
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<title>Profile — DriveEase</title>
</head><body>
<!-- BG -->
<div class="dash-bg" aria-hidden="true"><div class="dash-bg-blob1"></div><div class="dash-bg-blob2"></div><div class="dash-bg-blob3"></div><div class="dash-bg-grid"></div></div>
<?php include '../includes/navbar_user.php'; ?>
<div class="page-wrap">
  <div class="dash-header">
    <div class="eyebrow">My Account</div>
    <h1>My Profile</h1>
  </div>
  <?php if($success): ?><div class="alert alert-success mb-4">✅ <?= $success ?></div><?php endif; ?>
  <?php if($error): ?><div class="auth-error mb-4"><i class="fa-solid fa-triangle-exclamation me-2"></i><?= $error ?></div><?php endif; ?>
  <div class="row justify-content-center mb-5">
    <div class="col-md-7">
      <!-- Avatar card -->
      <div class="profile-card mb-4" style="display:flex;align-items:center;gap:20px">
        <div class="profile-avatar"><?= $initials ?></div>
        <div>
          <div style="font-family:var(--ff-head);font-size:1.2rem;font-weight:700"><?= htmlspecialchars($user['name']) ?></div>
          <div style="color:var(--t2);font-size:.875rem"><?= htmlspecialchars($user['email']) ?></div>
          <div style="margin-top:8px"><span class="badge badge-approved">User Account</span></div>
        </div>
      </div>
      <!-- Edit form -->
      <div class="profile-card">
        <div class="panel-title mb-3"><div class="panel-title-dot"></div>Edit Details</div>
        <form method="POST">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="input-label">Full Name</label>
              <input type="text" name="name" class="input-field" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="input-label">Phone</label>
              <input type="text" name="phone" class="input-field" value="<?= htmlspecialchars($user['phone']??'') ?>" placeholder="+91 XXXXX XXXXX">
            </div>
            <div class="col-12">
              <label class="input-label">Email (read-only)</label>
              <input type="email" class="input-field" value="<?= htmlspecialchars($user['email']) ?>" readonly style="opacity:.5;cursor:not-allowed">
            </div>
          </div>
          <div class="neon-line"></div>
          <div class="panel-title mb-3" style="font-size:.88rem"><div class="panel-title-dot"></div>Change Password <span style="color:var(--t3);font-weight:400;font-size:.8rem">(leave blank to keep current)</span></div>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="input-label">New Password</label>
              <input type="password" name="new_password" class="input-field" placeholder="Min 6 characters">
            </div>
            <div class="col-md-6">
              <label class="input-label">Confirm Password</label>
              <input type="password" name="confirm_password" class="input-field" placeholder="Repeat password">
            </div>
          </div>
          <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn btn-primary btn-md"><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
            <a href="dashboard.php" class="btn btn-ghost btn-md">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
</body></html>
