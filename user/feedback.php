<?php
session_start();
$root = '../';
include '../config/db.php';
if(!isset($_SESSION['user_id'])){ header("Location: ../auth/login.php"); exit(); }
$uid  = $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE id=$uid"));
$success=''; $error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $rating  = intval($_POST['rating']??0);
  $message = mysqli_real_escape_string($conn,trim($_POST['message']));
  $name    = mysqli_real_escape_string($conn,$user['name']);
  $email   = mysqli_real_escape_string($conn,$user['email']);
  if($rating<1||$rating>5){ $error="Please select a rating."; }
  elseif(strlen($message)<10){ $error="Please write at least 10 characters."; }
  else {
    mysqli_query($conn,"INSERT INTO feedback(user_id,name,email,message,rating) VALUES($uid,'$name','$email','$message',$rating)");
    $success="Thank you! Your feedback has been submitted. ⭐";
  }
}
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<title>Feedback — DriveEase</title>
</head><body>
<!-- BG -->
<div class="dash-bg" aria-hidden="true"><div class="dash-bg-blob1"></div><div class="dash-bg-blob2"></div><div class="dash-bg-blob3"></div><div class="dash-bg-grid"></div></div>
<?php include '../includes/navbar_user.php'; ?>
<div class="page-wrap">
  <div class="dash-header">
    <div class="eyebrow">Community</div>
    <h1>Share Your Experience</h1>
    <div class="sub">Your honest feedback helps us serve you better</div>
  </div>
  <?php if($success): ?><div class="alert alert-success mb-4">✅ <?= $success ?></div><?php endif; ?>
  <?php if($error): ?><div class="auth-error mb-4"><i class="fa-solid fa-triangle-exclamation me-2"></i><?= $error ?></div><?php endif; ?>
  <div class="row justify-content-center mb-5">
    <div class="col-md-7">
      <div class="profile-card">
        <form method="POST">
          <input type="hidden" name="rating" id="rating-value" value="0">
          <label class="input-label">Your Rating</label>
          <div class="star-rating mb-4">
            <span class="star" data-val="1">★</span>
            <span class="star" data-val="2">★</span>
            <span class="star" data-val="3">★</span>
            <span class="star" data-val="4">★</span>
            <span class="star" data-val="5">★</span>
          </div>
          <div class="mb-4">
            <label class="input-label">Your Review</label>
            <textarea name="message" class="input-field" rows="5" placeholder="Tell us about your rental experience — what went well, what could be better..." required></textarea>
          </div>
          <div style="background:var(--bg-3);border:1px solid var(--b1);border-radius:var(--r2);padding:14px 16px;margin-bottom:24px;display:flex;align-items:center;gap:12px">
            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--orange),var(--cyan));display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;color:#02040b;flex-shrink:0">
              <?= strtoupper(substr($user['name'],0,1)) ?>
            </div>
            <div>
              <div style="font-weight:600;font-size:.9rem"><?= htmlspecialchars($user['name']) ?></div>
              <div style="color:var(--t3);font-size:.75rem"><?= htmlspecialchars($user['email']) ?></div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-md" style="width:100%"><i class="fa-solid fa-paper-plane me-2"></i>Submit Feedback</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
</body></html>
