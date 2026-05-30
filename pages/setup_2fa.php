<?php
// 2FA Setup Page - Premium
require_once '../auth/session.php';
require_once '../config/db.php';
require_once '../config/constants.php';
require_once '../vendor/RobThree/TwoFactorAuth.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, totp_secret FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
if (!$user) die('User not found.');

$tfa = new RobThree\Auth\TwoFactorAuth(APP_NAME);
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['secret'], $_POST['code'])) {
    $secret = $_POST['secret'];
    $code = $_POST['code'];
    if ($tfa->verifyCode($secret, $code)) {
        $stmt = $pdo->prepare("UPDATE users SET totp_secret = ? WHERE id = ?");
        $stmt->execute([$secret, $user_id]);
        $success = true;
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
    <title>2FA Setup</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/premium.css">
    <style>
        .auth-page { min-height:100vh; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,var(--color-primary)0%,var(--color-primary-light)100%); padding:20px; }
        .auth-card { width:100%; max-width:500px; background:white; border-radius:var(--radius-xl); padding:40px; box-shadow:0 20px 60px rgba(0,0,0,0.2); }
        .step-indicator { display:flex; align-items:center; justify-content:center; gap:8px; margin-bottom:32px; }
        .step-dot { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; background:var(--color-border); color:var(--color-text-muted); transition:all var(--transition-fast); }
        .step-dot.active { background:#27187E; color:#F7F7FF; }
        .step-line { flex:1; max-width:60px; height:2px; background:var(--color-border); }
        .step-line.active { background:var(--color-primary); }
        .qr-container { width:180px; height:180px; margin:0 auto 20px; border:2px solid var(--color-border); border-radius:var(--radius-lg); display:flex; align-items:center; justify-content:center; overflow:hidden; padding:12px; background:white; }
        .qr-container img { width:100%; height:100%; object-fit:contain; }
        .code-input-group { display:flex; gap:8px; justify-content:center; margin:16px 0; }
        .code-input-group input { width:48px; height:56px; text-align:center; font-size:1.5rem; font-weight:700; border:2px solid var(--color-border); border-radius:var(--radius-md); font-family:var(--font-display); outline:none; transition:all var(--transition-fast); }
        .code-input-group input:focus { border-color:var(--color-primary); box-shadow:0 0 0 3px rgba(45,80,22,0.1); }
        .secret-key { font-family:var(--font-mono); font-size:0.8125rem; background:var(--color-bg-alt); padding:8px 14px; border-radius:var(--radius-md); letter-spacing:0.05em; word-break:break-all; user-select:all; cursor:pointer; }
    </style>
</head>
<body class="auth-page">
    <div class="auth-card" style="animation:fadeIn 0.5s ease;">
        <?php if ($success): ?>
            <div style="text-align:center;">
                <div style="width:64px;height:64px;background:rgba(45,80,22,0.06);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;"><i class="ti ti-shield-check" style="font-size:2rem;color:var(--color-success);"></i></div>
                <h1 class="login-heading" style="font-size:1.5rem;margin-bottom:8px;">2FA Enabled!</h1>
                <p style="color:var(--color-text-secondary);margin-bottom:24px;">Your account is now protected with Google Authenticator.</p>
                <a href="settings.php" class="btn btn-primary" style="justify-content:center;"><i class="ti ti-arrow-left"></i> Back to Settings</a>
            </div>
        <?php elseif (!empty($user['totp_secret'])): ?>
            <div style="text-align:center;">
                <div style="width:64px;height:64px;background:rgba(59,130,246,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;"><i class="ti ti-shield-lock" style="font-size:2rem;color:var(--color-info);"></i></div>
                <h1 class="login-heading" style="font-size:1.5rem;margin-bottom:8px;">2FA Already Active</h1>
                <p style="color:var(--color-text-secondary);margin-bottom:24px;">Two-factor authentication is already enabled for your account.</p>
                <div style="display:flex;gap:12px;justify-content:center;">
                    <a href="settings.php" class="btn btn-outline" style="justify-content:center;"><i class="ti ti-arrow-left"></i> Settings</a>
                    <a href="dashboard.php" class="btn btn-primary" style="justify-content:center;"><i class="ti ti-layout-dashboard"></i> Dashboard</a>
                </div>
            </div>
        <?php else:
            $secret = $tfa->createSecret();
            $qrCodeUrl = $tfa->getQRCodeImageAsDataUri(APP_NAME.' ('.$user['username'].')', $secret);
        ?>
            <div class="step-indicator">
                <div class="step-dot active">1</div><div class="step-line active"></div>
                <div class="step-dot">2</div><div class="step-line"></div>
                <div class="step-dot">3</div>
            </div>
            <div style="text-align:center;margin-bottom:28px;">
                <h1 class="login-heading" style="font-size:1.5rem;">Setup 2FA</h1>
                <p class="login-subheading">Enhance your account security with Google Authenticator</p>
            </div>
            <?php if ($error): ?>
                <div style="display:flex;align-items:center;gap:8px;padding:12px 16px;background:rgba(220,38,38,0.06);border:1px solid rgba(220,38,38,0.2);border-radius:var(--radius-md);margin-bottom:20px;">
                    <i class="ti ti-alert-circle" style="color:var(--color-danger);flex-shrink:0;"></i>
                    <span style="font-size:0.875rem;color:var(--color-danger);"><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>
            <div style="text-align:center;margin-bottom:24px;">
                <div style="font-size:0.8125rem;font-weight:600;color:var(--color-primary);margin-bottom:12px;">
                    <span style="background:var(--color-primary);color:white;width:20px;height:20px;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;font-size:0.6875rem;margin-right:6px;">1</span>
                    Scan QR Code
                </div>
                <div class="qr-container"><img src="<?= $qrCodeUrl ?>" alt="QR Code"></div>
                <p style="font-size:0.8125rem;color:var(--color-text-secondary);">Open Google Authenticator and tap <strong>+</strong> to scan</p>
            </div>
            <div style="margin-bottom:24px;padding:16px;background:var(--color-bg-alt);border-radius:var(--radius-lg);">
                <div style="font-size:0.8125rem;font-weight:600;color:var(--color-primary);margin-bottom:8px;">
                    <span style="background:var(--color-primary);color:white;width:20px;height:20px;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;font-size:0.6875rem;margin-right:6px;">2</span>
                    Or Enter Manually
                </div>
                <div class="secret-key" onclick="navigator.clipboard.writeText('');showToast('Copied!','success')">
                    <?= htmlspecialchars($secret) ?>
                </div>
                <p style="font-size:0.75rem;color:var(--color-text-muted);margin-top:6px;"><i class="ti ti-clipboard-copy"></i> Click to copy</p>
            </div>
            <div>
                <div style="font-size:0.8125rem;font-weight:600;color:var(--color-primary);margin-bottom:12px;">
                    <span style="background:var(--color-primary);color:white;width:20px;height:20px;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;font-size:0.6875rem;margin-right:6px;">3</span>
                    Enter 6-Digit Code
                </div>
                <form method="post" onsubmit="return validate2FA()">
                    <input type="hidden" name="secret" value="<?= htmlspecialchars($secret) ?>">
                    <div class="code-input-group" id="codeInputs">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" autofocus oninput="moveNext(this,0)" onkeydown="movePrev(this,event)" class="code-digit">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" oninput="moveNext(this,1)" onkeydown="movePrev(this,event)" class="code-digit">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" oninput="moveNext(this,2)" onkeydown="movePrev(this,event)" class="code-digit">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" oninput="moveNext(this,3)" onkeydown="movePrev(this,event)" class="code-digit">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" oninput="moveNext(this,4)" onkeydown="movePrev(this,event)" class="code-digit">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" oninput="moveNext(this,5)" onkeydown="movePrev(this,event)" class="code-digit">
                    </div>
                    <input type="hidden" name="code" id="fullCode">
                    <button type="submit" class="btn btn-primary btn-block mt-md" style="font-family:'Syne',sans-serif;" id="submitBtn">
                        <i class="ti ti-shield-check"></i> Verify & Enable 2FA
                    </button>
                </form>
            </div>
            <div style="text-align:center;margin-top:20px;">
                <a href="settings.php" style="font-size:0.875rem;font-weight:600;color:var(--color-text-secondary);text-decoration:none;">
                    <i class="ti ti-arrow-left" style="vertical-align:middle;"></i> Skip for now
                </a>
            </div>
        <?php endif; ?>
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