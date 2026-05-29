<?php
// c:/xampp/htdocs/yarahman/api/save_online.php
require_once '../auth/session.php';
require_once '../config/db.php';
check_auth();
check_role(['owner', 'branch_admin']);

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['sale_date']) || !isset($data['platform']) || !isset($data['amount'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data format']);
    exit();
}

$branch_id = $_SESSION['branch_id'];
$user_id = $_SESSION['user_id'];
$sale_date = $data['sale_date'];
$platform = $data['platform'];
$amount = $data['amount'];

if (!in_array($platform, ['Swiggy', 'Zomato'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid platform']);
    exit();
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO online_sales (branch_id, sale_date, platform, amount, entered_by) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$branch_id, $sale_date, $platform, $amount, $user_id]);
    
    echo json_encode(['success' => true, 'message' => 'Online sales saved successfully']);
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
