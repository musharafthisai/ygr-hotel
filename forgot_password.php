<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'config/db.php';
require_once 'config/constants.php';
$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $stmt = $pdo->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $stmt = $pdo->prepare("UPDATE users SET reset_token=?, reset_token_expiry=? WHERE id=?");
        $stmt->execute([$token, $expiry, $user['id']]);
        $resetLink = "http://localhost/yarahman/reset_password.php?token=$token";
        $to = $email;
        $subject = "Password Reset - " . APP_NAME;
        $message = "Hello,\n\nA password reset was requested for your account (username: {$user['username']}).\n\nClick the link below to reset your password (valid for 1 hour):\n$resetLink\n\nIf you did not request this, please ignore this email.\n\n- " . APP_NAME;
        $headers = "From: noreply@7digit.in";
        @mail($to, $subject, $message, $headers);
        $success = true;
    } else {
        $error = "No account found with that email address.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - <?= APP_NAME ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/css/premium.css">
    
    <style>
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%);
            padding: 20px;
        }
        
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: var(--radius-xl);
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: fadeIn 0.5s ease;
        }
        
        .alert-success {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: rgba(45, 80, 22, 0.06);
            border: 1px solid rgba(45, 80, 22, 0.2);
            border-radius: var(--radius-md);
            color: var(--color-success);
        }
    </style>
</head>
<body class="auth-page">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 28px;">
            <div style="width: 48px; height: 48px; background: var(--color-primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="ti ti-lock-question" style="color: white; font-size: 1.5rem;"></i>
            </div>
            <h1 class="login-heading" style="font-size:1.5rem;">Forgot Password?</h1>
            <p class="login-subheading">Enter your email and we'll send you a reset link</p>
        </div>
        
        <?php if ($success): ?>
            <div class="alert-success">
                <i class="ti ti-mail-check" style="font-size: 1.5rem; flex-shrink: 0;"></i>
                <div>
                    <div style="font-weight:600; margin-bottom:4px;">Reset link sent!</div>
                    <div style="font-size:0.8125rem; opacity:0.8;">Check your inbox for the password reset email. Valid for 1 hour.</div>
                </div>
            </div>
            <a href="index.php" class="btn btn-primary btn-block mt-lg" style="justify-content:center;">
                <i class="ti ti-arrow-left"></i> Back to Login
            </a>
        <?php else: ?>
            <?php if ($error): ?>
                <div style="display:flex; align-items:center; gap:8px; padding:12px 16px; background:rgba(220,38,38,0.06); border:1px solid rgba(220,38,38,0.2); border-radius:var(--radius-md); margin-bottom:20px;">
                    <i class="ti ti-alert-circle" style="color:var(--color-danger);"></i>
                    <span style="font-size:0.875rem; color:var(--color-danger);"><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>
            
            <form method="post" class="login-form" autocomplete="off">
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="form-input-with-icon">
                        <i class="ti ti-mail"></i>
                        <input type="email" name="email" class="form-input" placeholder="manager@restaurant.com" required autofocus>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block mb-lg" style="font-family:'Syne',sans-serif;">
                    <i class="ti ti-send"></i> Send Reset Link
                </button>
            </form>
            
            <div style="text-align: center;">
                <a href="index.php" style="font-size:0.875rem; font-weight:600; color:var(--color-primary); text-decoration:none;">
                    <i class="ti ti-arrow-left" style="vertical-align:middle;"></i> Back to Login
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
