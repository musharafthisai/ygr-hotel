<?php
// pages/daily_entry.php
require_once '../auth/session.php';
require_once '../config/db.php';
require_once '../config/constants.php';
check_auth();

$branch_id = $_SESSION['branch_id'];
$branch_name = $_SESSION['branch_name'];

// Auto-select first branch for owners with no branch set
if ($branch_id === null && $_SESSION['role'] === 'owner' && !empty($_SESSION['admin_branches'])) {
    $branch_id = $_SESSION['admin_branches'][0]['id'];
    $_SESSION['branch_id'] = $branch_id;
    $_SESSION['branch_name'] = $_SESSION['admin_branches'][0]['name'];
    $branch_name = $_SESSION['branch_name'];
}

$msg = '';
$msg_type = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_item') {
    $item_name = trim($_POST['item_name'] ?? '');
    $category = $_POST['category'] ?? 'sales';
    $rate = floatval($_POST['rate'] ?? 0);
    
    $stmtAllowed = $pdo->prepare("SELECT allowed_categories FROM users WHERE id = ?");
    $stmtAllowed->execute([$_SESSION['user_id']]);
    $allowed_categories = $stmtAllowed->fetchColumn() ?: 'all';
    
    if ($allowed_categories !== 'all' && $category !== $allowed_categories) {
        $msg = "Error: You are not authorized to add items to the " . ucfirst($category) . " category.";
        $msg_type = 'error';
    } else if (empty($item_name)) {
        $msg = "Error: Item name cannot be empty.";
        $msg_type = 'error';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM item_rates WHERE branch_id = ?");
            $stmt->execute([$branch_id]);
            $next_sort = $stmt->fetchColumn();
            $stmt = $pdo->prepare("INSERT INTO item_rates (branch_id, item_name, rate, category, sort_order) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$branch_id, $item_name, $rate, $category, $next_sort]);
            $msg = "New item added successfully.";
            $msg_type = 'success';
        } catch (\PDOException $e) {
            $msg = "Error: Item already exists in this branch.";
            $msg_type = 'error';
        }
    }
}

$date = date('Y-m-d');
if ($_SESSION['role'] !== 'staff' && isset($_GET['date'])) {
    $date = $_GET['date'];
}

$stmtAllowed = $pdo->prepare("SELECT allowed_categories FROM users WHERE id = ?");
$stmtAllowed->execute([$_SESSION['user_id']]);
$allowed_categories = $stmtAllowed->fetchColumn() ?: 'all';

if ($allowed_categories === 'sales') {
    $stmt = $pdo->prepare("SELECT item_name, rate, category FROM item_rates WHERE branch_id = ? AND category = 'sales' ORDER BY sort_order ASC");
} else if ($allowed_categories === 'expense') {
    $stmt = $pdo->prepare("SELECT item_name, rate, category FROM item_rates WHERE branch_id = ? AND category = 'expense' ORDER BY sort_order ASC");
} else {
    $stmt = $pdo->prepare("SELECT item_name, rate, category FROM item_rates WHERE branch_id = ? ORDER BY sort_order ASC");
}
$stmt->execute([$branch_id]);
$items = $stmt->fetchAll();

$no_items_reason = "";
if (empty($items)) {
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM item_rates WHERE branch_id = ?");
    $stmtCheck->execute([$branch_id]);
    $total_branch_items = $stmtCheck->fetchColumn();
    $no_items_reason = $total_branch_items == 0 ? "no_branch_items" : "no_category_items";
}

$stmt = $pdo->prepare("SELECT * FROM daily_entries WHERE branch_id = ? AND entry_date = ?");
$stmt->execute([$branch_id, $date]);
$existing_data = [];
while ($row = $stmt->fetch()) {
    $existing_data[$row['item_name']] = $row;
}
$is_update = count($existing_data) > 0;

$stmt = $pdo->prepare("SELECT * FROM daily_payments WHERE branch_id = ? AND entry_date = ?");
$stmt->execute([$branch_id, $date]);
$existing_payment = $stmt->fetch();

define('BASE_URL', (strpos($_SERVER['SCRIPT_NAME'] ?? '', '/yarahman') !== false) ? '/yarahman/' : '/');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Daily Entry - YGR signature</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#0D2818', accent: '#FB3640', swiggy: '#FF5200', zomato: '#E23744' },
                    fontFamily: { display: ['Syne','sans-serif'], body: ['DM Sans','sans-serif'] }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/premium.css">
    <style>
        .entry-page { max-width: 900px; margin: 0 auto; }
        #items-grid { display: grid; gap: 14px; }
        @media (min-width: 640px) { #items-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 1023px) { .entry-page { padding: 16px; padding-bottom: 120px; } }
    </style>
</head>
<body>
    <div class="loader-overlay"></div>

    <div class="top-header">
        <div class="header-left">
            <div class="header-brand">
                <div class="header-brand-icon"><i class="ti ti-bowl-rice"></i></div>
                YGR signature
            </div>
            <nav class="header-center">
                <a href="dashboard.php" class="nav-link"><i class="ti ti-layout-dashboard"></i> Dashboard</a>
                <a href="daily_entry.php" class="nav-link active"><i class="ti ti-pencil-plus"></i> Entry</a>
                <a href="weekly_report.php" class="nav-link"><i class="ti ti-file-analytics"></i> Reports</a>
                <a href="settings.php" class="nav-link"><i class="ti ti-settings"></i> Settings</a>
            </nav>
        </div>
        <div class="header-right">
            <?php if ($_SESSION['role'] === 'owner' && !empty($_SESSION['admin_branches'])): ?>
            <select class="branch-selector" onchange="if(this.value) window.location.href='../api/switch_branch.php?id='+this.value+'&redirect='+encodeURIComponent(window.location.href)">
                <?php foreach ($_SESSION['admin_branches'] as $b): ?>
                <option value="<?= $b['id'] ?>" <?= $b['id'] == $_SESSION['branch_id'] ? 'selected' : '' ?>><?= htmlspecialchars($b['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>logout.php" class="header-btn">
                <i class="ti ti-logout"></i> <span>Logout</span>
            </a>
            <div class="avatar"><?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 2)); ?></div>
        </div>
    </div>

    <div class="content-area entry-page">
        <?php if($msg): ?>
        <div class="toast <?= $msg_type === 'error' ? 'error' : 'success' ?>" style="position:relative; margin-bottom:15px; opacity:1;">
            <i class="ti ti-<?= $msg_type === 'error' ? 'alert-circle' : 'circle-check' ?>"></i>
            <span><?= htmlspecialchars($msg) ?></span>
            <div class="toast-progress" style="animation-duration: 3s;"></div>
        </div>
        <?php endif; ?>

        <div class="flex-between mb-lg" style="flex-wrap: wrap; gap: 12px;">
            <div>
                <h1 class="text-2xl font-bold" style="font-family:'Syne',sans-serif;">Daily Entry</h1>
                <p class="text-muted text-sm"><?= date('l, d F Y', strtotime($date)) ?></p>
            </div>
            <div style="display: flex; gap: 8px; align-items: center;">
                <?php if($_SESSION['role'] !== 'staff'): ?>
                <form action="daily_entry.php" method="GET" style="display:flex; gap:8px; align-items:center;">
                    <input type="date" name="date" value="<?= $date ?>" max="<?= date('Y-m-d') ?>" class="form-input" style="width: 160px; height: 38px; font-size: 13px;">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="ti ti-arrow-right"></i></button>
                </form>
                <?php endif; ?>
                <button type="button" class="btn btn-accent btn-sm" onclick="toggleAddItemModal()">
                    <i class="ti ti-plus"></i> Add Item
                </button>
            </div>
        </div>

        <form id="entryForm">
            <input type="hidden" id="entry_date" value="<?= $date ?>">
            <div id="items-grid">
                <?php if (empty($items)): ?>
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <div class="empty-state-icon"><i class="ti ti-utensils"></i></div>
                    <div class="empty-state-title">No Items Available</div>
                    <p class="empty-state-desc">Add items to get started.</p>
                </div>
                <?php else: ?>
                <?php foreach ($items as $item): 
                    $name = $item['item_name'];
                    $rate = $item['rate'];
                    $cat = $item['category'];
                    $ex = $existing_data[$name] ?? null;
                    $qty = $ex ? (float)$ex['quantity'] : '';
                    $price = $ex ? (float)$ex['unit_price'] : (float)$rate;
                    $amount = $ex ? (float)$ex['amount'] : '';
                    $is_filled = $ex ? 'filled' : '';
                    $cat_class = $cat === 'sales' ? 'sales-card' : 'expense-card';
                ?>
                <div class="entry-card <?= $is_filled ?> <?= $cat_class ?> card-enter" style="--i: 1;" data-category="<?= $cat ?>">
                    <div class="entry-card-body">
                        <div class="entry-header">
                            <div class="entry-name">
                                <?= htmlspecialchars($name) ?>
                                <span class="entry-cat-badge <?= $cat ?>"><?= $cat ?></span>
                            </div>
                            <?php if($ex): ?><span class="entry-saved-badge"><i class="ti ti-check"></i> Saved</span><?php endif; ?>
                        </div>
                        <div class="entry-inputs">
                            <div>
                                <label>QTY</label>
                                <input type="number" step="any" class="form-input qty-input" data-name="<?= htmlspecialchars($name) ?>" value="<?= $qty ?>" placeholder="0">
                            </div>
                            <div>
                                <label>PRICE</label>
                                <input type="number" step="any" class="form-input price-input" data-name="<?= htmlspecialchars($name) ?>" value="<?= $price ?>" placeholder="0">
                            </div>
                            <div>
                                <label>AMOUNT</label>
                                <input type="number" step="any" class="form-input amount-input" data-name="<?= htmlspecialchars($name) ?>" value="<?= $amount ?>" data-category="<?= $cat ?>" placeholder="0">
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="card mt-xl">
                <div class="card-header">
                    <span class="card-title"><i class="ti ti-chart-bar"></i> Daily Summary</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <div class="flex-between">
                        <span class="text-muted">Total Income</span>
                        <span id="tot-income" class="font-bold text-success">₹0.00</span>
                    </div>
                    <div class="flex-between">
                        <span class="text-muted">Total Expense</span>
                        <span id="tot-expense" class="font-bold text-danger">₹0.00</span>
                    </div>
                    <hr style="border-color: var(--border); margin: 4px 0;">
                    <div class="flex-between">
                        <span style="font-weight: 700;">Net Profit / Loss</span>
                        <span id="net-profit" style="font-weight: 700; font-family:'Syne',sans-serif; font-size:18px;">₹0.00</span>
                    </div>
                </div>
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border);">
                    <h4 class="mb-md" style="font-size: 15px; font-weight: 600;">
                        <i class="ti ti-cash" style="color: var(--success);"></i> EOD Payment Received
                    </h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label class="form-label">Cash (₹)</label>
                            <input type="number" step="any" id="manual_cash" class="form-input" value="<?= htmlspecialchars($existing_payment ? (float)$existing_payment['cash_amount'] : '') ?>" placeholder="0.00">
                        </div>
                        <div>
                            <label class="form-label">GPay (₹)</label>
                            <input type="number" step="any" id="manual_gpay" class="form-input" value="<?= htmlspecialchars($existing_payment ? (float)$existing_payment['gpay_amount'] : '') ?>" placeholder="0.00">
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="button" class="btn btn-success btn-block mt-xl" style="height: 52px; font-size: 16px; font-family: 'Syne', sans-serif;" onclick="saveEntries()">
                <i class="ti ti-<?= $is_update ? 'refresh' : 'device-floppy' ?>"></i>
                <?= $is_update ? 'UPDATE DATA' : 'SAVE DATA' ?>
            </button>
        </form>
    </div>

    <div class="floating-summary mobile-only">
        <div><strong>Inc:</strong> <span id="mob-float-inc">₹0</span></div>
        <div><strong>Exp:</strong> <span id="mob-float-exp">₹0</span></div>
        <div><strong>Net:</strong> <span id="mob-float-net">₹0</span></div>
    </div>

    <nav id="floatingNav" class="floating-nav">
        <button class="nav-item active" data-index="0" onclick="setNav(0)">
            <i class="ti ti-pencil-plus"></i>
            <span class="nav-label">Entry</span>
        </button>
        <button class="nav-item" data-index="1" onclick="setNav(1)">
            <i class="ti ti-calendar-week"></i>
            <span class="nav-label">Weekly</span>
        </button>
        <button class="nav-item" data-index="2" onclick="setNav(2)">
            <i class="ti ti-calendar-month"></i>
            <span class="nav-label">Monthly</span>
        </button>
        <button class="nav-item" data-index="3" onclick="setNav(3)">
            <i class="ti ti-truck-delivery"></i>
            <span class="nav-label">Online</span>
        </button>
        <button class="nav-item" data-index="4" onclick="setNav(4)">
            <i class="ti ti-settings"></i>
            <span class="nav-label">Settings</span>
        </button>
    </nav>

    <script src="<?php echo BASE_URL; ?>assets/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            calculateTotals();
            document.querySelectorAll('.qty-input, .price-input, .amount-input').forEach(input => {
                input.addEventListener('input', handleInput);
            });
        });

        function handleInput(e) {
            const card = e.target.closest('.entry-card');
            if (!card) return;
            const qtyInput = card.querySelector('.qty-input');
            const priceInput = card.querySelector('.price-input');
            const amountInput = card.querySelector('.amount-input');
            if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
                const q = parseFloat(qtyInput.value) || 0;
                const p = parseFloat(priceInput.value) || 0;
                if (qtyInput.value !== '' || priceInput.value !== '') {
                    amountInput.value = (q * p).toFixed(2);
                }
            }
            if (amountInput.value && parseFloat(amountInput.value) > 0) {
                card.classList.add('filled');
            } else {
                card.classList.remove('filled');
            }
            calculateTotals();
        }

        function calculateTotals() {
            let totalSales = 0, totalExpense = 0;
            document.querySelectorAll('.amount-input').forEach(input => {
                const val = parseFloat(input.value) || 0;
                if (input.dataset.category === 'sales') totalSales += val;
                else if (input.dataset.category === 'expense') totalExpense += val;
            });
            const net = totalSales - totalExpense;
            const deskExpEl = document.getElementById('tot-expense');
            if (deskExpEl) deskExpEl.innerText = '₹' + totalExpense.toFixed(2);
            const deskIncEl = document.getElementById('tot-income');
            if (deskIncEl) deskIncEl.innerText = '₹' + totalSales.toFixed(2);
            const netEl = document.getElementById('net-profit');
            if (netEl) {
                netEl.innerText = '₹' + net.toFixed(2);
                netEl.style.color = net >= 0 ? 'var(--success)' : 'var(--danger)';
            }
            document.getElementById('mob-float-inc').innerText = '₹' + Math.round(totalSales);
            document.getElementById('mob-float-exp').innerText = '₹' + Math.round(totalExpense);
            document.getElementById('mob-float-net').innerText = '₹' + Math.round(net);
        }

        async function saveEntries() {
            const confirmed = await showConfirmModal("Are you sure you want to save today's data?", "Save", "Cancel");
            if (!confirmed) return;
            showLoader();
            const date = document.getElementById('entry_date').value;
            const manualCash = parseFloat(document.getElementById('manual_cash').value) || 0;
            const manualGpay = parseFloat(document.getElementById('manual_gpay').value) || 0;
            const entries = [];
            document.querySelectorAll('.entry-card').forEach(card => {
                const qtyInput = card.querySelector('.qty-input');
                if (!qtyInput) return;
                const name = qtyInput.dataset.name;
                const qty = qtyInput.value;
                const price = card.querySelector('.price-input').value;
                const amount = card.querySelector('.amount-input').value;
                if (amount && parseFloat(amount) > 0) {
                    entries.push({ item_name: name, qty: qty || 0, unit_price: price || 0, amount: amount, payment_mode: 'CASH' });
                }
            });
            try {
                const response = await fetch('../api/save_entry.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ entry_date: date, cash_amount: manualCash, gpay_amount: manualGpay, entries })
                });
                const result = await response.json();
                if (result.success) {
                    showToast(result.message);
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(result.message || 'Error saving data', 'error');
                }
            } catch (error) {
                showToast('Network error', 'error');
            } finally {
                hideLoader();
            }
        }

        function toggleAddItemModal() {
            const modal = document.getElementById('addItemModal');
            modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
        }
    </script>

    <div id="addItemModal" class="modal-overlay" style="display:none;">
        <div class="modal-content" style="max-width: 520px;">
            <div class="modal-header">
                <span class="modal-title">Add New Item</span>
                <button type="button" class="modal-close" onclick="toggleAddItemModal()">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="add_item">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Item Name</label>
                        <input type="text" name="item_name" required placeholder="e.g. Chicken Lollipop" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-input">
                            <option value="sales">Sales (Income)</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rate (₹)</label>
                        <input type="number" step="any" name="rate" placeholder="0.00" value="0.00" class="form-input">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" onclick="toggleAddItemModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-plus"></i> Add</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>