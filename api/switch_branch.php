<?php
require_once '../auth/session.php';
require_once '../config/db.php';
check_auth();

if ($_SESSION['role'] === 'owner' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Verify branch exists and fetch name
    $stmt = $pdo->prepare("SELECT name FROM branches WHERE id = ?");
    $stmt->execute([$id]);
    $branch = $stmt->fetch();
    
    if ($branch) {
        $_SESSION['branch_id'] = $id;
        $_SESSION['branch_name'] = $branch['name'];
    }
}

// Redirect back to the page they came from, or dashboard by default
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '../pages/dashboard.php';
header("Location: " . $redirect);
exit();
?>
