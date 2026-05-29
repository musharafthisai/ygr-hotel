<?php
// c:/xampp/htdocs/yarahman/api/get_dashboard.php
require_once '../auth/session.php';
require_once '../config/db.php';
check_auth();
check_role(['owner', 'branch_admin']);

header('Content-Type: application/json');

$branch_id = $_SESSION['branch_id'];
$today = date('Y-m-d');
$first_day_week = date('Y-m-d', strtotime('monday this week'));
$last_day_week = date('Y-m-d', strtotime('sunday this week'));
$first_day_month = date('Y-m-01');
$last_day_month = date('Y-m-t');

$data = [];

// Helper function for quick sums
function getSum($pdo, $sql, $params) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return (float)$stmt->fetchColumn() ?: 0;
}

// 1. Today KPIs
$sqlTodaySales = "SELECT SUM(amount) FROM daily_entries WHERE branch_id=? AND entry_date=? AND item_name IN (SELECT item_name FROM item_rates WHERE branch_id=? AND category='sales')";
$data['todaySales'] = getSum($pdo, $sqlTodaySales, [$branch_id, $today, $branch_id]);

$sqlTodayCash = "SELECT SUM(cash_amount) FROM daily_payments WHERE branch_id=? AND entry_date=?";
$data['todayCash'] = getSum($pdo, $sqlTodayCash, [$branch_id, $today]);

$sqlTodayGPay = "SELECT SUM(gpay_amount) FROM daily_payments WHERE branch_id=? AND entry_date=?";
$data['todayGPay'] = getSum($pdo, $sqlTodayGPay, [$branch_id, $today]);

$sqlTodayExp = "SELECT SUM(amount) FROM daily_entries WHERE branch_id=? AND entry_date=? AND item_name IN (SELECT item_name FROM item_rates WHERE branch_id=? AND category='expense')";
$data['todayExp'] = getSum($pdo, $sqlTodayExp, [$branch_id, $today, $branch_id]);

// 2. Week KPIs
$sqlWeekSales = "SELECT SUM(amount) FROM daily_entries WHERE branch_id=? AND entry_date BETWEEN ? AND ? AND item_name IN (SELECT item_name FROM item_rates WHERE branch_id=? AND category='sales')";
$data['weekSales'] = getSum($pdo, $sqlWeekSales, [$branch_id, $first_day_week, $last_day_week, $branch_id]);

$sqlWeekExp = "SELECT SUM(amount) FROM daily_entries WHERE branch_id=? AND entry_date BETWEEN ? AND ? AND item_name IN (SELECT item_name FROM item_rates WHERE branch_id=? AND category='expense')";
$data['weekExp'] = getSum($pdo, $sqlWeekExp, [$branch_id, $first_day_week, $last_day_week, $branch_id]);

$sqlWeekSwiggy = "SELECT SUM(amount) FROM online_sales WHERE branch_id=? AND sale_date BETWEEN ? AND ? AND platform='Swiggy'";
$data['weekSwiggy'] = getSum($pdo, $sqlWeekSwiggy, [$branch_id, $first_day_week, $last_day_week]);

$sqlWeekZomato = "SELECT SUM(amount) FROM online_sales WHERE branch_id=? AND sale_date BETWEEN ? AND ? AND platform='Zomato'";
$data['weekZomato'] = getSum($pdo, $sqlWeekZomato, [$branch_id, $first_day_week, $last_day_week]);

// 3. Month KPIs
$sqlMonthSales = "SELECT SUM(amount) FROM daily_entries WHERE branch_id=? AND entry_date BETWEEN ? AND ? AND item_name IN (SELECT item_name FROM item_rates WHERE branch_id=? AND category='sales')";
$monthSales = getSum($pdo, $sqlMonthSales, [$branch_id, $first_day_month, $last_day_month, $branch_id]);

$sqlMonthExp = "SELECT SUM(amount) FROM daily_entries WHERE branch_id=? AND entry_date BETWEEN ? AND ? AND item_name IN (SELECT item_name FROM item_rates WHERE branch_id=? AND category='expense')";
$monthExp = getSum($pdo, $sqlMonthExp, [$branch_id, $first_day_month, $last_day_month, $branch_id]);

$sqlMonthSwiggy = "SELECT SUM(amount) FROM online_sales WHERE branch_id=? AND sale_date BETWEEN ? AND ? AND platform='Swiggy'";
$monthSwiggy = getSum($pdo, $sqlMonthSwiggy, [$branch_id, $first_day_month, $last_day_month]);

$sqlMonthZomato = "SELECT SUM(amount) FROM online_sales WHERE branch_id=? AND sale_date BETWEEN ? AND ? AND platform='Zomato'";
$monthZomato = getSum($pdo, $sqlMonthZomato, [$branch_id, $first_day_month, $last_day_month]);

$data['monthNetProfit'] = ($monthSales + $monthSwiggy + $monthZomato) - $monthExp;

// 4. Charts Data
// Bar chart: Last 7 days sales vs expense
$data['last7Days'] = [];
for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $label = date('D', strtotime($d));
    
    $s = getSum($pdo, "SELECT SUM(amount) FROM daily_entries WHERE branch_id=? AND entry_date=? AND item_name IN (SELECT item_name FROM item_rates WHERE branch_id=? AND category='sales')", [$branch_id, $d, $branch_id]);
    $e = getSum($pdo, "SELECT SUM(amount) FROM daily_entries WHERE branch_id=? AND entry_date=? AND item_name IN (SELECT item_name FROM item_rates WHERE branch_id=? AND category='expense')", [$branch_id, $d, $branch_id]);
    
    $data['last7Days']['labels'][] = $label;
    $data['last7Days']['sales'][] = $s;
    $data['last7Days']['expenses'][] = $e;
}

// Pie chart: Month Payment Split
$sqlMonthCash = "SELECT SUM(cash_amount) FROM daily_payments WHERE branch_id=? AND entry_date BETWEEN ? AND ?";
$monthCash = getSum($pdo, $sqlMonthCash, [$branch_id, $first_day_month, $last_day_month]);

$sqlMonthGPay = "SELECT SUM(gpay_amount) FROM daily_payments WHERE branch_id=? AND entry_date BETWEEN ? AND ?";
$monthGPay = getSum($pdo, $sqlMonthGPay, [$branch_id, $first_day_month, $last_day_month]);

$data['pieData'] = [
    $monthCash,
    $monthGPay,
    $monthSwiggy + $monthZomato
];

echo json_encode(['success' => true, 'data' => $data]);
?>
