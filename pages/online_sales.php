<?php
// pages/online_sales.php
require_once '../auth/session.php';
require_once '../config/db.php';
require_once '../config/constants.php';
check_auth();
check_role(['owner', 'branch_admin']);
$branch_id = $_SESSION['branch_id'];
$branch_name = $_SESSION['branch_name'];

if (isset($_GET['delete_id'])) {
    $del_id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM online_sales WHERE id = ? AND branch_id = ?");
    $stmt->execute([$del_id, $branch_id]);
    header("Location: online_sales.php?msg=deleted");
    exit();
}

$month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
$stmt = $pdo->prepare("
    SELECT os.*, u.name as user_name 
    FROM online_sales os
    LEFT JOIN users u ON os.entered_by = u.id
    WHERE os.branch_id = ? AND DATE_FORMAT(os.sale_date, '%Y-%m') = ?
    ORDER BY os.sale_date DESC
");
$stmt->execute([$branch_id, $month]);
$entries = $stmt->fetchAll();

$swiggy_total = 0;
$zomato_total = 0;
foreach ($entries as $e) {
    if ($e['platform'] == 'Swiggy') $swiggy_total += $e['amount'];
    if ($e['platform'] == 'Zomato') $zomato_total += $e['amount'];
}

define('BASE_URL', (strpos($_SERVER['SCRIPT_NAME'] ?? '', '/yarahman') !== false) ? '/yarahman/' : '/');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Online Sales - YGR signature</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#F7F7FF', accent: '#FB3640', swiggy: '#FF5200', zomato: '#E23744' },
                    fontFamily: { display: ['Syne','sans-serif'], body: ['DM Sans','sans-serif'] }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/premium.css">
    <style>
        .online-page { max-width: 1200px; margin: 0 auto; min-height: calc(100vh - 80px); }
        @media (max-width: 1023px) { .online-page { padding: 16px; padding-bottom: 120px; min-height: calc(100vh - 40px); } }
        .entry-row { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .entry-row .form-group { margin-bottom: 0; flex: 1; min-width: 120px; }
    </style>
</head>
<body>
    <div class="loader-overlay"></div>

    <!-- ===== TOP HEADER (Desktop) ===== -->
    <div class="top-header">
        <div class="header-left">
            <div class="header-brand">
                <div class="header-brand-icon"><img src="../assets/img/logo.png" alt="YGR" style="height:28px;width:auto;filter:brightness(0)"></div>
                YGR signature
            </div>
            <nav class="header-center">
                <a href="dashboard.php" class="nav-link"><i class="ti ti-layout-dashboard"></i> Dashboard</a>
                <a href="daily_entry.php" class="nav-link"><i class="ti ti-pencil-plus"></i> Entry</a>
                <a href="weekly_report.php" class="nav-link"><i class="ti ti-file-analytics"></i> Weekly</a>
                <a href="monthly_report.php" class="nav-link"><i class="ti ti-calendar-stats"></i> Monthly</a>
                <a href="online_sales.php" class="nav-link active"><i class="ti ti-truck-delivery"></i> Online</a>
                <a href="settings.php" class="nav-link"><i class="ti ti-settings"></i> Settings</a>
            </nav>
        </div>
        <div class="header-right">
            <a href="<?php echo BASE_URL; ?>logout.php" class="header-btn">
                <i class="ti ti-logout"></i> <span>Logout</span>
            </a>
            <div class="avatar"><?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 2)); ?></div>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="content-area online-page">
        <div class="flex-between mb-xl" style="flex-wrap: wrap; gap: 12px;">
            <div>
                <h1 class="text-2xl font-bold" style="font-family:'Syne',sans-serif;">Online Sales</h1>
                <p class="text-muted text-sm">Swiggy & Zomato order tracking</p>
            </div>
            <form method="GET" style="display:flex; gap:8px; align-items:center;">
                <input type="month" name="month" value="<?= $month ?>" class="form-input" style="width: 200px;">
                <button type="submit" class="btn btn-primary btn-sm"><i class="ti ti-refresh"></i></button>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-xl">
            <div class="online-summary-card swiggy">
                <div class="online-summary-icon"><i class="ti ti-motorbike"></i></div>
                <div class="online-summary-label">Swiggy Total</div>
                <div class="online-summary-value">₹<?= number_format($swiggy_total, 2) ?></div>
                <div class="online-summary-orders"><?= count(array_filter($entries, fn($e) => $e['platform'] == 'Swiggy')) ?> orders</div>
            </div>
            <div class="online-summary-card zomato">
                <div class="online-summary-icon"><i class="ti ti-motorbike"></i></div>
                <div class="online-summary-label">Zomato Total</div>
                <div class="online-summary-value">₹<?= number_format($zomato_total, 2) ?></div>
                <div class="online-summary-orders"><?= count(array_filter($entries, fn($e) => $e['platform'] == 'Zomato')) ?> orders</div>
            </div>
            <div class="card flex-center" style="background:linear-gradient(135deg,#27187E,#F7F7FF);color:#27187E;">
                <div style="text-align:center;">
                    <div style="font-size:12px;opacity:0.8;text-transform:uppercase;letter-spacing:0.5px;">Combined Total</div>
                    <div style="font-family:'Syne',sans-serif;font-size:32px;font-weight:800;margin-top:4px;">₹<?= number_format($swiggy_total + $zomato_total, 2) ?></div>
                </div>
            </div>
        </div>

        <!-- Add Entry Form -->
        <div class="card mb-xl">
            <div class="card-header">
                <span class="card-title"><i class="ti ti-plus-circle"></i> Add Online Sale</span>
            </div>
            <form id="onlineForm" method="POST" action="../api/save_online.php" class="entry-row">
                <div class="form-group">
                    <label class="form-label">Date</label>
                    <input type="date" name="sale_date" class="form-input" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Platform</label>
                    <select name="platform" id="platformSelect" class="form-input" required>
                        <option value="Swiggy">🍟 Swiggy</option>
                        <option value="Zomato">🍕 Zomato</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Amount (₹)</label>
                    <input type="number" step="any" name="amount" class="form-input" placeholder="0.00" required>
                </div>
                <div class="form-group" style="flex: 0 0 auto;">
                    <button type="submit" class="btn btn-primary" style="margin-top: 20px;">
                        <i class="ti ti-plus"></i> Add
                    </button>
                </div>
            </form>
        </div>

        <!-- Entries Table -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div class="card-header" style="padding: 16px 20px; margin-bottom: 0;">
                <span class="card-title"><i class="ti ti-table"></i> Sales Entries</span>
                <span class="text-muted text-sm"><?= count($entries) ?> entries</span>
            </div>
            <?php if ($msg = $_GET['msg'] ?? ''): ?>
            <div class="toast success" style="margin: 12px 20px 0; position:relative;">
                <i class="ti ti-circle-check"></i>
                <span><?= htmlspecialchars($msg) ?></span>
                <div class="toast-progress" style="animation-duration: 3s;"></div>
            </div>
            <?php endif; ?>
            <div class="table-container" style="border: none;">
                <table class="report-table" id="onlineTable">
                    <thead>
                        <tr>
                            <th style="text-align:left;">Date</th>
                            <th style="text-align:left;">Platform</th>
                            <th style="text-align:right;">Amount</th>
                            <th style="text-align:left;">Entered By</th>
                            <th style="text-align:center;width:80px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($entries)): ?>
                        <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);">No entries for this month</td></tr>
                        <?php else: ?>
                        <?php foreach ($entries as $entry): ?>
                        <tr>
                            <td style="text-align:left;font-weight:600;"><?= htmlspecialchars($entry['sale_date']) ?></td>
                            <td style="text-align:left;">
                                <span class="<?= $entry['platform'] === 'Swiggy' ? 'badge-swiggy' : 'badge-zomato' ?>">
                                    <i class="ti ti-motorbike"></i> <?= htmlspecialchars($entry['platform']) ?>
                                </span>
                            </td>
                            <td style="font-weight:700;font-family:'Syne',sans-serif;font-size:15px;">₹<?= number_format($entry['amount'], 2) ?></td>
                            <td style="text-align:left;color:var(--text-secondary);"><?= htmlspecialchars($entry['user_name'] ?? '—') ?></td>
                            <td style="text-align:center;">
                                <button class="btn btn-danger btn-xs" onclick="deleteEntry(<?= $entry['id'] ?>)">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ===== FLOATING PILL NAVBAR (Mobile) ===== -->
    <nav id="floatingNav" class="floating-nav">
        <button class="nav-item" data-index="0" onclick="setNav(0)"><i class="ti ti-pencil-plus"></i><span class="nav-label">Entry</span></button>
        <button class="nav-item" data-index="1" onclick="setNav(1)"><i class="ti ti-calendar-week"></i><span class="nav-label">Weekly</span></button>
        <button class="nav-item" data-index="2" onclick="setNav(2)"><i class="ti ti-calendar-month"></i><span class="nav-label">Monthly</span></button>
        <button class="nav-item active" data-index="3" onclick="setNav(3)"><i class="ti ti-truck-delivery"></i><span class="nav-label">Online</span></button>
        <button class="nav-item" data-index="4" onclick="setNav(4)"><i class="ti ti-settings"></i><span class="nav-label">Settings</span></button>
    </nav>

    <script src="<?php echo BASE_URL; ?>assets/js/app.js"></script>
    <script>
        async function deleteEntry(id) {
            const confirmed = await showConfirmModal("Delete this entry?", "Delete", "Cancel");
            if (confirmed) {
                window.location.href = 'online_sales.php?delete_id=' + id;
            }
        }

        document.getElementById('onlineForm')?.addEventListener('submit', function(e) {
            showLoader();
        });
    </script>
</body>
</html>