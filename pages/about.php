<?php session_start(); $root='../'; include '../config/db.php'; include '../includes/track_visit.php'; ?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<title>About Us — DriveEase</title>
</head><body>
<div class="dash-bg" aria-hidden="true">
  <div class="dash-bg-blob1"></div><div class="dash-bg-blob2"></div>
  <div class="dash-bg-blob3"></div><div class="dash-bg-grid"></div>
</div>
<?php include '../includes/navbar_public.php'; ?>

<!-- HERO -->
<section style="padding:100px 0 80px;text-align:center;position:relative;overflow:hidden">
  <div style="position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 50% 0%,rgba(249,115,22,.07),transparent 65%);pointer-events:none"></div>
  <div class="wrap" style="position:relative;z-index:1">
    <p class="label-sm" style="color:var(--orange);margin-bottom:14px">Our Story</p>
    <h1 style="font-size:clamp(2rem,5vw,3.5rem);font-weight:800;letter-spacing:-.03em;margin-bottom:20px;color:var(--t1)">
      Built for Modern <span class="text-gradient">Travelers</span>
    </h1>
    <p style="color:var(--t2);max-width:620px;margin:0 auto 36px;font-size:1.05rem;line-height:1.8">
      DriveEase was founded with a single mission: make vehicle rental as seamless as booking a cab. No paperwork, no hidden charges — just great vehicles and a smooth ride.
    </p>
    <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap">
      <a href="../auth/register.php" class="btn btn-primary btn-lg"><i class="fa-solid fa-rocket me-2"></i>Get Started Free</a>
      <a href="../pages/contact.php" class="btn btn-ghost btn-lg">Contact Us</a>
    </div>
  </div>
</section>

<div class="divider-glow" style="margin:0 24px"></div>

<!-- STATS -->
<section class="section">
  <div class="wrap">
    <div class="section-head">
      <p class="label-sm" style="color:var(--cyan);margin-bottom:10px">By The Numbers</p>
      <h2>DriveEase <span class="text-gradient-cyan">at a Glance</span></h2>
    </div>
    <div class="row g-4">
      <div class="col-md-3 col-6"><div class="stat-card stat-orange" style="text-align:center">
        <span class="stat-label">Vehicles</span>
        <span class="stat-value" data-target="500" data-suffix="+">500+</span>
        <span class="stat-icon-bg">🚗</span>
      </div></div>
      <div class="col-md-3 col-6"><div class="stat-card stat-blue" style="text-align:center">
        <span class="stat-label">Happy Renters</span>
        <span class="stat-value" data-target="12000" data-suffix="+">12K+</span>
        <span class="stat-icon-bg">😊</span>
      </div></div>
      <div class="col-md-3 col-6"><div class="stat-card stat-green" style="text-align:center">
        <span class="stat-label">Cities</span>
        <span class="stat-value" data-target="50" data-suffix="+">50+</span>
        <span class="stat-icon-bg">📍</span>
      </div></div>
      <div class="col-md-3 col-6"><div class="stat-card stat-purple" style="text-align:center">
        <span class="stat-label">Avg Rating</span>
        <span class="stat-value">4.9★</span>
        <span class="stat-icon-bg">⭐</span>
      </div></div>
    </div>
  </div>
</section>

<!-- VALUES -->
<section class="section" style="background:rgba(6,10,20,.6)">
  <div class="wrap">
    <div class="section-head">
      <p class="label-sm" style="color:var(--orange);margin-bottom:10px">What Drives Us</p>
      <h2>Our <span class="text-gradient">Core Values</span></h2>
      <p>Every decision we make is guided by these principles</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4"><div class="feat-card">
        <div class="feat-icon">🎯</div>
        <h4>Transparency First</h4>
        <p>No hidden charges. The price you see on the vehicle card is exactly what you pay — taxes included.</p>
      </div></div>
      <div class="col-md-4"><div class="feat-card">
        <div class="feat-icon">⚡</div>
        <h4>Speed & Simplicity</h4>
        <p>From browsing to booking confirmation in under 2 minutes. We respect your time.</p>
      </div></div>
      <div class="col-md-4"><div class="feat-card">
        <div class="feat-icon">🛡️</div>
        <h4>Safety & Trust</h4>
        <p>All vehicles are RC-verified, comprehensively insured, and undergo regular service checks.</p>
      </div></div>
      <div class="col-md-4"><div class="feat-card">
        <div class="feat-icon">💳</div>
        <h4>Secure Payments</h4>
        <p>Powered by Razorpay with 256-bit SSL. Your payment data is never stored on our servers.</p>
      </div></div>
      <div class="col-md-4"><div class="feat-card">
        <div class="feat-icon">📞</div>
        <h4>24/7 Support</h4>
        <p>Got a problem at 2am? Our support team is always on. Call, email, or chat — we're here.</p>
      </div></div>
      <div class="col-md-4"><div class="feat-card">
        <div class="feat-icon">🌱</div>
        <h4>Eco Conscious</h4>
        <p>We're gradually adding EVs and hybrids to our fleet to reduce our carbon footprint.</p>
      </div></div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="section">
  <div class="wrap">
    <div class="section-head">
      <p class="label-sm" style="color:var(--cyan);margin-bottom:10px">Simple Process</p>
      <h2>How <span class="text-gradient-cyan">It Works</span></h2>
      <p>Four steps from sign-up to driving away</p>
    </div>
    <div class="row g-4">
      <div class="col-md-3 col-sm-6"><div class="step-card fade-up fade-up-1"><div class="step-num">01</div><h4>Create Account</h4><p>Sign up with your email in seconds — no credit card required.</p></div></div>
      <div class="col-md-3 col-sm-6"><div class="step-card fade-up fade-up-2"><div class="step-num">02</div><h4>Pick a Vehicle</h4><p>Browse and filter by brand, price, or availability.</p></div></div>
      <div class="col-md-3 col-sm-6"><div class="step-card fade-up fade-up-3"><div class="step-num">03</div><h4>Choose Dates</h4><p>Live calendar blocks already-booked dates automatically.</p></div></div>
      <div class="col-md-3 col-sm-6"><div class="step-card fade-up fade-up-4"><div class="step-num">04</div><h4>Pay &amp; Drive</h4><p>Secure Razorpay checkout. Invoice in your inbox instantly.</p></div></div>
    </div>
  </div>
</section>

<!-- TECH STACK -->
<section class="section" style="background:rgba(6,10,20,.6)">
  <div class="wrap text-center">
    <p class="label-sm" style="color:var(--orange);margin-bottom:12px">Built With</p>
    <h2>Technology Stack</h2>
    <p style="color:var(--t2);margin-bottom:40px;max-width:560px;margin-inline:auto">
      A production-grade PHP application with modern tooling and reliable third-party integrations
    </p>
    <div class="row g-3 justify-content-center">
      <?php
      $techs = [
        ['PHP 8','var(--orange)'],['MySQL','var(--cyan)'],['Bootstrap 5','var(--purple-soft)'],
        ['Razorpay API','var(--orange)'],['PHPMailer','var(--cyan)'],['FPDF','var(--purple-soft)'],
        ['Flatpickr','var(--orange)'],['Chart.js','var(--cyan)'],['Font Awesome','var(--purple-soft)']
      ];
      foreach($techs as [$name,$color]):
      ?>
      <div class="col-auto">
        <span style="background:rgba(10,16,32,.9);border:1px solid <?= $color ?>;color:<?= $color ?>;
          padding:10px 22px;border-radius:999px;font-size:.85rem;font-weight:700;
          box-shadow:0 0 12px rgba(0,0,0,.3);display:inline-block">
          <?= $name ?>
        </span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section">
  <div class="wrap text-center">
    <p class="label-sm" style="color:var(--orange);margin-bottom:14px">Join Us</p>
    <h2 style="font-size:clamp(1.8rem,4vw,2.8rem);font-weight:800;margin-bottom:16px">
      Ready to <span class="text-gradient">Start Driving?</span>
    </h2>
    <p style="color:var(--t2);margin-bottom:32px">Create a free account and book your first vehicle today</p>
    <div class="d-flex gap-3 justify-content-center flex-wrap">
      <a href="../auth/register.php" class="btn btn-primary btn-lg"><i class="fa-solid fa-user-plus me-2"></i>Create Free Account</a>
      <a href="../pages/contact.php" class="btn btn-ghost btn-lg">Get in Touch</a>
    </div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
</body></html>
