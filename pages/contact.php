<?php
session_start();
$root='../';
include '../config/db.php';
$success=''; $error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name  = mysqli_real_escape_string($conn, trim($_POST['name']??''));
  $email = mysqli_real_escape_string($conn, trim($_POST['email']??''));
  $subj  = mysqli_real_escape_string($conn, trim($_POST['subject']??''));
  $msg   = mysqli_real_escape_string($conn, trim($_POST['message']??''));
  if(!$name||!$email||!$msg){ $error="Please fill in all required fields."; }
  else {
    mysqli_query($conn,"INSERT INTO feedback(name,email,message,rating) VALUES('$name','$email','[CONTACT] $subj: $msg',0)");
    $success="✅ Message sent! We'll get back to you within 24 hours.";
  }
}
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<title>Contact — DriveEase</title>
</head><body>
<div class="dash-bg" aria-hidden="true">
  <div class="dash-bg-blob1"></div><div class="dash-bg-blob2"></div>
  <div class="dash-bg-blob3"></div><div class="dash-bg-grid"></div>
</div>
<?php include '../includes/navbar_public.php'; ?>

<!-- HEADER -->
<section style="padding:80px 0 60px;text-align:center;position:relative">
  <div style="position:absolute;inset:0;background:radial-gradient(ellipse 60% 60% at 50% 0%,rgba(0,245,212,.05),transparent 65%);pointer-events:none"></div>
  <div class="wrap" style="position:relative;z-index:1">
    <p class="label-sm" style="color:var(--cyan);margin-bottom:14px">Get In Touch</p>
    <h1 style="font-size:clamp(1.8rem,4vw,3rem);font-weight:800;letter-spacing:-.03em;color:var(--t1)">
      We'd Love to <span class="text-gradient">Hear From You</span>
    </h1>
    <p style="color:var(--t2);margin-top:12px;font-size:1rem">Our support team typically responds within a few hours</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="row g-5 justify-content-center">
      <!-- Contact Info -->
      <div class="col-lg-4 col-md-5">
        <div class="profile-card h-100">
          <h5 style="font-family:var(--ff-head);font-weight:700;margin-bottom:28px;color:var(--t1)">Contact Details</h5>
          <div style="display:flex;flex-direction:column;gap:24px">
            <?php
            $contacts = [
              ['📧','Email','support@driveease.in','mailto:support@driveease.in'],
              ['📞','Phone','+91 98765 43210','tel:+919876543210'],
              ['📍','Address','Bengaluru, Karnataka, India',null],
              ['⏰','Support Hours','24/7 — Always Available',null],
            ];
            foreach($contacts as [$icon,$label,$value,$link]):
            ?>
            <div style="display:flex;gap:16px;align-items:flex-start">
              <div style="width:44px;height:44px;border-radius:var(--r2);background:var(--orange-dim);border:1px solid var(--b-orange);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0"><?= $icon ?></div>
              <div>
                <div style="font-size:.72rem;color:var(--t3);text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px;font-weight:700"><?= $label ?></div>
                <?php if($link): ?>
                  <a href="<?= $link ?>" style="color:var(--t1);font-size:.9rem;font-weight:500"><?= $value ?></a>
                <?php else: ?>
                  <div style="color:var(--t1);font-size:.9rem;font-weight:500"><?= $value ?></div>
                <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>

          <!-- Quick links -->
          <div style="margin-top:32px;padding-top:24px;border-top:1px solid var(--b1)">
            <div style="font-size:.75rem;color:var(--t3);text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:16px">Quick Links</div>
            <div style="display:flex;flex-direction:column;gap:10px">
              <a href="../pages/about.php" style="color:var(--t2);font-size:.875rem;transition:var(--t-fast)" onmouseover="this.style.color='var(--orange)'" onmouseout="this.style.color='var(--t2)'"><i class="fa-solid fa-arrow-right me-2" style="color:var(--orange)"></i>About DriveEase</a>
              <a href="../auth/register.php" style="color:var(--t2);font-size:.875rem;transition:var(--t-fast)" onmouseover="this.style.color='var(--orange)'" onmouseout="this.style.color='var(--t2)'"><i class="fa-solid fa-arrow-right me-2" style="color:var(--orange)"></i>Create Account</a>
              <a href="../user/vehicles.php" style="color:var(--t2);font-size:.875rem;transition:var(--t-fast)" onmouseover="this.style.color='var(--orange)'" onmouseout="this.style.color='var(--t2)'"><i class="fa-solid fa-arrow-right me-2" style="color:var(--orange)"></i>Browse Vehicles</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="col-lg-7 col-md-7">
        <div class="profile-card">
          <h5 style="font-family:var(--ff-head);font-weight:700;margin-bottom:28px;color:var(--t1)">Send a Message</h5>
          <?php if($success): ?><div class="alert alert-success mb-4"><?= $success ?></div><?php endif; ?>
          <?php if($error): ?><div class="auth-error mb-4"><i class="fa-solid fa-triangle-exclamation me-2"></i><?= $error ?></div><?php endif; ?>
          <form method="POST">
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="input-label">Your Name <span style="color:var(--red)">*</span></label>
                <input type="text" name="name" class="input-field" placeholder="Rahul Sharma" required value="<?= htmlspecialchars($_POST['name']??'') ?>">
              </div>
              <div class="col-md-6">
                <label class="input-label">Email Address <span style="color:var(--red)">*</span></label>
                <input type="email" name="email" class="input-field" placeholder="you@example.com" required value="<?= htmlspecialchars($_POST['email']??'') ?>">
              </div>
            </div>
            <div class="mb-3">
              <label class="input-label">Subject</label>
              <input type="text" name="subject" class="input-field" placeholder="How can we help?" value="<?= htmlspecialchars($_POST['subject']??'') ?>">
            </div>
            <div class="mb-4">
              <label class="input-label">Message <span style="color:var(--red)">*</span></label>
              <textarea name="message" class="input-field" rows="6" placeholder="Tell us about your query, feedback, or issue..." required><?= htmlspecialchars($_POST['message']??'') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-lg" style="width:100%">
              <i class="fa-solid fa-paper-plane me-2"></i>Send Message
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
</body></html>
