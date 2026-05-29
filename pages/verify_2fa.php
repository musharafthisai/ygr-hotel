<?php
// 2FA Verification Page - Premium
require_once '../auth/session.php';
require_once '../config/db.php';
require_once '../config/constants.php';
require_once '../vendor/RobThree/TwoFactorAuth.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT totp_secret FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || empty($user['totp_secret'])) {
    header('Location: dashboard.php');
    exit;
}

$tfa = new RobThree\Auth\TwoFactorAuth(APP_NAME);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
    $code = $_POST['code'];
    if ($tfa->verifyCode($user['totp_secret'], $code)) {
        $_SESSION['2fa_verified'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid code. Please try again.';
    }
}

define('BASE_URL', (strpos($_SERVER['SCRIPT_NAME'] ?? '', '/yarahman') !== false) ? '/yarahman/' : '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify 2FA</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/premium.css">
    <style>
        .auth-page { min-height:100vh; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,var(--color-primary)0%,var(--color-primary-light)100%); padding:20px; }
        .auth-card { width:100%; max-width:420px; background:white; border-radius:var(--radius-xl); padding:40px; box-shadow:0 20px 60px rgba(0,0,0,0.2); animation:fadeIn 0.5s ease; }
        .code-input-group { display:flex; gap:8px; justify-content:center; margin:16px 0; }
        .code-input-group input { width:48px; height:56px; text-align:center; font-size:1.5rem; font-weight:700; border:2px solid var(--color-border); border-radius:var(--radius-md); font-family:var(--font-display); outline:none; transition:all var(--transition-fast); }
        .code-input-group input:focus { border-color:var(--color-primary); box-shadow:0 0 0 3px rgba(45,80,22,0.1); }
        .pulse { animation:pulse 2s infinite; }
        @keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:0.5; } }
    </style>
</head>
<body class="auth-page">
    <div class="auth-card">
        <div style="text-align:center;margin-bottom:28px;">
            <div style="width:56px;height:56px;background:rgba(45,80,22,0.06);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <i class="ti ti-shield-lock" style="font-size:1.75rem;color:var(--color-primary);"></i>
            </div>
            <h1 class="login-heading" style="font-size:1.5rem;">Two-Factor Auth</h1>
            <p class="login-subheading">Enter the 6-digit code from Google Authenticator</p>
        </div>
        <?php if ($error): ?>
            <div style="display:flex;align-items:center;gap:8px;padding:12px 16px;background:rgba(220,38,38,0.06);border:1px solid rgba(220,38,38,0.2);border-radius:var(--radius-md);margin-bottom:20px;animation:slideDown 0.3s ease;">
                <i class="ti ti-alert-circle" style="color:var(--color-danger);flex-shrink:0;"></i>
                <span style="font-size:0.875rem;color:var(--color-danger);"><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>
        <form method="post" onsubmit="return validate2FA()">
            <div class="code-input-group" id="codeInputs">
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" autofocus oninput="moveNext(this,0)" onkeydown="movePrev(this,event)" class="code-digit">
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" oninput="moveNext(this,1)" onkeydown="movePrev(this,event)" class="code-digit">
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" oninput="moveNext(this,2)" onkeydown="movePrev(this,event)" class="code-digit">
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" oninput="moveNext(this,3)" onkeydown="movePrev(this,event)" class="code-digit">
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" oninput="moveNext(this,4)" onkeydown="movePrev(this,event)" class="code-digit">
                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" oninput="moveNext(this,5)" onkeydown="movePrev(this,event)" class="code-digit">
            </div>
            <input type="hidden" name="code" id="fullCode">
            <button type="submit" class="btn btn-primary btn-block mt-md" style="font-family:'Syne',sans-serif;height:50px;font-size:1rem;" id="submitBtn">
                <i class="ti ti-shield-check"></i> Verify
            </button>
        </form>
        <div style="text-align:center;margin-top:20px;">
            <a href="logout.php" style="font-size:0.8125rem;color:var(--color-text-muted);text-decoration:none;">
                <i class="ti ti-logout" style="vertical-align:middle;"></i> Sign out
            </a>
        </div>
    </div>
    <script src="<?= BASE_URL ?>assets/js/app.js"></script>
    <script>
        function moveNext(input, index) { if (input.value.length === 1) { const next = document.querySelectorAll('.code-digit')[index + 1]; if (next) next.focus(); } updateFullCode(); }
        function movePrev(input, e) { if (e.key === 'Backspace' && !input.value) { const prev = document.querySelectorAll('.code-digit')[Array.from(document.querySelectorAll('.code-digit')).indexOf(input) - 1]; if (prev) prev.focus(); } }
        function updateFullCode() { document.getElementById('fullCode').value = Array.from(document.querySelectorAll('.code-digit')).map(d => d.value).join(''); }
        function validate2FA() { const code = document.getElementById('fullCode').value; if (code.length !== 6) { showToast('Please enter all 6 digits', 'error'); return false; } return true; }
    </script>
</body>
</html>