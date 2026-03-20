<?php
session_start();
$root = '';
include 'config/db.php';
include 'includes/track_visit.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'includes/head.php'; ?>
  <title>DriveEase — Premium Vehicle Rental</title>
  <style>
    .hero-particles .particle { position:absolute; border-radius:50%; opacity:0; animation:particleFloat linear infinite; }
    @keyframes particleFloat { 0%{transform:translateY(100vh);opacity:0} 10%{opacity:.5} 90%{opacity:.2} 100%{transform:translateY(-80px) translateX(20px);opacity:0} }
  </style>
</head>
<body>

<?php include 'includes/navbar_public.php'; ?>

<!-- HERO -->
<section class="hero">
  <div class="hero-aurora"></div>
  <div class="hero-purple-blob"></div>
  <div class="hero-bg-img"></div>
  <div class="hero-overlay"></div>
  <div class="hero-grid"></div>
  <!-- Particles -->
  <div class="hero-particles" id="heroParticles"></div>
  <div class="hero-content">
    <div class="hero-eyebrow"><span class="hero-dot"></span>Now available in 50+ cities across India</div>
    <h1>Drive Your <span class="text-gradient">Dream</span><br><span class="text-gradient-cyan">On Your Terms</span></h1>
    <p class="hero-sub">Premium vehicles · Instant booking · Transparent pricing<br>The smartest way to rent a vehicle in India.</p>
    <div class="hero-actions">
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="user/vehicles.php" class="btn btn-primary btn-lg"><i class="fa-solid fa-car-side"></i> Browse Vehicles</a>
        <a href="user/my-bookings.php" class="btn btn-neon btn-lg">My Bookings</a>
      <?php else: ?>
        <a href="auth/register.php" class="btn btn-primary btn-lg"><i class="fa-solid fa-rocket"></i> Start Renting Free</a>
        <a href="auth/login.php" class="btn btn-neon btn-lg"><i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In</a>
      <?php endif; ?>
    </div>
    <div class="hero-stats mt-5">
      <div class="hero-stat"><span class="hero-stat-num">500+</span><span class="hero-stat-lbl">Vehicles</span></div>
      <div class="hero-stat"><span class="hero-stat-num">12K+</span><span class="hero-stat-lbl">Happy Renters</span></div>
      <div class="hero-stat"><span class="hero-stat-num">50+</span><span class="hero-stat-lbl">Cities</span></div>
      <div class="hero-stat"><span class="hero-stat-num">4.9★</span><span class="hero-stat-lbl">Avg Rating</span></div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="section">
  <div class="wrap">
    <div class="section-head">
      <p class="label-sm" style="color:var(--orange);margin-bottom:10px">Simple Process</p>
      <h2>How <span class="text-gradient">DriveEase</span> Works</h2>
      <p>Rent a vehicle in under 2 minutes. No paperwork, no hassle.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-3 col-sm-6"><div class="step-card fade-up fade-up-1"><div class="step-num">01</div><h4>Create Account</h4><p>Sign up in seconds with your email — no credit card required.</p></div></div>
      <div class="col-md-3 col-sm-6"><div class="step-card fade-up fade-up-2"><div class="step-num">02</div><h4>Pick a Vehicle</h4><p>Browse our fleet — filter by type, price, and brand.</p></div></div>
      <div class="col-md-3 col-sm-6"><div class="step-card fade-up fade-up-3"><div class="step-num">03</div><h4>Choose Dates</h4><p>Select your rental period with our live availability calendar.</p></div></div>
      <div class="col-md-3 col-sm-6"><div class="step-card fade-up fade-up-4"><div class="step-num">04</div><h4>Pay &amp; Drive</h4><p>Pay securely via Razorpay and get confirmed instantly.</p></div></div>
    </div>
  </div>
</section>

<div class="divider-glow"></div>

<!-- POPULAR VEHICLES -->
<section class="section">
  <div class="wrap">
    <div class="section-head">
      <p class="label-sm" style="color:var(--orange);margin-bottom:10px">Our Fleet</p>
      <h2>Popular <span class="text-gradient-cyan">Vehicles</span></h2>
      <p>Hand-picked, well-maintained — ready for your next adventure</p>
    </div>
    <div class="row g-4">
      <?php
      $q = mysqli_query($conn, "SELECT * FROM vehicles LIMIT 6");
      while($v = mysqli_fetch_assoc($q)):
      ?>
      <div class="col-md-4 col-sm-6 v-card-wrap" data-cat="<?= strtolower($v['brand']) ?>">
        <div class="v-card">
          <div class="v-card-img">
            <span class="v-card-badge"><?= htmlspecialchars($v['status'] ?? 'Available') ?></span>
            <img src="assets/images/<?= htmlspecialchars($v['image']) ?>" alt="<?= htmlspecialchars($v['name']) ?>">
          </div>
          <div class="v-card-body">
            <div class="v-card-brand"><?= htmlspecialchars($v['brand']) ?></div>
            <div class="v-card-name"><?= htmlspecialchars($v['name']) ?></div>
            <div class="v-card-price">
              <span class="amount">₹<?= number_format($v['price_per_day']) ?></span>
              <span class="per">/ day</span>
            </div>
            <div class="v-card-features">
              <span class="v-feat">✓ Insured</span>
              <span class="v-feat">✓ Verified</span>
              <span class="v-feat">✓ 24/7 Support</span>
            </div>
            <div class="v-card-footer">
              <?php if(isset($_SESSION['user_id'])): ?>
                <a href="user/vehicles.php" class="btn btn-primary btn-sm" style="width:100%"><i class="fa-regular fa-calendar"></i> Book Now</a>
              <?php else: ?>
                <a href="auth/login.php" class="btn btn-ghost btn-sm" style="width:100%"><i class="fa-solid fa-lock me-1"></i> Login to Book</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
    <div class="text-center mt-5">
      <a href="<?= isset($_SESSION['user_id']) ? 'user/vehicles.php' : 'auth/login.php' ?>" class="btn btn-ghost btn-lg">View All Vehicles <i class="fa-solid fa-arrow-right ms-2"></i></a>
    </div>
  </div>
</section>

<!-- WHY CHOOSE US -->
<section class="section" style="background:var(--bg-1)">
  <div class="wrap">
    <div class="section-head">
      <p class="label-sm" style="color:var(--cyan);margin-bottom:10px">Why DriveEase</p>
      <h2>Built Around <span class="text-gradient-wild">Your Journey</span></h2>
      <p>Every feature designed to make your rental experience seamless</p>
    </div>
    <div class="row g-4">
      <div class="col-md-3 col-sm-6"><div class="feat-card"><div class="feat-icon">💳</div><h4>Secure Payments</h4><p>Razorpay-powered checkout with UPI, cards, netbanking & wallets.</p></div></div>
      <div class="col-md-3 col-sm-6"><div class="feat-card"><div class="feat-icon">🚗</div><h4>Premium Fleet</h4><p>Every vehicle is inspected, insured, and ready to go.</p></div></div>
      <div class="col-md-3 col-sm-6"><div class="feat-card"><div class="feat-icon">⚡</div><h4>Instant Booking</h4><p>Book in under 2 minutes with real-time availability.</p></div></div>
      <div class="col-md-3 col-sm-6"><div class="feat-card"><div class="feat-icon">📧</div><h4>Email Confirmation</h4><p>Automatic booking confirmation and invoice to your inbox.</p></div></div>
      <div class="col-md-3 col-sm-6"><div class="feat-card"><div class="feat-icon">📄</div><h4>PDF Invoice</h4><p>Download or print your invoice anytime from My Bookings.</p></div></div>
      <div class="col-md-3 col-sm-6"><div class="feat-card"><div class="feat-icon">📞</div><h4>24/7 Support</h4><p>Round-the-clock assistance via phone, email, and chat.</p></div></div>
      <div class="col-md-3 col-sm-6"><div class="feat-card"><div class="feat-icon">📍</div><h4>50+ Cities</h4><p>Available across major metros and Tier-2 cities in India.</p></div></div>
      <div class="col-md-3 col-sm-6"><div class="feat-card"><div class="feat-icon">🔒</div><h4>Verified Fleet</h4><p>All vehicles are RC-verified, insured, and regularly serviced.</p></div></div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="section">
  <div class="wrap">
    <div class="section-head">
      <p class="label-sm" style="color:var(--orange);margin-bottom:10px">Testimonials</p>
      <h2>What Our <span class="text-gradient">Customers Say</span></h2>
      <p>Real reviews from thousands of happy renters</p>
    </div>
    <div id="testiCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <div class="testi-card">
            <div class="testi-quote">"</div>
            <div class="testi-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
            <p class="testi-text">Absolutely seamless experience! Booked a Hyundai Creta for a weekend trip to Coorg. The car was spotless, and the invoice was in my inbox within minutes of payment.</p>
            <div class="testi-name">Varun Hiremath</div><div class="testi-role">Software Engineer · Bengaluru</div>
          </div>
        </div>
        <div class="carousel-item">
          <div class="testi-card">
            <div class="testi-quote">"</div>
            <div class="testi-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
            <p class="testi-text">The pricing is super transparent and the Razorpay checkout was instant. I loved seeing the price calculator update as I picked dates. Will definitely use again!</p>
            <div class="testi-name">Sagar Jamkhandi</div><div class="testi-role">Product Designer · Mumbai</div>
          </div>
        </div>
        <div class="carousel-item">
          <div class="testi-card">
            <div class="testi-quote">"</div>
            <div class="testi-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
            <p class="testi-text">Best vehicle rental platform I've used. Booking a Royal Enfield for my Manali trip was so easy — blocked dates showed clearly and support was super responsive.</p>
            <div class="testi-name">Sammed C</div><div class="testi-role">Entrepreneur · Delhi</div>
          </div>
        </div>
      </div>
      <div class="carousel-indicators" style="bottom:-40px">
        <button type="button" data-bs-target="#testiCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#testiCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#testiCarousel" data-bs-slide-to="2"></button>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section" style="background:var(--bg-1);position:relative;overflow:hidden">
  <div style="position:absolute;inset:0;background:radial-gradient(ellipse 60% 60% at 50% 50%,rgba(249,115,22,.06),transparent);pointer-events:none"></div>
  <div class="wrap text-center" style="position:relative;z-index:1">
    <p class="label-sm" style="color:var(--orange);margin-bottom:14px">Ready to Ride?</p>
    <h2 style="font-size:clamp(1.8rem,4vw,3rem);font-weight:800;letter-spacing:-.03em;margin-bottom:16px">Your next journey is<br><span class="text-gradient-wild">one click away</span></h2>
    <p style="color:var(--t2);margin-bottom:36px;font-size:1rem">Join 12,000+ renters who trust DriveEase</p>
    <div class="d-flex gap-3 justify-content-center flex-wrap">
      <a href="auth/register.php" class="btn btn-primary btn-lg"><i class="fa-solid fa-user-plus"></i> Create Free Account</a>
      <a href="pages/about.php" class="btn btn-neon btn-lg">Learn More</a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>
<script>
// Particle generator
(function(){
  const c = document.getElementById('heroParticles');
  if(!c) return;
  const colors = ['var(--orange)','var(--cyan)','var(--purple-soft)'];
  for(let i=0;i<25;i++){
    const p = document.createElement('div');
    p.className = 'particle';
    const size = Math.random()*3+1;
    p.style.cssText = `
      left:${Math.random()*100}%;
      width:${size}px;height:${size}px;
      background:${colors[i%3]};
      animation-duration:${Math.random()*15+8}s;
      animation-delay:${Math.random()*10}s;
    `;
    c.appendChild(p);
  }
})();
</script>
</body>
</html>
