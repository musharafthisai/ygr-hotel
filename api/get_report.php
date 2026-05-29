<?php
// c:/xampp/htdocs/yarahman/api/get_report.php
require_once '../auth/session.php';
require_once '../config/db.php';
require_once '../config/constants.php';
check_auth();
check_role(['owner', 'branch_admin']);

header('Content-Type: application/json');

$type = $_GET['type'] ?? '';
$branch_id = $_SESSION['branch_id'];

if ($type === 'weekly') {
    $from = $_GET['from'] ?? date('Y-m-d', strtotime('monday this week'));
    $to = $_GET['to'] ?? date('Y-m-d', strtotime('sunday this week'));
    $item = $_GET['item'] ?? 'all';

    // Fetch items (filter if needed)
    if ($item !== 'all') {
        $stmt = $pdo->prepare("SELECT item_name, category FROM item_rates WHERE branch_id=? AND item_name=? ORDER BY sort_order");
        $stmt->execute([$branch_id, $item]);
    } else {
        $stmt = $pdo->prepare("SELECT item_name, category FROM item_rates WHERE branch_id=? ORDER BY sort_order");
        $stmt->execute([$branch_id]);
    }
    $items = $stmt->fetchAll();

    // Fetch daily entries (filter if needed)
    if ($item !== 'all') {
        $stmt = $pdo->prepare("SELECT entry_date, item_name, amount, payment_mode FROM daily_entries WHERE branch_id=? AND entry_date BETWEEN ? AND ? AND item_name=?");
        $stmt->execute([$branch_id, $from, $to, $item]);
    } else {
        $stmt = $pdo->prepare("SELECT entry_date, item_name, amount, payment_mode FROM daily_entries WHERE branch_id=? AND entry_date BETWEEN ? AND ?");
        $stmt->execute([$branch_id, $from, $to]);
    }

    $entries = [];
    $daily_cash = [];
    $daily_gpay = [];

    // Initialize structure
    $dates = [];
    $current = strtotime($from);
    $end = strtotime($to);
    while ($current <= $end) {
        $d = date('Y-m-d', $current);
        $dates[] = $d;
        $daily_cash[$d] = 0;
        $daily_gpay[$d] = 0;
        $current = strtotime('+1 day', $current);
    }

    $item_data = [];
    foreach ($items as $itemRow) {
        $item_data[$itemRow['item_name']] = [
            'category' => $itemRow['category'],
            'days' => array_fill_keys($dates, 0)
        ];
    }

    while ($row = $stmt->fetch()) {
        $name = $row['item_name'];
        $d = $row['entry_date'];
        $amt = (float)$row['amount'];
        if (isset($item_data[$name])) {
            $item_data[$name]['days'][$d] += $amt;
        }
    }

    // Fetch manual EOD payments for cash/gpay
    $stmtPay = $pdo->prepare("SELECT entry_date, cash_amount, gpay_amount FROM daily_payments WHERE branch_id=? AND entry_date BETWEEN ? AND ?");
    $stmtPay->execute([$branch_id, $from, $to]);
    while ($rowPay = $stmtPay->fetch()) {
        $d = $rowPay['entry_date'];
        if (isset($daily_cash[$d])) {
            $daily_cash[$d] = (float)$rowPay['cash_amount'];
            $daily_gpay[$d] = (float)$rowPay['gpay_amount'];
        }
    }
    
    // Fetch online daily breakdown
    $stmt = $pdo->prepare("SELECT platform, SUM(amount) as amt FROM online_sales WHERE branch_id=? AND sale_date BETWEEN ? AND ? GROUP BY platform");
    $stmt->execute([$branch_id, $from, $to]);
    $swiggy = 0; $zomato = 0;
    while($r = $stmt->fetch()) {
        if($r['platform']=='Swiggy') $swiggy = (float)$r['amt'];
        if($r['platform']=='Zomato') $zomato = (float)$r['amt'];
    }
    
    $daily_swiggy = array_fill_keys($dates, 0);
    $daily_zomato = array_fill_keys($dates, 0);
    $stmtOnline = $pdo->prepare("SELECT sale_date, platform, SUM(amount) as amt FROM online_sales WHERE branch_id=? AND sale_date BETWEEN ? AND ? GROUP BY sale_date, platform");
    $stmtOnline->execute([$branch_id, $from, $to]);
    while($r = $stmtOnline->fetch()) {
        if($r['platform']=='Swiggy') $daily_swiggy[$r['sale_date']] = (float)$r['amt'];
        if($r['platform']=='Zomato') $daily_zomato[$r['sale_date']] = (float)$r['amt'];
    }
    
    echo json_encode([
        'success' => true,
        'dates' => $dates,
        'items' => $item_data,
        'daily_cash' => $daily_cash,
        'daily_gpay' => $daily_gpay,
        'swiggy' => $swiggy,
        'zomato' => $zomato,
        'daily_swiggy' => $daily_swiggy,
        'daily_zomato' => $daily_zomato
    ]);
    exit();
} 
else if ($type === 'monthly') {
    $month = $_GET['month'] ?? date('Y-m');
    $start = $month . '-01';
    $end = date('Y-m-t', strtotime($start));
    
    // Fetch items
    $stmt = $pdo->prepare("SELECT item_name, category FROM item_rates WHERE branch_id=? ORDER BY sort_order");
    $stmt->execute([$branch_id]);
    $items = [];
    while($r = $stmt->fetch()){ $items[] = $r; }
    
    // Setup days
    $days = [];
    $current = strtotime($start);
    $endTime = strtotime($end);
    while($current <= $endTime) {
        $days[] = date('Y-m-d', $current);
        $current = strtotime('+1 day', $current);
    }
    
    // Fetch entries
    $stmt = $pdo->prepare("SELECT entry_date, item_name, amount, payment_mode FROM daily_entries WHERE branch_id=? AND entry_date BETWEEN ? AND ?");
    $stmt->execute([$branch_id, $start, $end]);
    
    // Structure: day => { item_name => amount, cash => sum, gpay => sum }
    $data = [];
    foreach($days as $d) {
        $data[$d] = ['cash'=>0, 'gpay'=>0, 'items'=>[]];
        foreach($items as $it) {
            $data[$d]['items'][$it['item_name']] = 0;
        }
    }
    
    while($row = $stmt->fetch()) {
        $d = $row['entry_date'];
        $n = $row['item_name'];
        $a = (float)$row['amount'];
        
        if(isset($data[$d]['items'][$n])) {
            $data[$d]['items'][$n] += $a;
        }
    }
    
    // Fetch manual EOD payments for cash/gpay
    $stmtPay = $pdo->prepare("SELECT entry_date, cash_amount, gpay_amount FROM daily_payments WHERE branch_id=? AND entry_date BETWEEN ? AND ?");
    $stmtPay->execute([$branch_id, $start, $end]);
    while ($rowPay = $stmtPay->fetch()) {
        $d = $rowPay['entry_date'];
        if (isset($data[$d])) {
            $data[$d]['cash'] = (float)$rowPay['cash_amount'];
            $data[$d]['gpay'] = (float)$rowPay['gpay_amount'];
        }
    }
    
    // Fetch online daily breakdown
    $stmt = $pdo->prepare("SELECT platform, SUM(amount) as amt FROM online_sales WHERE branch_id=? AND sale_date BETWEEN ? AND ? GROUP BY platform");
    $stmt->execute([$branch_id, $start, $end]);
    $swiggy = 0; $zomato = 0;
    while($r = $stmt->fetch()) {
        if($r['platform']=='Swiggy') $swiggy = (float)$r['amt'];
        if($r['platform']=='Zomato') $zomato = (float)$r['amt'];
    }
    
    $daily_swiggy = array_fill_keys($days, 0);
    $daily_zomato = array_fill_keys($days, 0);
    $stmtOnline = $pdo->prepare("SELECT sale_date, platform, SUM(amount) as amt FROM online_sales WHERE branch_id=? AND sale_date BETWEEN ? AND ? GROUP BY sale_date, platform");
    $stmtOnline->execute([$branch_id, $start, $end]);
    while($r = $stmtOnline->fetch()) {
        if($r['platform']=='Swiggy') $daily_swiggy[$r['sale_date']] = (float)$r['amt'];
        if($r['platform']=='Zomato') $daily_zomato[$r['sale_date']] = (float)$r['amt'];
    }
    
    // Add daily online to each day's data
    foreach($days as $d) {
        $data[$d]['swiggy'] = $daily_swiggy[$d];
        $data[$d]['zomato'] = $daily_zomato[$d];
    }
    
    echo json_encode([
        'success' => true,
        'days' => $days,
        'items' => $items,
        'data' => $data,
        'swiggy' => $swiggy,
        'zomato' => $zomato,
        'daily_swiggy' => $daily_swiggy,
        'daily_zomato' => $daily_zomato
    ]);
    exit();
}

echo json_encode(['success' => false, 'message' => 'Invalid type']);
?>
