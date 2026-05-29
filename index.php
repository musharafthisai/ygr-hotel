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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, user-scalable=no">
  <meta name="theme-color" content="#0D2818">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <title>YGR signature — Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link rel="manifest" href="/yarahman/manifest.json">
  <link rel="stylesheet" href="assets/css/mobile-app.css">
  <style>
    /* ── RESET ── */
    * { margin: 0; padding: 0; box-sizing: border-box; }

    /* ── PAGE ── */
    .login-page {
      position: relative;
      min-height: 100vh;
      min-height: 100dvh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      padding-bottom: calc(20px + env(safe-area-inset-bottom));
      background: #0a1f12;
      overflow: hidden;
    }

    /* ═══════════════════════════════════════════
       BACKGROUND IMAGE CAROUSEL
       ═══════════════════════════════════════════ */
    .bg-carousel {
      position: fixed;
      inset: 0;
      z-index: 0;
      overflow: hidden;
    }
    .bg-slide {
      position: absolute;
      inset: 0;
      background-size: cover;
      background-position: center;
      opacity: 0;
      transition: opacity 1.5s cubic-bezier(0.4, 0, 0.2, 1);
      will-change: opacity;
    }
    .bg-slide.active {
      opacity: 1;
    }
    .bg-slide::after {
      content: '';
      position: absolute;
      inset: 0;
      background:
        linear-gradient(135deg, rgba(10, 31, 18, 0.85) 0%, rgba(10, 31, 18, 0.45) 50%, rgba(10, 31, 18, 0.8) 100%);
    }

    /* ── QUOTE OVERLAY ON BACKGROUND ── */
    .bg-quote {
      position: fixed;
      bottom: 60px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 1;
      text-align: center;
      max-width: 700px;
      width: 90%;
      pointer-events: none;
    }
    .bg-quote-text {
      font-family: 'Playfair Display', serif;
      font-size: clamp(20px, 4vw, 36px);
      color: rgba(255,255,255,0.7);
      font-weight: 500;
      font-style: italic;
      text-shadow: 0 2px 20px rgba(0,0,0,0.5);
      min-height: 1.4em;
      transition: opacity 0.8s ease;
    }
    .bg-quote-author {
      font-family: 'DM Sans', sans-serif;
      font-size: clamp(11px, 1.5vw, 14px);
      color: rgba(255,255,255,0.35);
      margin-top: 8px;
      letter-spacing: 2px;
      text-transform: uppercase;
      font-weight: 600;
    }
    .bg-quote-text .typewriter-cursor {
      display: inline-block;
      width: 3px;
      height: 1em;
      background: rgba(255,255,255,0.6);
      margin-left: 2px;
      animation: blink 1s step-end infinite;
      vertical-align: text-bottom;
    }
    @keyframes blink {
      0%, 100% { opacity: 1; }
      50% { opacity: 0; }
    }

    /* ── CAROUSEL DOTS ── */
    .carousel-dots {
      position: fixed;
      bottom: 24px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 1;
      display: flex;
      gap: 8px;
      pointer-events: none;
    }
    .carousel-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: rgba(255,255,255,0.2);
      transition: all 0.6s ease;
    }
    .carousel-dot.active {
      width: 28px;
      border-radius: 4px;
      background: rgba(255,255,255,0.6);
    }

    /* ── FLOATING ORBS ── */
    .orb {
      position: fixed;
      border-radius: 50%;
      filter: blur(80px);
      opacity: 0.5;
      pointer-events: none;
      z-index: 0;
    }
    .orb-1 {
      width: 400px; height: 400px;
      background: rgba(251, 54, 64, 0.15);
      top: -120px; right: -120px;
      animation: orbFloat1 12s ease-in-out infinite;
    }
    .orb-2 {
      width: 350px; height: 350px;
      background: rgba(30, 123, 75, 0.12);
      bottom: -100px; left: -100px;
      animation: orbFloat2 14s ease-in-out infinite;
    }
    .orb-3 {
      width: 200px; height: 200px;
      background: rgba(251, 54, 64, 0.08);
      top: 50%; left: 50%;
      animation: orbFloat3 18s ease-in-out infinite;
    }
    @keyframes orbFloat1 {
      0%, 100% { transform: translate(0, 0) scale(1); }
      33% { transform: translate(40px, -40px) scale(1.1); }
      66% { transform: translate(-20px, 20px) scale(0.9); }
    }
    @keyframes orbFloat2 {
      0%, 100% { transform: translate(0, 0) scale(1); }
      33% { transform: translate(-40px, 30px) scale(1.15); }
      66% { transform: translate(30px, -20px) scale(0.85); }
    }
    @keyframes orbFloat3 {
      0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.08; }
      50% { transform: translate(-50%, -50%) scale(1.5); opacity: 0.15; }
    }

    /* ── FLOATING SHAPES (food icons) ── */
    .float-shape {
      position: fixed;
      color: rgba(255,255,255,0.06);
      font-size: 60px;
      pointer-events: none;
      z-index: 0;
      animation: shapeDrift 20s linear infinite;
    }
    .float-shape:nth-child(8) { top: 15%; left: 10%; animation-delay: 0s; animation-duration: 22s; }
    .float-shape:nth-child(9) { top: 70%; right: 8%; animation-delay: -5s; animation-duration: 18s; }
    .float-shape:nth-child(10) { top: 40%; right: 15%; animation-delay: -10s; animation-duration: 25s; }
    .float-shape:nth-child(11) { bottom: 20%; left: 20%; animation-delay: -15s; animation-duration: 20s; }
    .float-shape:nth-child(12) { top: 25%; left: 60%; animation-delay: -7s; animation-duration: 23s; }
    .float-shape:nth-child(13) { bottom: 35%; right: 35%; animation-delay: -12s; animation-duration: 19s; }
    @keyframes shapeDrift {
      0% { transform: translateY(0) rotate(0deg); opacity: 0.04; }
      25% { transform: translateY(-30px) rotate(5deg); opacity: 0.07; }
      50% { transform: translateY(10px) rotate(-3deg); opacity: 0.04; }
      75% { transform: translateY(-20px) rotate(4deg); opacity: 0.07; }
      100% { transform: translateY(0) rotate(0deg); opacity: 0.04; }
    }

    /* ── PARTICLE GRID ── */
    .particles {
      position: fixed;
      inset: 0;
      z-index: 0;
      pointer-events: none;
      overflow: hidden;
    }
    .particle {
      position: absolute;
      width: 3px;
      height: 3px;
      background: rgba(255,255,255,0.15);
      border-radius: 50%;
      animation: particleFloat linear infinite;
    }
    @keyframes particleFloat {
      0% { transform: translateY(100vh) scale(0); opacity: 0; }
      10% { opacity: 1; }
      90% { opacity: 1; }
      100% { transform: translateY(-10vh) scale(1); opacity: 0; }
    }

    /* ── FLOATING FOOD BADGES ── */
    .food-badge {
      position: fixed;
      z-index: 0;
      pointer-events: none;
      background: rgba(255,255,255,0.06);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 100px;
      padding: 8px 18px;
      display: flex;
      align-items: center;
      gap: 8px;
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      font-weight: 600;
      color: rgba(255,255,255,0.5);
      animation: badgeFloat 6s ease-in-out infinite;
    }
    .food-badge i { font-size: 18px; }
    .badge-1 { top: 12%; right: 8%; animation-delay: 0s; }
    .badge-2 { top: 50%; left: 6%; animation-delay: -2s; }
    .badge-3 { bottom: 25%; right: 12%; animation-delay: -4s; }
    @keyframes badgeFloat {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-15px) rotate(2deg); }
    }

    /* ── LOGIN CARD ── */
    .login-card {
      width: 100%;
      max-width: 420px;
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(24px) saturate(180%);
      -webkit-backdrop-filter: blur(24px) saturate(180%);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 28px;
      padding: 36px 30px 32px;
      box-shadow:
        0 20px 60px rgba(0, 0, 0, 0.5),
        inset 0 1px 0 rgba(255, 255, 255, 0.15);
      position: relative;
      z-index: 1;
      animation: cardEntrance 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      transform-origin: center bottom;
    }
    @keyframes cardEntrance {
      0% { opacity: 0; transform: translateY(40px) scale(0.94); }
      100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    .login-card::before {
      content: '';
      position: absolute;
      inset: 0;
      border-radius: 28px;
      background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 50%, rgba(255,255,255,0.05) 100%);
      pointer-events: none;
    }
    .login-card::after {
      content: '';
      position: absolute;
      inset: -2px;
      border-radius: 30px;
      background: linear-gradient(135deg, rgba(251,54,64,0.15), transparent, rgba(30,123,75,0.15));
      z-index: -1;
      animation: cardBorderGlow 4s ease-in-out infinite;
    }
    @keyframes cardBorderGlow {
      0%, 100% { opacity: 0.5; }
      50% { opacity: 1; }
    }

    /* Card shine overlay */
    .card-shine {
      position: absolute;
      inset: 0;
      border-radius: 28px;
      background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.05) 45%, rgba(255,255,255,0.08) 50%, transparent 55%);
      background-size: 300% 100%;
      animation: shineSweep 8s ease-in-out infinite;
      pointer-events: none;
    }
    @keyframes shineSweep {
      0% { background-position: 100% 0; }
      40% { background-position: -100% 0; }
      100% { background-position: -100% 0; }
    }

    /* ── LOGO ── */
    .login-brand {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 28px;
      position: relative;
    }
    .login-logo-wrap {
      width: 68px;
      height: 68px;
      background: linear-gradient(135deg, #0D2818, #1A4A2E);
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 32px;
      color: white;
      box-shadow: 0 8px 24px rgba(13, 40, 24, 0.4);
      animation: logoPop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
      position: relative;
    }
    .login-logo-wrap::after {
      content: '';
      position: absolute;
      inset: -3px;
      border-radius: 23px;
      background: linear-gradient(135deg, rgba(251,54,64,0.3), transparent, rgba(30,123,75,0.3));
      z-index: -1;
      animation: logoGlow 3s ease-in-out infinite;
    }
    @keyframes logoGlow {
      0%, 100% { opacity: 0.5; }
      50% { opacity: 1; }
    }
    @keyframes logoPop {
      0% { transform: scale(0) rotate(-10deg); opacity: 0; }
      100% { transform: scale(1) rotate(0deg); opacity: 1; }
    }
    .login-title {
      font-family: 'Syne', sans-serif;
      font-size: 26px;
      font-weight: 800;
      color: white;
      margin-top: 14px;
      animation: fadeUp 0.5s ease 0.35s both;
      text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
    .login-sub {
      font-size: 14px;
      color: rgba(255,255,255,0.6);
      margin-top: 4px;
      animation: fadeUp 0.5s ease 0.45s both;
    }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* ── ERROR ── */
    .login-error {
      background: rgba(220, 38, 38, 0.15);
      border: 1px solid rgba(220, 38, 38, 0.25);
      border-radius: 12px;
      padding: 12px 14px;
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 18px;
      font-size: 13px;
      color: #FCA5A5;
      font-weight: 500;
      animation: shakeIn 0.4s ease;
    }
    @keyframes shakeIn {
      0% { transform: translateX(-10px); opacity: 0; }
      20% { transform: translateX(6px); }
      40% { transform: translateX(-4px); }
      60% { transform: translateX(2px); }
      80% { transform: translateX(-1px); }
      100% { transform: translateX(0); opacity: 1; }
    }

    /* ── FORM ── */
    .form-group {
      margin-bottom: 18px;
      animation: fadeUp 0.5s ease both;
    }
    .form-group:nth-child(1) { animation-delay: 0.5s; }
    .form-group:nth-child(2) { animation-delay: 0.6s; }
    .form-group:nth-child(3) { animation-delay: 0.7s; }
    .form-group:nth-child(4) { animation-delay: 0.8s; }

    .input-wrap {
      position: relative;
    }
    .input-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: rgba(255,255,255,0.35);
      font-size: 18px;
      pointer-events: none;
      transition: color 0.3s ease;
      z-index: 2;
    }
    .input-wrap:focus-within .input-icon {
      color: rgba(255,255,255,0.8);
    }

    .login-input {
      width: 100%;
      height: 52px;
      padding: 0 48px 0 46px;
      border: 2px solid rgba(255,255,255,0.12);
      border-radius: 14px;
      font-size: 15px;
      font-family: 'DM Sans', sans-serif;
      background: rgba(255,255,255,0.06);
      color: white;
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      outline: none;
      position: relative;
    }
    .login-input::placeholder {
      color: rgba(255,255,255,0.25);
      transition: opacity 0.3s ease;
    }
    .login-input:focus {
      border-color: rgba(255,255,255,0.35);
      background: rgba(255,255,255,0.1);
      box-shadow: 0 0 0 4px rgba(255,255,255,0.06);
    }
    .login-input:focus::placeholder {
      opacity: 0.1;
    }
    .login-input.error {
      border-color: rgba(220, 38, 38, 0.5);
      box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
    }

    /* Floating label */
    .input-label {
      position: absolute;
      left: 46px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 15px;
      font-family: 'DM Sans', sans-serif;
      color: rgba(255,255,255,0.35);
      pointer-events: none;
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      background: transparent;
      padding: 0 4px;
      z-index: 1;
    }
    .input-wrap.focused .input-label,
    .input-wrap.filled .input-label {
      top: -1px;
      transform: translateY(-50%) scale(0.82);
      font-size: 13px;
      color: rgba(255,255,255,0.7);
      background: rgba(13, 40, 24, 0.9);
      left: 14px;
      padding: 0 6px;
      border-radius: 4px;
    }

    .login-toggle {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: rgba(255,255,255,0.35);
      font-size: 20px;
      background: none;
      border: none;
      padding: 6px;
      border-radius: 8px;
      transition: all 0.2s ease;
      z-index: 2;
    }
    .login-toggle:hover {
      background: rgba(255,255,255,0.08);
      color: rgba(255,255,255,0.7);
    }
    .login-toggle:active {
      transform: translateY(-50%) scale(0.9);
    }

    /* ── REMEMBER ME ── */
    .remember-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .checkbox-wrap {
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      user-select: none;
    }
    .checkbox-wrap input {
      display: none;
    }
    .check-box {
      width: 22px;
      height: 22px;
      border: 2px solid rgba(255,255,255,0.2);
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
      flex-shrink: 0;
      position: relative;
      background: rgba(255,255,255,0.05);
    }
    .checkbox-wrap input:checked + .check-box {
      background: #0D2818;
      border-color: #0D2818;
      animation: checkPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .check-box i {
      font-size: 14px;
      color: white;
      opacity: 0;
      transform: scale(0);
      transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .checkbox-wrap input:checked + .check-box i {
      opacity: 1;
      transform: scale(1);
    }
    @keyframes checkPop {
      0% { transform: scale(1); }
      50% { transform: scale(1.15); }
      100% { transform: scale(1); }
    }
    .check-label {
      font-size: 14px;
      font-weight: 500;
      color: rgba(255,255,255,0.6);
    }

    .forgot-link {
      font-size: 13px;
      font-weight: 600;
      color: rgba(255,255,255,0.6);
      text-decoration: none;
      transition: all 0.2s ease;
      padding: 6px 8px;
      border-radius: 8px;
    }
    .forgot-link:hover {
      background: rgba(255,255,255,0.08);
      color: rgba(255,255,255,0.9);
    }

    /* ── BUTTON ── */
    .login-btn {
      width: 100%;
      height: 52px;
      border: none;
      border-radius: 14px;
      background: linear-gradient(135deg, #0D2818, #1A4A2E);
      color: white;
      font-family: 'Syne', sans-serif;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      position: relative;
      overflow: hidden;
      animation: fadeUp 0.5s ease 0.9s both;
    }
    .login-btn::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    .login-btn:hover::before {
      opacity: 1;
    }
    .login-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(13, 40, 24, 0.4);
    }
    .login-btn:active {
      transform: translateY(0) scale(0.98);
    }
    .login-btn:disabled {
      opacity: 0.7;
      transform: none;
      box-shadow: none;
      pointer-events: none;
    }

    /* Ripple */
    .ripple {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.35);
      transform: scale(0);
      animation: rippleAnim 0.6s ease-out;
      pointer-events: none;
    }
    @keyframes rippleAnim {
      to { transform: scale(4); opacity: 0; }
    }

    /* ── SPINNER ── */
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    .spinner {
      animation: spin 0.8s linear infinite;
    }

    /* ── SUCCESS STATE ── */
    .login-btn.success {
      background: #1E7B4B;
    }

    /* ── FOOTER ── */
    .login-footer {
      text-align: center;
      margin-top: 24px;
      padding-top: 18px;
      border-top: 1px solid rgba(255,255,255,0.08);
      animation: fadeUp 0.5s ease 1s both;
    }
    .login-footer a {
      font-size: 13px;
      font-weight: 600;
      color: rgba(255,255,255,0.5);
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s ease;
      padding: 6px 10px;
      border-radius: 8px;
    }
    .login-footer a:hover {
      background: rgba(255,255,255,0.08);
      color: rgba(255,255,255,0.8);
    }
    .login-footer a i {
      font-size: 15px;
    }

    /* ── MESSAGE ── */
    .msg-success {
      background: rgba(30, 123, 75, 0.15);
      border: 1px solid rgba(30, 123, 75, 0.25);
      border-radius: 12px;
      padding: 12px 14px;
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 18px;
      font-size: 13px;
      color: #86EFAC;
      font-weight: 500;
      animation: fadeUp 0.4s ease;
    }

    /* ── SCROLLBAR ── */
    ::-webkit-scrollbar { width: 3px; }
    ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 3px; }

    /* ── RESPONSIVE ── */
    @media (max-width: 480px) {
      .login-card {
        padding: 28px 20px 24px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.06);
      }
      .bg-quote {
        bottom: 80px;
      }
      .bg-quote-text {
        font-size: 16px;
      }
      .food-badge {
        display: none;
      }
    }
  </style>
</head>
<body>
  <div class="login-page">
    <!-- Background Image Carousel -->
    <div class="bg-carousel" id="bgCarousel">
      <div class="bg-slide active" style="background-image: url('<?php echo BASE_URL; ?>assets/img/slide1.jpg');"></div>
      <div class="bg-slide" style="background-image: url('<?php echo BASE_URL; ?>assets/img/slide2.jpg');"></div>
      <div class="bg-slide" style="background-image: url('<?php echo BASE_URL; ?>assets/img/slide3.jpg');"></div>
      <div class="bg-slide" style="background-image: url('<?php echo BASE_URL; ?>assets/img/slide4.jpg');"></div>
    </div>

    <!-- Orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <!-- Floating Food Icons -->
    <i class="ti ti-bowl-rice float-shape"></i>
    <i class="ti ti-flame float-shape"></i>
    <i class="ti ti-cookie float-shape"></i>
    <i class="ti ti-coffee float-shape"></i>
    <i class="ti ti-soup float-shape"></i>
    <i class="ti ti-apple float-shape"></i>

    <!-- Floating Food Badges -->
    <div class="food-badge badge-1"><i class="ti ti-star"></i> Chef's Special</div>
    <div class="food-badge badge-2"><i class="ti ti-flame"></i> Trending Now</div>
    <div class="food-badge badge-3"><i class="ti ti-heart"></i> Most Loved</div>

    <!-- Particles -->
    <div class="particles" id="particles"></div>

    <!-- Quote Overlay -->
    <div class="bg-quote">
      <div class="bg-quote-text" id="quoteText"></div>
      <div class="bg-quote-author" id="quoteAuthor"></div>
    </div>

    <!-- Carousel Dots -->
    <div class="carousel-dots" id="carouselDots">
      <span class="carousel-dot active"></span>
      <span class="carousel-dot"></span>
      <span class="carousel-dot"></span>
      <span class="carousel-dot"></span>
    </div>

    <!-- Card -->
    <div class="login-card" id="loginCard">
      <div class="card-shine"></div>
      <div class="login-brand">
        <div class="login-logo-wrap">
          <i class="ti ti-bowl-rice"></i>
        </div>
        <h1 class="login-title">YGR signature</h1>
        <p class="login-sub">Sign in to manage your restaurant</p>
      </div>

      <div id="errorContainer">
        <?php if(isset($_GET['error'])): ?>
          <div class="login-error">
            <i class="ti ti-alert-circle"></i>
            <?php
                if($_GET['error'] === 'invalid') echo "Invalid username or password.";
                elseif($_GET['error'] === 'empty') echo "Please fill in all fields.";
                elseif($_GET['error'] === 'inactive') echo "Account is inactive. Contact Admin.";
            ?>
          </div>
        <?php endif; ?>
        <?php if(isset($_GET['timeout'])): ?>
          <div class="login-error" style="background:rgba(245,158,11,0.1);border-color:rgba(245,158,11,0.2);color:#FCD34D;">
            <i class="ti ti-clock"></i>
            Session expired. Please log in again.
          </div>
        <?php endif; ?>
        <?php if(isset($_GET['msg']) && $_GET['msg'] === 'pw_updated'): ?>
          <div class="msg-success">
            <i class="ti ti-circle-check"></i>
            Password updated successfully. Please log in.
          </div>
        <?php endif; ?>
      </div>

      <form id="loginForm" method="POST" action="<?php echo BASE_URL; ?>auth/login.php" novalidate>
        <div class="form-group">
          <div class="input-wrap" id="usernameWrap">
            <i class="ti ti-user input-icon"></i>
            <label class="input-label" for="username">Username</label>
            <input type="text" class="login-input" id="username" name="username" autocomplete="username" required>
          </div>
        </div>

        <div class="form-group">
          <div class="input-wrap" id="passwordWrap">
            <i class="ti ti-lock input-icon"></i>
            <label class="input-label" for="password">Password</label>
            <input type="password" class="login-input" id="password" name="password" autocomplete="current-password" required>
            <button type="button" class="login-toggle" id="togglePass" tabindex="-1" aria-label="Toggle password visibility">
              <i class="ti ti-eye"></i>
            </button>
          </div>
        </div>

        <div class="form-group">
          <div class="remember-row">
            <label class="checkbox-wrap">
              <input type="checkbox" name="remember">
              <span class="check-box"><i class="ti ti-check"></i></span>
              <span class="check-label">Remember me</span>
            </label>
            <a href="<?php echo BASE_URL; ?>forgot_password.php" class="forgot-link">Forgot?</a>
          </div>
        </div>

        <button type="submit" class="login-btn" id="submitBtn">
          <span id="btnText">Sign in</span>
          <i class="ti ti-arrow-right" id="btnIcon"></i>
        </button>
      </form>

      <div class="login-footer">
        <a href="<?php echo BASE_URL; ?>forgot_password.php">
          <i class="ti ti-lock-question"></i> Forgot password?
        </a>
      </div>
    </div>
  </div>

  <script>
    // ═══════════════════════════════════════════
    // FOOD QUOTES & SLOGANS
    // ═══════════════════════════════════════════
    const foodQuotes = [
      { text: "Biryani is not just food, it's an emotion.", author: "Every Food Lover" },
      { text: "Life is short, make it biryani.", author: "Indian Kitchen" },
      { text: "Good food is the foundation of genuine happiness.", author: "Auguste Escoffier" },
      { text: "Spice up your life, one bite at a time.", author: "YGR signature Kitchen" },
      { text: "Where every grain tells a story of flavor.", author: "Chef's Special" },
      { text: " Happiness is a warm bowl of biryani.", author: "Food Philosophy" },
      { text: "Served with love, seasoned with tradition.", author: "YGR signature" },
      { text: "Taste the tradition, savor the moment.", author: "Royal Recipe" },
    ];

    // ═══════════════════════════════════════════
    // BACKGROUND IMAGE CAROUSEL
    // ═══════════════════════════════════════════
    (function initCarousel() {
      const slides = document.querySelectorAll('.bg-slide');
      const dots = document.querySelectorAll('.carousel-dot');
      const quoteEl = document.getElementById('quoteText');
      const authorEl = document.getElementById('quoteAuthor');
      let current = 0;
      let quoteIndex = 0;
      let isTyping = false;

      function typeQuote(quote) {
        if (isTyping) return;
        isTyping = true;
        quoteEl.innerHTML = '';
        authorEl.textContent = quote.author;
        let i = 0;
        const text = quote.text;
        function type() {
          if (i < text.length) {
            const char = text.charAt(i);
            const span = document.createElement('span');
            span.textContent = char;
            span.style.opacity = '0';
            span.style.animation = 'fadeIn 0.03s ease forwards';
            quoteEl.appendChild(span);
            i++;
            setTimeout(type, 35 + Math.random() * 20);
          } else {
            const cursor = document.createElement('span');
            cursor.className = 'typewriter-cursor';
            quoteEl.appendChild(cursor);
            isTyping = false;
          }
        }
        type();
      }

      function goToSlide(index) {
        slides.forEach((s, i) => s.classList.toggle('active', i === index));
        dots.forEach((d, i) => d.classList.toggle('active', i === index));
        current = index;
        quoteIndex = (quoteIndex + 1) % foodQuotes.length;
        typeQuote(foodQuotes[quoteIndex]);
      }

      // Initial quote
      typeQuote(foodQuotes[0]);

      // Auto-advance every 6s
      setInterval(() => {
        goToSlide((current + 1) % slides.length);
      }, 6000);
    })();

    // ═══════════════════════════════════════════
    // PARTICLES
    // ═══════════════════════════════════════════
    (function createParticles() {
      const c = document.getElementById('particles');
      for (let i = 0; i < 40; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        p.style.left = Math.random() * 100 + '%';
        p.style.width = p.style.height = (1 + Math.random() * 3) + 'px';
        p.style.animationDuration = (10 + Math.random() * 25) + 's';
        p.style.animationDelay = (Math.random() * 25) + 's';
        c.appendChild(p);
      }
    })();

    // ═══════════════════════════════════════════
    // MOUSE TRACKING ON CARD (subtle parallax)
    // ═══════════════════════════════════════════
    (function initCardParallax() {
      const card = document.getElementById('loginCard');
      card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = (e.clientX - rect.left) / rect.width - 0.5;
        const y = (e.clientY - rect.top) / rect.height - 0.5;
        card.style.transform =
          'perspective(1000px) rotateY(' + (x * 3) + 'deg) rotateX(' + (-y * 3) + 'deg)';
      });
      card.addEventListener('mouseleave', () => {
        card.style.transform = 'perspective(1000px) rotateY(0deg) rotateX(0deg)';
        card.style.transition = 'transform 0.6s ease';
        setTimeout(() => { card.style.transition = ''; }, 600);
      });
    })();

    // ═══════════════════════════════════════════
    // FLOATING LABELS
    // ═══════════════════════════════════════════
    function initFloatLabels() {
      document.querySelectorAll('.input-wrap').forEach(wrap => {
        const input = wrap.querySelector('.login-input');
        if (!input) return;
        input.addEventListener('focus', () => wrap.classList.add('focused'));
        input.addEventListener('blur', () => {
          wrap.classList.remove('focused');
          if (input.value.trim()) wrap.classList.add('filled');
          else wrap.classList.remove('filled');
        });
        if (input.value.trim()) wrap.classList.add('filled');
      });
    }
    initFloatLabels();

    // ═══════════════════════════════════════════
    // TOGGLE PASSWORD
    // ═══════════════════════════════════════════
    document.getElementById('togglePass').addEventListener('click', function() {
      const p = document.getElementById('password');
      const i = this.querySelector('i');
      if (p.type === 'password') {
        p.type = 'text';
        i.className = 'ti ti-eye-off';
      } else {
        p.type = 'password';
        i.className = 'ti ti-eye';
      }
    });

    // ═══════════════════════════════════════════
    // BUTTON RIPPLE
    // ═══════════════════════════════════════════
    document.getElementById('submitBtn').addEventListener('click', function(e) {
      const rect = this.getBoundingClientRect();
      const r = document.createElement('span');
      r.className = 'ripple';
      const size = Math.max(rect.width, rect.height);
      r.style.width = r.style.height = size + 'px';
      r.style.left = (e.clientX - rect.left - size / 2) + 'px';
      r.style.top = (e.clientY - rect.top - size / 2) + 'px';
      this.appendChild(r);
      setTimeout(() => r.remove(), 600);
    });

    // ═══════════════════════════════════════════
    // FORM SUBMIT
    // ═══════════════════════════════════════════
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      const u = document.getElementById('username');
      const p = document.getElementById('password');
      const btn = document.getElementById('submitBtn');
      const bt = document.getElementById('btnText');
      const bi = document.getElementById('btnIcon');
      const ec = document.getElementById('errorContainer');

      ec.innerHTML = '';
      let hasErr = false;

      if (!u.value.trim()) {
        u.classList.add('error');
        ec.innerHTML = '<div class="login-error"><i class="ti ti-alert-circle"></i> Please enter your username</div>';
        hasErr = true;
      } else {
        u.classList.remove('error');
      }

      if (!p.value) {
        p.classList.add('error');
        if (!hasErr) ec.innerHTML = '<div class="login-error"><i class="ti ti-alert-circle"></i> Please enter your password</div>';
        hasErr = true;
      } else {
        p.classList.remove('error');
      }

      if (hasErr) { e.preventDefault(); return; }

      btn.disabled = true;
      bt.textContent = 'Signing in...';
      bi.className = 'ti ti-loader spinner';
    });

    // ── INPUT ERROR REMOVAL ──
    document.querySelectorAll('.login-input').forEach(inp => {
      inp.addEventListener('input', function() {
        this.classList.remove('error');
      });
    });

    // ── SERVICE WORKER ──
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('/yarahman/sw.js').catch(() => {});
    }

    // ── INTERSECTION OBSERVER FOR QUOTES ──
    document.querySelectorAll('.bg-slide').forEach((slide, i) => {
      slide.addEventListener('transitionend', () => {
        // subtle
      });
    });
  </script>
</body>
</html>
