<?php
// c:/xampp/htdocs/yarahman/api/save_entry.php
require_once '../auth/session.php';
require_once '../config/db.php';
check_auth();

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['entry_date']) || !isset($data['entries'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data format']);
    exit();
}

$branch_id = $_SESSION['branch_id'];
$user_id = $_SESSION['user_id'];
$entry_date = $data['entry_date'];

// Staff can only enter today's data
if ($_SESSION['role'] === 'staff' && $entry_date !== date('Y-m-d')) {
    echo json_encode(['success' => false, 'message' => 'Staff can only enter data for today']);
    exit();
}

try {
    $pdo->beginTransaction();

    // 1. Save manual EOD payment figures
    $cash_amount = isset($data['cash_amount']) ? (float)$data['cash_amount'] : 0;
    $gpay_amount = isset($data['gpay_amount']) ? (float)$data['gpay_amount'] : 0;

    $stmtPay = $pdo->prepare("
        INSERT INTO daily_payments 
        (branch_id, entry_date, cash_amount, gpay_amount, entered_by) 
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        cash_amount = VALUES(cash_amount), 
        gpay_amount = VALUES(gpay_amount), 
        entered_by = VALUES(entered_by)
    ");
    $stmtPay->execute([$branch_id, $entry_date, $cash_amount, $gpay_amount, $user_id]);

    // 2. Save individual items
    $stmt = $pdo->prepare("
        INSERT INTO daily_entries 
        (branch_id, entry_date, item_name, quantity, unit_price, amount, payment_mode, entered_by) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        quantity = VALUES(quantity), 
        unit_price = VALUES(unit_price), 
        amount = VALUES(amount), 
        payment_mode = VALUES(payment_mode), 
        entered_by = VALUES(entered_by)
    ");

    foreach ($data['entries'] as $entry) {
        if (isset($entry['amount']) && $entry['amount'] !== '') {
            $stmt->execute([
                $branch_id,
                $entry_date,
                $entry['item_name'],
                $entry['qty'] ?: 0,
                $entry['unit_price'] ?: 0,
                $entry['amount'] ?: 0,
                $entry['payment_mode'],
                $user_id
            ]);
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Data saved successfully']);

} catch (\PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
