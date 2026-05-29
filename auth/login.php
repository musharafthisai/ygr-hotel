<?php
// c:/xampp/htdocs/yarahman/auth/login.php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        header("Location: ../index.php?error=empty");
        exit();
    }
    
    $stmt = $pdo->prepare("
        SELECT u.id, u.username, u.password, u.role, u.branch_id, b.name as branch_name, u.is_active, u.allowed_categories 
        FROM users u 
        LEFT JOIN branches b ON u.branch_id = b.id 
        WHERE u.username = ?
    ");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        if ($user['is_active'] == 0) {
            header("Location: ../index.php?error=inactive");
            exit();
        }
        
        // Login success
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['branch_id'] = $user['branch_id'];
        $_SESSION['branch_name'] = $user['branch_name'];
        $_SESSION['login_time'] = time();
        $_SESSION['allowed_categories'] = $user['allowed_categories'] ?? 'all';

        if ($user['role'] === 'owner') {
            $stmtBranches = $pdo->query("SELECT id, name FROM branches ORDER BY id ASC");
            $_SESSION['admin_branches'] = $stmtBranches->fetchAll(PDO::FETCH_ASSOC);
        }
        
        if ($user['role'] === 'staff') {
            header("Location: ../pages/daily_entry.php");
        } else {
            header("Location: ../pages/dashboard.php");
        }
        exit();
    } else {
        header("Location: ../index.php?error=invalid");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>
