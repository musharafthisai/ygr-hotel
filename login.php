<?php
session_start();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'staff') {
        header("Location: pages/daily_entry.php");
    } else {
        header("Location: pages/dashboard.php");
    }
    exit();
}

define('BASE_URL', (strpos($_SERVER['SCRIPT_NAME'] ?? '', '/yarahman') !== false) ? '/yarahman/' : '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>YGR signature — Sign in</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,700;1,800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background: linear-gradient(145deg, #b8dff5 0%, #a8d4ef 25%, #c9e8f7 45%, #e0f0fa 65%, #f0f8ff 100%);
      position: relative;
      overflow: hidden;
      perspective: 1200px;
    }

    body::before, body::after {
      content: '';
      position: absolute;
      border-radius: 50%;
      border: 1.5px solid rgba(255,255,255,0.35);
      pointer-events: none;
    }
    body::before {
      width: 820px; height: 820px;
      bottom: -260px; left: 50%;
      transform: translateX(-50%);
    }
    body::after {
      width: 620px; height: 620px;
      bottom: -200px; left: 50%;
      transform: translateX(-50%);
    }

    @keyframes drift-right {
      0%   { transform: translateX(0) translateY(0); }
      50%  { transform: translateX(-40px) translateY(-12px); }
      100% { transform: translateX(0) translateY(0); }
    }
    @keyframes drift-left {
      0%   { transform: translateX(0) translateY(0); }
      50%  { transform: translateX(44px) translateY(-8px); }
      100% { transform: translateX(0) translateY(0); }
    }
    @keyframes drift-slow {
      0%   { transform: translateX(0) translateY(0) scale(1); }
      33%  { transform: translateX(28px) translateY(-18px) scale(1.04); }
      66%  { transform: translateX(-20px) translateY(10px) scale(0.97); }
      100% { transform: translateX(0) translateY(0) scale(1); }
    }
    @keyframes drift-up {
      0%   { transform: translateY(0) translateX(0); }
      50%  { transform: translateY(-22px) translateX(16px); }
      100% { transform: translateY(0) translateX(0); }
    }
    @keyframes drift-wide {
      0%   { transform: translateX(0) scale(1); }
      40%  { transform: translateX(60px) scale(1.06); }
      100% { transform: translateX(0) scale(1); }
    }
    @keyframes pulse-opacity {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.7; }
    }
    @keyframes float-food {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-18px) rotate(2deg); }
    }

    .clouds {
      position: absolute; inset: 0; pointer-events: none; overflow: hidden;
    }
    .cloud {
      position: absolute;
      background: rgba(255,255,255,0.55);
      border-radius: 50%;
      filter: blur(38px);
      will-change: transform;
    }
    .cloud-1 {
      width: 400px; height: 190px;
      bottom: 20px; left: -60px;
      animation: drift-right 14s ease-in-out infinite, pulse-opacity 14s ease-in-out infinite;
    }
    .cloud-2 {
      width: 460px; height: 210px;
      bottom: 0px; right: -80px;
      animation: drift-left 18s ease-in-out infinite, pulse-opacity 18s ease-in-out 2s infinite;
    }
    .cloud-3 {
      width: 260px; height: 120px;
      bottom: 70px; left: 180px;
      filter: blur(30px);
      animation: drift-slow 22s ease-in-out infinite;
    }
    .cloud-4 {
      width: 200px; height: 100px;
      bottom: 55px; right: 200px;
      filter: blur(28px);
      animation: drift-up 16s ease-in-out 1s infinite, pulse-opacity 16s ease-in-out 1s infinite;
    }
    .cloud-5 {
      width: 300px; height: 130px;
      top: 40px; left: -40px;
      background: rgba(255,255,255,0.35);
      filter: blur(50px);
      animation: drift-wide 26s ease-in-out 3s infinite, pulse-opacity 26s ease-in-out 3s infinite;
    }
    .cloud-6 {
      width: 340px; height: 150px;
      top: 20px; right: -50px;
      background: rgba(255,255,255,0.32);
      filter: blur(50px);
      animation: drift-left 20s ease-in-out 5s infinite, pulse-opacity 20s ease-in-out 5s infinite;
    }
    .cloud-7 {
      width: 500px; height: 200px;
      top: 35%; left: 50%;
      transform: translateX(-50%);
      background: rgba(255,255,255,0.18);
      filter: blur(70px);
      animation: drift-slow 30s ease-in-out 7s infinite;
    }

    /* ── Floating Food Images (3D Parallax) ───────────── */
    .food-float {
      position: fixed;
      pointer-events: none;
      z-index: 1;
      will-change: transform;
      transition: transform 0.1s ease-out;
    }
    .food-float img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: contain;
      filter: drop-shadow(0 20px 40px rgba(0,0,0,0.12));
      animation: float-food 6s ease-in-out infinite;
    }
    .food-1 {
      width: 130px; height: 130px;
      top: 12%; left: 6%;
      animation: float-food 7s ease-in-out 0s infinite;
    }
    .food-2 {
      width: 160px; height: 160px;
      bottom: 15%; right: 5%;
      animation: float-food 8s ease-in-out 1s infinite;
    }
    .food-3 {
      width: 110px; height: 110px;
      top: 50%; left: 3%;
      transform: translateY(-50%);
      animation: float-food 6.5s ease-in-out 0.5s infinite;
    }
    .food-4 {
      width: 140px; height: 140px;
      top: 30%; right: 3%;
      animation: float-food 7.5s ease-in-out 1.5s infinite;
    }
    .food-5 {
      width: 120px; height: 120px;
      top: 18%; right: 6%;
      animation: float-food 9s ease-in-out 2s infinite;
    }
    .food-6 {
      width: 120px; height: 120px;
      bottom: 40%; left: 8%;
      animation: float-food 8s ease-in-out 1s infinite;
    }

    /* ── Cloud PNG Decorations ────────────────────────── */
    .cloud-png {
      position: fixed;
      pointer-events: none;
      z-index: 0;
      opacity: 0.7;
      will-change: transform;
    }
    .cloud-png img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: contain;
    }
    .cloud-png-1 {
      width: 260px;
      top: 8%;
      left: 2%;
      animation: drift-right 20s ease-in-out infinite;
    }
    .cloud-png-4 {
      width: 240px;
      bottom: 30%;
      right: 1%;
      opacity: 0.45;
      animation: drift-left 28s ease-in-out 5s infinite;
    }

    /* ── Card ─────────────────────────────────────────── */
    .card {
      position: relative;
      z-index: 10;
      width: 440px;
      max-width: calc(100vw - 32px);
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(32px) saturate(1.8);
      -webkit-backdrop-filter: blur(32px) saturate(1.8);
      border-radius: 32px;
      padding: 48px 40px 40px;
      border: 1px solid rgba(255,255,255,0.4);
      box-shadow:
        0 30px 80px rgba(100,160,210,0.25),
        0 10px 30px rgba(100,160,210,0.12),
        inset 0 1px 0 rgba(255,255,255,0.6);
      animation: cardIn .5s cubic-bezier(.22,1,.36,1) both;
      overflow: hidden;
      transform-style: preserve-3d;
    }

    .card::before {
      content: '';
      position: absolute;
      inset: -2px;
      border-radius: 34px;
      background: linear-gradient(135deg, rgba(255,255,255,0.5), rgba(255,255,255,0.05), rgba(255,255,255,0.3));
      z-index: -1;
      pointer-events: none;
    }

    .card-shine {
      position: absolute;
      inset: 0;
      border-radius: 32px;
      background: linear-gradient(105deg, transparent 30%, rgba(255,255,255,0.25) 45%, rgba(255,255,255,0.4) 50%, transparent 65%);
      background-size: 300% 100%;
      animation: shineSweep 6s ease-in-out infinite;
      pointer-events: none;
    }

    @keyframes shineSweep {
      0% { background-position: 200% 0; }
      40% { background-position: -80% 0; }
      100% { background-position: -80% 0; }
    }

    @keyframes cardIn {
      from { opacity: 0; transform: translateY(30px) scale(.95); }
      to { opacity: 1; transform: none; }
    }

    .login-brand {
      display: flex; flex-direction: column; align-items: center;
      margin-bottom: 28px; position: relative;
    }
    .login-logo-wrap {
      width: 72px; height: 72px;
      background: #000;
      border-radius: 22px;
      display: flex; align-items: center; justify-content: center;
      box-shadow: 0 8px 32px rgba(0,0,0,0.15);
      margin-bottom: 16px;
      position: relative;
    }
    .login-logo-wrap::after {
      content: '';
      position: absolute;
      inset: -3px;
      border-radius: 25px;
      background: linear-gradient(135deg, rgba(255,255,255,0.3), transparent, rgba(255,255,255,0.15));
      z-index: -1;
    }
    .login-logo-wrap img {
      height: 40px; width: auto;
      filter: brightness(0) invert(1);
    }
    .login-title {
      font-family: 'Playfair Display', serif;
      font-size: 30px; font-weight: 800; font-style: italic;
      color: #000;
      letter-spacing: -0.3px;
      text-align: center;
    }
    .login-sub {
      font-family: 'DM Sans', sans-serif;
      font-size: 14px; font-weight: 500;
      color: rgba(0,0,0,0.5);
      text-align: center;
      margin-top: 4px;
    }

    .alert {
      border-radius: 12px; padding: 12px 14px;
      font-size: 13px; font-weight: 500;
      margin-bottom: 16px; text-align: center;
      backdrop-filter: blur(8px);
    }
    .alert-error   { background: rgba(220,38,38,0.1); color: #b91c1c; border: 1px solid rgba(220,38,38,0.2); }
    .alert-success { background: rgba(22,163,74,0.1); color: #15803d; border: 1px solid rgba(22,163,74,0.2); }

    .form-group { position: relative; margin-bottom: 12px; }
    .form-group .icon {
      position: absolute; left: 16px; top: 50%;
      transform: translateY(-50%);
      color: rgba(0,0,0,0.3);
      display: flex; align-items: center;
      pointer-events: none;
      transition: color .25s ease;
    }
    .form-group:focus-within .icon { color: rgba(0,0,0,0.6); }
    .form-group .icon svg { width: 18px; height: 18px; }

    input[type="password"],
    input[type="text"] {
      width: 100%; height: 52px;
      background: rgba(255,255,255,0.5);
      border: 1.5px solid rgba(255,255,255,0.5);
      border-radius: 14px;
      padding: 0 44px 0 44px;
      font-size: 14px; font-family: 'DM Sans', sans-serif;
      color: #1a1a1a;
      outline: none;
      transition: all .25s ease;
      backdrop-filter: blur(4px);
    }
    input::placeholder { color: rgba(0,0,0,0.3); }
    input:focus {
      background: rgba(255,255,255,0.7);
      border-color: rgba(255,255,255,0.8);
      box-shadow: 0 0 0 4px rgba(255,255,255,0.15);
    }

    .toggle-pw {
      position: absolute; right: 16px; top: 50%;
      transform: translateY(-50%);
      background: none; border: none;
      cursor: pointer; padding: 6px;
      color: rgba(0,0,0,0.3);
      display: flex; align-items: center;
      transition: color .25s ease;
      border-radius: 8px;
    }
    .toggle-pw:hover { color: rgba(0,0,0,0.6); }
    .toggle-pw svg { width: 18px; height: 18px; }

    .forgot-row { text-align: right; margin-bottom: 20px; margin-top: 4px; }
    .forgot-row a {
      font-size: 13px; font-weight: 500;
      color: rgba(0,0,0,0.4);
      text-decoration: none;
      transition: color .25s ease;
    }
    .forgot-row a:hover { color: #000; }

    .btn-submit {
      width: 100%; height: 52px;
      background: #000;
      color: #fff; border: none;
      border-radius: 14px;
      font-size: 15px; font-weight: 600;
      font-family: 'DM Sans', sans-serif;
      cursor: pointer;
      letter-spacing: 0.3px;
      transition: all .3s ease;
      box-shadow: 0 8px 24px rgba(0,0,0,0.3);
      position: relative; overflow: hidden;
    }
    .btn-submit::before {
      content: '';
      position: absolute; inset: 0;
      background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
      opacity: 0; transition: opacity .3s ease;
    }
    .btn-submit:hover::before { opacity: 1; }
    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 32px rgba(0,0,0,0.4);
    }
    .btn-submit:active { transform: translateY(0) scale(.98); }

    .corner-btn {
      position: fixed;
      top: 20px;
      z-index: 100;
    }
    .corner-btn a {
      display: inline-block;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      padding: 10px 18px;
      border-radius: 12px;
      transition: all .25s ease;
      backdrop-filter: blur(16px) saturate(1.8);
      -webkit-backdrop-filter: blur(16px) saturate(1.8);
      border: 1px solid rgba(255,255,255,0.35);
      box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }
    .corner-btn-left {
      left: 20px;
    }
    .corner-btn-left a {
      color: rgba(0,0,0,0.7);
      background: rgba(255,255,255,0.25);
    }
    .corner-btn-left a:hover {
      background: rgba(255,255,255,0.45);
      color: #000;
      box-shadow: 0 6px 24px rgba(0,0,0,0.12);
    }
    .corner-btn-right {
      right: 20px;
    }
    .corner-btn-right a {
      color: #fff;
      background: rgba(37, 211, 102, 0.35);
      border-color: rgba(255,255,255,0.25);
    }
    .corner-btn-right a:hover {
      background: rgba(37, 211, 102, 0.55);
      transform: translateY(-1px);
      box-shadow: 0 6px 24px rgba(37, 211, 102, 0.25);
    }

    @media (max-width: 768px) {
      .food-float, .cloud-png { display: none; }
      .card { padding: 36px 24px 32px; }
    }
  </style>
</head>
<body>
  <div class="corner-btn corner-btn-left">
    <a href="<?= BASE_URL ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Back
    </a>
  </div>
  <div class="corner-btn corner-btn-right">
    <a href="https://wa.me/919150802906" target="_blank">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      Support
    </a>
  </div>

  <div class="clouds" aria-hidden="true">
    <div class="cloud cloud-1"></div>
    <div class="cloud cloud-2"></div>
    <div class="cloud cloud-3"></div>
    <div class="cloud cloud-4"></div>
    <div class="cloud cloud-5"></div>
    <div class="cloud cloud-6"></div>
    <div class="cloud cloud-7"></div>
  </div>

  <!-- Floating Food Images -->
  <div class="food-float food-1" data-depth="0.04">
    <img src="assets/img/hero.png" alt="" loading="lazy">
  </div>
  <div class="food-float food-2" data-depth="-0.05">
    <img src="assets/img/mutton.png" alt="" loading="lazy">
  </div>
  <div class="food-float food-3" data-depth="0.03">
    <img src="assets/img/65.png" alt="" loading="lazy">
  </div>
  <div class="food-float food-4" data-depth="-0.04">
    <img src="assets/img/chicken.png" alt="" loading="lazy">
  </div>
  <div class="food-float food-5" data-depth="0.05">
    <img src="assets/img/chiken blast.png" alt="" loading="lazy">
  </div>
  <div class="food-float food-6" data-depth="-0.03">
    <img src="assets/img/chiken blast.png" alt="" loading="lazy">
  </div>

  <div class="cloud-png cloud-png-1">
    <img src="assets/img/—Pngtree—floating realistic clouds_8623463.png" alt="" loading="lazy">
  </div>
  <div class="cloud-png cloud-png-4">
    <img src="assets/img/—Pngtree—single cloud on a transparent_18736825.png" alt="" loading="lazy">
  </div>

  <div class="card">
    <div class="card-shine"></div>
    <div class="login-brand">
      <div class="login-logo-wrap">
        <img src="assets/img/logo.png" alt="YGR">
      </div>
      <div class="login-title">YGR signature</div>
      <div class="login-sub">Sign in to manage your restaurant</div>
    </div>

    <?php if(isset($_GET['error'])): ?>
      <div class="alert alert-error" role="alert">
        <?php
          if($_GET['error'] === 'invalid') echo "Invalid username or password.";
          elseif($_GET['error'] === 'empty') echo "Please fill in all fields.";
          elseif($_GET['error'] === 'inactive') echo "Account is inactive. Contact Admin.";
        ?>
      </div>
    <?php endif; ?>
    <?php if(isset($_GET['timeout'])): ?>
      <div class="alert alert-error" style="background:rgba(245,158,11,0.1);border-color:rgba(245,158,11,0.2);color:#92400e;" role="alert">
        Session expired. Please log in again.
      </div>
    <?php endif; ?>
    <?php if(isset($_GET['msg']) && $_GET['msg'] === 'pw_updated'): ?>
      <div class="alert alert-success" role="alert">Password updated successfully. Please log in.</div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>auth/login.php" novalidate>
      <div class="form-group">
        <span class="icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="3"/>
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
        </span>
        <input type="text" name="username" id="username" placeholder="Username" required autocomplete="username" />
      </div>

      <div class="form-group">
        <span class="icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
        </span>
        <input type="password" name="password" id="password" placeholder="Password" required autocomplete="current-password" />
        <button type="button" class="toggle-pw" onclick="togglePassword()" aria-label="Toggle password visibility">
          <svg id="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
            <line x1="1" y1="1" x2="23" y2="23"/>
          </svg>
          <svg id="eye-on" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
          </svg>
        </button>
      </div>

      <div class="forgot-row">
        <a href="<?= BASE_URL ?>forgot_password.php">Forgot password?</a>
      </div>

      <button type="submit" class="btn-submit">Sign in</button>
    </form>
  </div>

  <script>
    function togglePassword() {
      const input  = document.getElementById('password');
      const eyeOff = document.getElementById('eye-off');
      const eyeOn  = document.getElementById('eye-on');
      if (input.type === 'password') {
        input.type   = 'text';
        eyeOff.style.display = 'none';
        eyeOn.style.display  = 'block';
      } else {
        input.type   = 'password';
        eyeOff.style.display = 'block';
        eyeOn.style.display  = 'none';
      }
    }

    // ── 3D Mouse Parallax ──
    document.addEventListener('mousemove', function(e) {
      const x = (e.clientX / window.innerWidth - 0.5) * 2;
      const y = (e.clientY / window.innerHeight - 0.5) * 2;

      // Card 3D tilt
      const card = document.querySelector('.card');
      if (card) {
        card.style.transform =
          'perspective(1200px) rotateY(' + (x * 4) + 'deg) rotateX(' + (-y * 4) + 'deg)';
      }

      // Food images parallax
      document.querySelectorAll('.food-float').forEach(function(el) {
        const depth = parseFloat(el.getAttribute('data-depth')) || 0.03;
        const moveX = x * 30 * depth * 10;
        const moveY = y * 30 * depth * 10;
        const current = el.style.transform || '';
        if (!current.includes('translate')) {
          el.style.transform = 'translate(' + moveX + 'px, ' + moveY + 'px)';
        } else {
          el.style.transform = 'translate(' + moveX + 'px, ' + moveY + 'px)';
        }
      });

      // Cloud PNGs parallax
      document.querySelectorAll('.cloud-png').forEach(function(el, i) {
        const factor = i === 0 ? 0.015 : -0.02;
        el.style.transform = 'translate(' + (x * 40 * factor * 10) + 'px, ' + (y * 20 * factor * 10) + 'px)';
      });
    });
  </script>
</body>
</html>
