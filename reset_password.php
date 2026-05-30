<?php
require_once 'config/db.php';
require_once 'config/constants.php';

$error = '';
$success = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token=? AND reset_token_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    
    if ($user && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'];
        $confirm = $_POST['confirm_password'];
        
        if (strlen($password) < 6) {
            $error = "Password must be at least 6 characters.";
        } elseif ($password !== $confirm) {
            $error = "Passwords do not match.";
        } else {
            $newpass = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE users SET password=?, reset_token=NULL, reset_token_expiry=NULL WHERE id=?");
            $stmt->execute([$newpass, $user['id']]);
            $success = true;
        }
    }
    
    if ($user && !$success) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - <?= APP_NAME ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/css/premium.css">
    
    <style>
        .auth-page {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%);
            padding: 20px;
        }
        .auth-card {
            width: 100%; max-width: 420px; background: white;
            border-radius: var(--radius-xl); padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: fadeIn 0.5s ease;
        }
    </style>
</head>
<body class="auth-page">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 28px;">
            <div style="width:48px;height:48px;background:var(--color-primary);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <i class="ti ti-key" style="color:white;font-size:1.5rem;"></i>
            </div>
            <h1 class="login-heading" style="font-size:1.5rem;">Reset Password</h1>
            <p class="login-subheading">Enter your new password</p>
        </div>
        
        <?php if ($error): ?>
            <div style="display:flex;align-items:center;gap:8px;padding:12px 16px;background:rgba(220,38,38,0.06);border:1px solid rgba(220,38,38,0.2);border-radius:var(--radius-md);margin-bottom:20px;">
                <i class="ti ti-alert-circle" style="color:var(--color-danger);"></i>
                <span style="font-size:0.875rem;color:var(--color-danger);"><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label class="form-label">New Password</label>
                <div class="form-input-with-icon">
                    <i class="ti ti-lock"></i>
                    <input type="password" name="password" class="form-input" placeholder="Min. 6 characters" required minlength="6">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <div class="form-input-with-icon">
                    <i class="ti ti-lock-check"></i>
                    <input type="password" name="confirm_password" class="form-input" placeholder="Re-enter password" required minlength="6">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" style="font-family:'Syne',sans-serif;">
                <i class="ti ti-refresh"></i> Reset Password
            </button>
        </form>
    </div>
</body>
</html>
<?php
    } elseif (!$user) {
        // Invalid or expired token
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invalid Token - <?= APP_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/css/premium.css">
    <style>
        .auth-page { min-height:100vh; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,var(--color-primary)0%,var(--color-primary-light)100%); padding:20px; }
        .auth-card { width:100%;max-width:420px;background:white;border-radius:var(--radius-xl);padding:40px;box-shadow:0 20px 60px rgba(0,0,0,0.2); }
    </style>
</head>
<body class="auth-page">
    <div class="auth-card" style="text-align:center;">
        <div style="width:48px;height:48px;background:var(--color-danger);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="ti ti-alert-triangle" style="color:white;font-size:1.5rem;"></i>
        </div>
        <h1 class="login-heading" style="font-size:1.5rem; margin-bottom:8px;">Invalid or Expired Link</h1>
        <p style="color:var(--color-text-secondary);margin-bottom:24px;">This password reset link is invalid or has expired. Please request a new one.</p>
        <a href="login.php" class="btn btn-primary" style="justify-content:center;"><i class="ti ti-arrow-left"></i> Back to Login</a>
    </div>
</body>
</html>
<?php
    }
    
    if ($success) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - <?= APP_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/css/premium.css">
    <style>
        .auth-page { min-height:100vh; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,var(--color-primary)0%,var(--color-primary-light)100%); padding:20px; }
        .auth-card { width:100%;max-width:420px;background:white;border-radius:var(--radius-xl);padding:40px;box-shadow:0 20px 60px rgba(0,0,0,0.2); }
    </style>
</head>
<body class="auth-page">
    <div class="auth-card" style="text-align:center;">
        <div style="width:48px;height:48px;background:var(--color-success);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="ti ti-circle-check" style="color:white;font-size:1.5rem;"></i>
        </div>
        <h1 class="login-heading" style="font-size:1.5rem; margin-bottom:8px;">Password Reset Successful!</h1>
        <p style="color:var(--color-text-secondary);margin-bottom:24px;">Your password has been updated. You can now log in with your new password.</p>
        <a href="login.php" class="btn btn-primary" style="justify-content:center;"><i class="ti ti-login"></i> Sign In</a>
    </div>
</body>
</html>
<?php
    }
} else {
    header("Location: login.php");
    exit;
}
?>
