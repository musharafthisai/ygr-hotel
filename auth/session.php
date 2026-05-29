<?php
// c:/xampp/htdocs/yarahman/auth/session.php

// Set session timeout to 8 hours (28800 seconds)
ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);
session_start();

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function check_auth() {
    if (!is_logged_in()) {
        header("Location: ../index.php");
        exit();
    }
    // 2FA enforcement: if user has totp_secret and not verified, redirect to 2FA page
    if (!isset($_SESSION['2fa_verified'])) {
        global $pdo;
        if (!isset($pdo)) {
            require_once __DIR__ . '/../config/db.php';
        }
        $stmt = $pdo->prepare("SELECT totp_secret FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        if ($user && !empty($user['totp_secret']) && basename($_SERVER['PHP_SELF']) !== 'verify_2fa.php') {
            header('Location: ../pages/verify_2fa.php');
            exit();
        }
    }
}

function check_role($allowed_roles) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
        // Redirect to a safe page if unauthorized
        if ($_SESSION['role'] === 'staff') {
            header("Location: ../pages/daily_entry.php");
        } else {
            header("Location: ../pages/dashboard.php");
        }
        exit();
    }
}

// Auto-logout warning at 7h 50min logic can be handled via JS based on login time.
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 28800)) {
    session_unset();
    session_destroy();
    header("Location: ../index.php?timeout=1");
    exit();
}

function render_branch_selector() {
    if ($_SESSION['role'] !== 'owner' || empty($_SESSION['admin_branches'])) {
        return '<div class="branch-name">' . htmlspecialchars($_SESSION['branch_name']) . '</div>';
    }
    
    $html = '<select class="branch-selector" onchange="window.location.href=\'../api/switch_branch.php?id=\'+this.value+\'&redirect=\'+encodeURIComponent(window.location.href)">';
    foreach ($_SESSION['admin_branches'] as $b) {
        $sel = ($b['id'] == $_SESSION['branch_id']) ? 'selected' : '';
        $html .= '<option value="'.$b['id'].'" '.$sel.'>' . htmlspecialchars($b['name']) . '</option>';
    }
    $html .= '</select>';
    return $html;
}
?>
