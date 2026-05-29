<?php
// pages/settings.php
require_once '../auth/session.php';
require_once '../config/db.php';
require_once '../config/constants.php';
check_auth();
check_role(['owner', 'branch_admin']);

$branch_id = $_SESSION['branch_id'];
$branch_name = $_SESSION['branch_name'];

// Auto-select first branch for owners with no branch set
if ($branch_id === null && $_SESSION['role'] === 'owner' && !empty($_SESSION['admin_branches'])) {
    $branch_id = $_SESSION['admin_branches'][0]['id'];
    $_SESSION['branch_id'] = $branch_id;
    $_SESSION['branch_name'] = $_SESSION['admin_branches'][0]['name'];
    $branch_name = $_SESSION['branch_name'];
}
$msg = $_GET['msg'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'save_rates') {
            if (isset($_POST['delete_item_id'])) {
                $del_id = $_POST['delete_item_id'];
                $stmt = $pdo->prepare("DELETE FROM item_rates WHERE id = ? AND branch_id = ?");
                $stmt->execute([$del_id, $branch_id]);
                $msg = "Item deleted successfully.";
            } elseif (isset($_POST['rates']) && is_array($_POST['rates'])) {
                foreach ($_POST['rates'] as $id => $rate) {
                    $stmt = $pdo->prepare("UPDATE item_rates SET rate = ? WHERE id = ? AND branch_id = ?");
                    $stmt->execute([$rate, $id, $branch_id]);
                }
                $msg = "Item rates updated successfully.";
            }
        } else if ($action === 'add_item') {
            $item_name = trim($_POST['item_name'] ?? '');
            $category = $_POST['category'] ?? 'sales';
            $rate = floatval($_POST['rate'] ?? 0);
            if (!empty($item_name)) {
                try {
                    $stmt = $pdo->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM item_rates WHERE branch_id = ?");
                    $stmt->execute([$branch_id]);
                    $next_sort = $stmt->fetchColumn();
                    $stmt = $pdo->prepare("INSERT INTO item_rates (branch_id, item_name, rate, category, sort_order) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$branch_id, $item_name, $rate, $category, $next_sort]);
                    $msg = "Item added successfully.";
                } catch (\PDOException $e) { $msg = "Error: Item already exists."; }
            } else { $msg = "Item name cannot be empty."; }
        } else if ($action === 'add_user') {
            $name = $_POST['name']; $username = $_POST['username'];
            $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $role = $_POST['role']; $allowed = $_POST['allowed_categories'] ?? 'all';
            try {
                $stmt = $pdo->prepare("INSERT INTO users (branch_id, name, username, password, role, allowed_categories) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$branch_id, $name, $username, $pass, $role, $allowed]);
                $msg = "User added successfully.";
            } catch (\PDOException $e) { $msg = "Error: Username might already exist."; }
        } else if ($action === 'update_user_permission') {
            $uid = $_POST['user_id']; $allowed = $_POST['allowed_categories'] ?? 'all';
            $stmt = $pdo->prepare("UPDATE users SET allowed_categories = ? WHERE id = ? AND branch_id = ?");
            $stmt->execute([$allowed, $uid, $branch_id]);
            if ($uid == $_SESSION['user_id']) $_SESSION['allowed_categories'] = $allowed;
            $msg = "User category access updated.";
        } else if ($action === 'toggle_user') {
            $uid = $_POST['user_id']; $status = $_POST['status'] == 1 ? 0 : 1;
            if ($uid != $_SESSION['user_id']) {
                $stmt = $pdo->prepare("UPDATE users SET is_active = ? WHERE id = ? AND branch_id = ?");
                $stmt->execute([$status, $uid, $branch_id]);
                $msg = "User status updated.";
            } else { $msg = "Cannot deactivate your own account."; }
        } else if ($action === 'add_branch' && $_SESSION['role'] === 'owner') {
            $bname = $_POST['branch_name']; $loc = $_POST['location'];
            $stmt = $pdo->prepare("INSERT INTO branches (name, location) VALUES (?, ?)");
            $stmt->execute([$bname, $loc]);
            $new_b_id = $pdo->lastInsertId();
            $stmt = $pdo->prepare("INSERT INTO item_rates (branch_id, item_name, rate, category, sort_order) SELECT ?, item_name, rate, category, sort_order FROM item_rates WHERE branch_id = 1");
            $stmt->execute([$new_b_id]);
            if(isset($_SESSION['admin_branches'])) $_SESSION['admin_branches'][] = ['id' => $new_b_id, 'name' => $bname];
            $msg = "Branch added successfully.";
        } else if ($action === 'delete_user') {
            $uid = $_POST['user_id'];
            if ($uid != $_SESSION['user_id']) {
                try {
                    $pdo->beginTransaction();
                    $stmt = $pdo->prepare("UPDATE daily_entries SET entered_by = NULL WHERE entered_by = ?"); $stmt->execute([$uid]);
                    $stmt = $pdo->prepare("UPDATE daily_payments SET entered_by = NULL WHERE entered_by = ?"); $stmt->execute([$uid]);
                    $stmt = $pdo->prepare("UPDATE online_sales SET entered_by = NULL WHERE entered_by = ?"); $stmt->execute([$uid]);
                    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND branch_id = ?"); $stmt->execute([$uid, $branch_id]);
                    $pdo->commit();
                    $msg = "User deleted. History preserved.";
                } catch (\Exception $e) { if ($pdo->inTransaction()) $pdo->rollBack(); $msg = "Error: ".$e->getMessage(); }
            } else { $msg = "Cannot delete your own account."; }
        } else if ($action === 'delete_branch' && $_SESSION['role'] === 'owner') {
            $del_id = $_POST['branch_id'];
            $stmt = $pdo->query("SELECT COUNT(*) FROM branches");
            $count = $stmt->fetchColumn();
            if ($count <= 1) { $msg = "Error: Cannot delete the only branch."; }
            else {
                try {
                    $pdo->beginTransaction();
                    foreach (['daily_entries','daily_payments','online_sales','item_rates','users'] as $t) {
                        $pdo->exec("DELETE FROM $t WHERE branch_id = $del_id");
                    }
                    $stmt = $pdo->prepare("DELETE FROM branches WHERE id = ?"); $stmt->execute([$del_id]);
                    $pdo->commit();
                    if (isset($_SESSION['admin_branches'])) {
                        $_SESSION['admin_branches'] = array_values(array_filter($_SESSION['admin_branches'], fn($b) => $b['id'] != $del_id));
                    }
                    if ($_SESSION['branch_id'] == $del_id && !empty($_SESSION['admin_branches'])) {
                        $_SESSION['branch_id'] = $_SESSION['admin_branches'][0]['id'];
                        $_SESSION['branch_name'] = $_SESSION['admin_branches'][0]['name'];
                        header("Location: settings.php?msg=" . urlencode("Branch deleted. Switched active branch."));
                        exit();
                    }
                    $msg = "Branch deleted successfully.";
                } catch (\Exception $e) { if ($pdo->inTransaction()) $pdo->rollBack(); $msg = "Error deleting branch."; }
            }
        }
    }
}

// Fetch Data
$stmt = $pdo->prepare("SELECT * FROM item_rates WHERE branch_id = ? ORDER BY sort_order"); $stmt->execute([$branch_id]); $items = $stmt->fetchAll();

// Auto-seed default items if table is empty
if (count($items) === 0 && $branch_id !== null) {
    $sort = 0;
    foreach (ITEMS_SALES as $name) {
        $sort++;
        $stmt = $pdo->prepare("INSERT IGNORE INTO item_rates (branch_id, item_name, rate, category, sort_order) VALUES (?, ?, ?, 'sales', ?)");
        $stmt->execute([$branch_id, $name, 0, $sort]);
    }
    foreach (ITEMS_EXPENSE as $name) {
        $sort++;
        $stmt = $pdo->prepare("INSERT IGNORE INTO item_rates (branch_id, item_name, rate, category, sort_order) VALUES (?, ?, ?, 'expense', ?)");
        $stmt->execute([$branch_id, $name, 0, $sort]);
    }
    $stmt = $pdo->prepare("SELECT * FROM item_rates WHERE branch_id = ? ORDER BY sort_order"); $stmt->execute([$branch_id]); $items = $stmt->fetchAll();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE branch_id = ?"); $stmt->execute([$branch_id]); $users = $stmt->fetchAll();
$branches = [];
if ($_SESSION['role'] === 'owner') { $stmt = $pdo->query("SELECT * FROM branches"); $branches = $stmt->fetchAll(); }

define('BASE_URL', (strpos($_SERVER['SCRIPT_NAME'] ?? '', '/yarahman') !== false) ? '/yarahman/' : '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Settings - YGR signature</title>
    
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
        .settings-page { max-width: 1000px; margin: 0 auto; }
        .tabs { display: flex; gap: 4px; margin-bottom: 24px; background: var(--surface); border-radius: var(--radius-lg); padding: 4px; border: 1px solid var(--border); }
        .tab-btn { flex: 1; padding: 10px 16px; border: none; background: transparent; border-radius: var(--radius-md); font-weight: 600; font-size: 13px; color: var(--text-secondary); cursor: pointer; transition: all var(--transition); display: flex; align-items: center; justify-content: center; gap: 6px; }
        .tab-btn:hover { background: var(--bg); }
        .tab-btn.active { background: var(--primary); color: white; }
        .tab-pane { display: none; }
        .tab-pane.active { display: block; }
        .staff-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 14px; }
        .staff-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-lg); display: flex; align-items: center; gap: 14px; padding: 14px 16px; transition: all var(--transition); }
        .staff-card:hover { border-color: var(--primary); box-shadow: var(--shadow-lg); transform: translateY(-2px); }
        .staff-card-img { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 2px solid var(--border-light); }
        .staff-card-body { flex: 1; min-width: 0; }
        .staff-card-name { font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 6px; }
        .staff-card-meta { font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .staff-card-actions { display: flex; gap: 4px; }
        .staff-badge-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
        .staff-badge-dot.active { background: var(--success); box-shadow: 0 0 0 3px rgba(30,123,75,0.2); }
        .staff-badge-dot.inactive { background: var(--danger); }
        .btn-icon-sm { width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all var(--transition); background: var(--surface); }
        .btn-icon-sm.warning:hover { background: var(--warning-light); border-color: var(--warning); color: var(--warning); }
        .btn-icon-sm.danger:hover { background: var(--danger-light); border-color: var(--danger); color: var(--danger); }
        .user-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border-light); }
        .user-row:last-child { border-bottom: none; }
        
        .item-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            position: relative;
        }
        
        .item-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary);
            box-shadow: 0 16px 32px rgba(13,40,24,0.15);
        }
        
        .item-card-image-wrap {
            width: 100%;
            height: 130px;
            overflow: hidden;
            background: linear-gradient(135deg, #0D2818, #1E7B4B);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .item-card-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s cubic-bezier(0.23, 1, 0.320, 1);
        }
        
        .item-card:hover .item-card-image-wrap img {
            transform: scale(1.12);
        }
        
        .item-card-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            font-size: 0.65rem;
            padding: 5px 10px;
            border-radius: 20px;
            color: white;
            border: 1px solid rgba(255,255,255,0.4);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 4px;
            backdrop-filter: blur(8px);
        }
        
        .item-card-body {
            padding: 14px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .item-card-name {
            font-weight: 700;
            font-size: 0.9375rem;
            color: #2D2D2D;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .item-card-category {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            width: fit-content;
        }
        
        .item-card-category.sales {
            background: rgba(30,123,75,0.1);
            color: var(--success);
        }
        
        .item-card-category.expense {
            background: rgba(220,38,38,0.1);
            color: var(--danger);
        }
        
        .item-card-price {
            font-weight: 700;
            font-size: 1.125rem;
            color: var(--primary);
        }
        
        .item-card-actions {
            display: flex;
            gap: 8px;
        }
        
        .item-card-action-btn {
            flex: 1;
            padding: 8px;
            border: 1.5px solid rgba(220,38,38,0.3);
            background: rgba(220,38,38,0.08);
            color: var(--danger);
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }
        
        .item-card-action-btn.delete:hover {
            background: rgba(220,38,38,0.12);
            border-color: var(--danger);
        }
        .item-card-upload-btn {
            position: absolute; bottom: 6px; left: 6px; z-index: 4; width: 30px; height: 30px;
            border-radius: 50%; border: 2px solid rgba(255,255,255,0.6); background: rgba(0,0,0,0.35);
            color: white; display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 14px; transition: all 0.2s ease; backdrop-filter: blur(4px);
        }
        .item-card-upload-btn:hover { background: var(--primary); border-color: white; transform: scale(1.1); }
        
        @media (max-width: 1023px) { .settings-page { padding: 16px; padding-bottom: 120px; } }
        @media (max-width: 480px) {
            .tabs { gap: 2px; padding: 3px; flex-wrap: wrap; }
            .tab-btn { font-size: 11px; padding: 8px 8px; flex: 1 1 auto; min-width: 0; }
            .tab-btn i { font-size: 16px; }
        }
        @media (max-width: 380px) {
            .tab-btn { font-size: 10px; padding: 6px 4px; gap: 3px; }
            .tab-btn i { font-size: 14px; }
        }
    </style>
    <script>
        function initSettingsTabs() {
            var tabs = document.getElementById('settingsTabs');
            if (!tabs) return;
            tabs.addEventListener('click', function(e) {
                var btn = e.target.closest('.tab-btn');
                if (!btn) return;
                var tabId = btn.getAttribute('data-tab');
                if (!tabId) return;
                document.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); });
                document.querySelectorAll('.tab-pane').forEach(function(p) { p.classList.remove('active'); });
                btn.classList.add('active');
                var pane = document.getElementById(tabId);
                if (pane) pane.classList.add('active');
            });
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSettingsTabs);
        } else {
            initSettingsTabs();
        }
        function confirmDeleteItem(btn) {
            var itemId = btn.value;
            showConfirmModal('Delete this item? This cannot be undone.', 'Delete', 'Cancel').then(function(confirmed) {
                if (confirmed) {
                    var f = document.getElementById('itemsForm');
                    var inp = document.createElement('input');
                    inp.type = 'hidden';
                    inp.name = 'delete_item_id';
                    inp.value = itemId;
                    f.appendChild(inp);
                    f.submit();
                }
            });
        }
        function uploadItemImage(input, itemName, itemId) {
            if (!input.files || !input.files[0]) return;
            var file = input.files[0];
            if (file.size > 2 * 1024 * 1024) { showToast('Image must be under 2MB', 'error'); return; }
            var formData = new FormData();
            formData.append('image', file);
            formData.append('item_name', itemName);
            showLoader();
            fetch('../api/upload_item_image.php', { method: 'POST', body: formData })
                .then(function(r) { return r.json(); })
                .then(function(res) {
                    if (res.success) {
                        showToast('Image uploaded!');
                        setTimeout(function() { window.location.reload(); }, 1000);
                    } else {
                        showToast(res.message || 'Upload failed', 'error');
                        hideLoader();
                    }
                })
                .catch(function() { showToast('Upload error', 'error'); hideLoader(); });
        }
    </script>
</head>
<body>
    <div class="loader-overlay"></div>

    <!-- ===== TOP HEADER (Desktop) ===== -->
    <div class="top-header">
        <div class="header-left">
            <div class="header-brand">
                <div class="header-brand-icon"><i class="ti ti-bowl-rice"></i></div>
                YGR signature
            </div>
            <nav class="header-center">
                <a href="dashboard.php" class="nav-link"><i class="ti ti-layout-dashboard"></i> Dashboard</a>
                <a href="daily_entry.php" class="nav-link"><i class="ti ti-pencil-plus"></i> Entry</a>
                <a href="weekly_report.php" class="nav-link"><i class="ti ti-file-analytics"></i> Weekly</a>
                <a href="monthly_report.php" class="nav-link"><i class="ti ti-calendar-stats"></i> Monthly</a>
                <a href="settings.php" class="nav-link active"><i class="ti ti-settings"></i> Settings</a>
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

    <!-- ===== MAIN CONTENT ===== -->
    <div class="content-area settings-page">
        <?php if($msg): ?>
        <div class="toast success" style="position:relative; margin-bottom:15px; opacity:1;">
            <i class="ti ti-circle-check"></i> <span><?= htmlspecialchars($msg) ?></span>
            <div class="toast-progress" style="animation-duration: 3s;"></div>
        </div>
        <?php endif; ?>

        <div class="mb-xl">
            <h1 class="text-2xl font-bold" style="font-family:'Syne',sans-serif;">Settings</h1>
            <p class="text-muted text-sm">Manage items, staff, and branches</p>
        </div>

        <!-- Tabs -->
        <div class="tabs" id="settingsTabs">
            <button class="tab-btn active" data-tab="tab1"><i class="ti ti-package"></i> Items</button>
            <button class="tab-btn" data-tab="tab2"><i class="ti ti-users"></i> Staff</button>
            <?php if($_SESSION['role'] === 'owner'): ?>
            <button class="tab-btn" data-tab="tab3"><i class="ti ti-building"></i> Branches</button>
            <?php endif; ?>
            <button class="tab-btn" data-tab="tab4"><i class="ti ti-info-circle"></i> About</button>
        </div>

        <!-- ===== TAB 1: ITEMS ===== -->
        <div id="tab1" class="tab-pane active">
            <div class="card mb-lg">
                <div class="card-header">
                    <span class="card-title"><i class="ti ti-plus-circle" style="color:var(--success);"></i> Add New Item</span>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="add_item">
                    <div style="display:flex; gap:16px; flex-wrap:wrap;">
                        <div class="form-group" style="flex:2; min-width:200px; margin-bottom:0;">
                            <label class="form-label">Item Name</label>
                            <input type="text" name="item_name" required placeholder="e.g. Chicken Lollipop" class="form-input">
                        </div>
                        <div class="form-group" style="flex:1; min-width:140px; margin-bottom:0;">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-input">
                                <option value="sales">Sales (Income)</option>
                                <option value="expense">Expense</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex:1; min-width:120px; margin-bottom:0;">
                            <label class="form-label">Rate (₹)</label>
                            <input type="number" step="any" name="rate" value="0.00" class="form-input">
                        </div>
                        <div style="display:flex;align-items:flex-end;">
                            <button type="submit" class="btn btn-primary"><i class="ti ti-plus"></i> Add Item</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="ti ti-grid-dots"></i> All Items</span>
                    <span class="text-muted text-sm"><?= count($items) ?> items</span>
                </div>
                <form method="POST" id="itemsForm">
                    <input type="hidden" name="action" value="save_rates">
                    <?php if (count($items) === 0): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="ti ti-package"></i></div>
                        <div class="empty-state-title">No Items Yet</div>
                        <div class="empty-state-desc">Add your first item above to get started.</div>
                    </div>
                    <?php else: ?>
                    <div class="items-grid">
                        <?php foreach($items as $it): ?>
                        <div class="item-card">
                            <div class="item-card-image-wrap" style="display:flex;align-items:center;justify-content:center;">
                                <?php if ($it['category'] === 'sales'): ?>
                                <div class="item-card-fallback" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#0D2818,#1E7B4B);color:white;font-size:36px;font-weight:800;font-family:'Syne',sans-serif;z-index:1;">
                                    <?= strtoupper(substr($it['item_name'], 0, 1)) ?>
                                </div>
                                <?php $img = getFoodImageUrl($it['item_name'], $it['category']); ?>
                                <?php if ($img): ?>
                                <img src="<?= $img ?>" alt="<?= htmlspecialchars($it['item_name']) ?>" loading="lazy"
                                     onload="this.style.opacity='1'"
                                     onerror="this.remove()"
                                     style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0;transition:opacity 0.4s;z-index:2;">
                                <?php endif; ?>
                                <?php else: ?>
                                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#DC2626,#EF4444);color:white;font-size:36px;">
                                    <i class="ti ti-receipt"></i>
                                </div>
                                <?php endif; ?>
                                <span class="item-card-badge" style="background:<?= $it['category']==='sales' ? 'rgba(30,123,75,0.9)' : 'rgba(220,38,38,0.9)' ?>;z-index:3;">
                                    <i class="ti ti-<?= $it['category']==='sales' ? 'trending-up' : 'trending-down' ?>"></i>
                                    <?= ucfirst($it['category']) ?>
                                </span>
                                <button type="button" class="item-card-upload-btn" onclick="document.getElementById('upload_<?= $it['id'] ?>').click()" title="Upload Image"><i class="ti ti-camera"></i></button>
                                <input type="file" id="upload_<?= $it['id'] ?>" accept="image/jpeg,image/png,image/webp,image/gif" style="display:none" onchange="uploadItemImage(this, '<?= htmlspecialchars($it['item_name'], ENT_QUOTES) ?>', <?= $it['id'] ?>)">
                            </div>
                            <div class="item-card-body">
                                <div class="item-card-name"><?= htmlspecialchars($it['item_name']) ?></div>
                                <div class="item-card-category <?= $it['category'] ?>"><?= $it['category'] ?></div>
                                <div class="item-card-price">₹<?= number_format($it['rate'], 2) ?></div>
                                <div class="item-card-actions">
                                    <button type="button" value="<?= $it['id'] ?>" class="item-card-action-btn delete" onclick="confirmDeleteItem(this)"><i class="ti ti-trash"></i></button>
                                </div>
                            </div>
                            <div style="padding:0 14px 14px;">
                                <input type="number" step="any" name="rates[<?= $it['id'] ?>]" value="<?= $it['rate'] ?>" class="form-input" style="text-align:center;" placeholder="₹ Rate">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-success btn-block mt-lg" style="font-family:'Syne',sans-serif;">
                        <i class="ti ti-device-floppy"></i> Save All Rates
                    </button>
                </form>
            </div>
        </div>

        <!-- ===== TAB 2: STAFF ===== -->
        <div id="tab2" class="tab-pane">
            <div class="card mb-lg">
                <div class="card-header">
                    <span class="card-title"><i class="ti ti-user-plus" style="color:var(--success);"></i> Add Staff Member</span>
                </div>
                <form method="POST" style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
                    <input type="hidden" name="action" value="add_user">
                    <div class="form-group" style="flex:1; min-width:150px; margin-bottom:0;">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" required class="form-input">
                    </div>
                    <div class="form-group" style="flex:1; min-width:130px; margin-bottom:0;">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" required class="form-input">
                    </div>
                    <div class="form-group" style="flex:1; min-width:130px; margin-bottom:0;">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" required class="form-input">
                    </div>
                    <div class="form-group" style="flex:1; min-width:120px; margin-bottom:0;">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-input">
                            <option value="staff">Staff</option>
                            <option value="branch_admin">Branch Admin</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex:1; min-width:150px; margin-bottom:0;">
                        <label class="form-label">Access</label>
                        <select name="allowed_categories" class="form-input">
                            <option value="all">All Categories</option>
                            <option value="sales">Sales Only</option>
                            <option value="expense">Expense Only</option>
                        </select>
                    </div>
                    <div style="width:100%;"><button type="submit" class="btn btn-primary"><i class="ti ti-plus"></i> Add User</button></div>
                </form>
            </div>

            <div class="card" style="padding:0;overflow:hidden;">
                <div class="card-header" style="padding:16px 20px;margin:0;">
                    <span class="card-title"><i class="ti ti-users"></i> Staff Members</span>
                    <span class="text-muted text-sm"><?= count($users) ?> members</span>
                </div>
                <div style="padding:16px 20px;">
                    <?php if(count($users) > 0): ?>
                    <div class="staff-grid">
                        <?php foreach($users as $ui => $u): 
                            $default_imgs = [
                                'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=120&q=80',
                                'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=120&q=80',
                                'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=120&q=80',
                                'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=120&q=80',
                                'https://images.unsplash.com/photo-1603360946369-dc9bb6258143?w=120&q=80',
                                'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=120&q=80',
                                'https://images.unsplash.com/photo-1604503468506-a8da13d82791?w=120&q=80',
                                'https://images.unsplash.com/photo-1558030006-450675393462?w=120&q=80',
                            ];
                            $img_idx = $ui % count($default_imgs);
                            $role_class = $u['role']==='owner' ? 'badge-amber' : ($u['role']==='branch_admin' ? 'badge-blue' : 'badge-green');
                        ?>
                        <div class="staff-card card-enter" style="--i:<?= $ui ?>;">
                            <img src="<?= $default_imgs[$img_idx] ?>" alt="" class="staff-card-img" loading="lazy">
                            <div class="staff-card-body">
                                <div class="staff-card-name">
                                    <?= htmlspecialchars($u['name']) ?>
                                    <span class="staff-badge-dot <?= $u['is_active'] ? 'active' : 'inactive' ?>"></span>
                                </div>
                                <div class="staff-card-meta">
                                    <span class="badge <?= $role_class ?>" style="font-size:10px;"><?= ucfirst($u['role']) ?></span>
                                    <span>@<?= htmlspecialchars($u['username']) ?></span>
                                    <span style="color:<?= $u['is_active'] ? 'var(--success)' : 'var(--danger)' ?>;"><?= $u['is_active'] ? 'Active' : 'Inactive' ?></span>
                                </div>
                            </div>
                            <div class="staff-card-actions">
                                <?php if($u['id'] != $_SESSION['user_id']): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="toggle_user">
                                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                    <input type="hidden" name="status" value="<?= $u['is_active'] ?>">
                                    <button class="btn-icon-sm warning" title="<?= $u['is_active'] ? 'Deactivate' : 'Activate' ?>"><i class="ti ti-<?= $u['is_active'] ? 'player-pause' : 'player-play' ?>"></i></button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete_user">
                                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                    <button class="btn-icon-sm danger" title="Delete"><i class="ti ti-trash"></i></button>
                                </form>
                                <?php else: ?>
                                <span style="font-size:12px;color:var(--text-muted);"><i class="ti ti-shield-check" style="color:var(--success);"></i></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="empty-state"><div class="empty-state-icon"><i class="ti ti-users"></i></div><div class="empty-state-title">No Staff Yet</div></div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if(count($users) > 0): ?>
            <div class="card mt-lg">
                <div class="card-header">
                    <span class="card-title"><i class="ti ti-lock" style="color:var(--warning);"></i> Permissions</span>
                </div>
                <?php foreach($users as $u): ?>
                <div class="user-row" style="<?= $u['id'] == $_SESSION['user_id'] ? 'opacity:0.5;' : '' ?>">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:var(--primary);color:white;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:12px;flex-shrink:0;">
                            <?= strtoupper(substr($u['name'], 0, 2)) ?>
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:14px;"><?= htmlspecialchars($u['name']) ?></div>
                            <div style="font-size:12px;color:var(--text-muted);">Category Access</div>
                        </div>
                    </div>
                    <div>
                        <?php if($u['id'] != $_SESSION['user_id']): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="update_user_permission">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <select name="allowed_categories" onchange="this.form.submit()" class="form-input" style="width:120px;height:32px;padding:4px 8px;font-size:12px;">
                                <option value="all" <?= ($u['allowed_categories']??'all')==='all' ? 'selected' : '' ?>>All</option>
                                <option value="sales" <?= ($u['allowed_categories']??'all')==='sales' ? 'selected' : '' ?>>Sales</option>
                                <option value="expense" <?= ($u['allowed_categories']??'all')==='expense' ? 'selected' : '' ?>>Expense</option>
                            </select>
                        </form>
                        <?php else: ?>
                        <span style="font-size:12px;color:var(--text-muted);">Current user</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- ===== TAB 3: BRANCHES ===== -->
        <?php if($_SESSION['role'] === 'owner'): ?>
        <div id="tab3" class="tab-pane">
            <div class="card mb-lg">
                <div class="card-header">
                    <span class="card-title"><i class="ti ti-building-plus" style="color:var(--success);"></i> Add Branch</span>
                </div>
                <form method="POST" style="display:flex; gap:12px; align-items:flex-end;">
                    <input type="hidden" name="action" value="add_branch">
                    <div class="form-group" style="flex:1; margin-bottom:0;">
                        <label class="form-label">Branch Name</label>
                        <input type="text" name="branch_name" required class="form-input">
                    </div>
                    <div class="form-group" style="flex:1; margin-bottom:0;">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-input">
                    </div>
                    <button type="submit" class="btn btn-primary" style="height:44px;"><i class="ti ti-plus"></i> Add</button>
                </form>
            </div>
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="ti ti-building"></i> All Branches</span>
                </div>
                <?php foreach($branches as $b): ?>
                <div class="user-row">
                    <div>
                        <div style="font-weight:600;font-size:14px;"><?= htmlspecialchars($b['name']) ?></div>
                        <div style="font-size:12px;color:var(--text-muted);"><?= htmlspecialchars($b['location']) ?> • ID: <?= $b['id'] ?></div>
                    </div>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete_branch">
                        <input type="hidden" name="branch_id" value="<?= $b['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-ghost" style="color:var(--danger);"><i class="ti ti-trash"></i> Delete</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- ===== TAB 4: ABOUT ===== -->
        <div id="tab4" class="tab-pane">
            <div class="premium-card mb-lg">
                <div class="premium-card-content">
                    <div style="width:72px;height:72px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i class="ti ti-bowl-rice" style="font-size:32px;color:white;"></i>
                    </div>
                    <div class="premium-card-label">YGR signature Shop Manager</div>
                    <div style="font-family:'Syne',sans-serif;font-size:28px;font-weight:800;margin-bottom:8px;">v2.0 Premium</div>
                    <p style="opacity:0.8;">Your restaurant, managed brilliantly.</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="ti ti-info-circle" style="color:var(--info);"></i> About This Project</span>
                </div>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div style="display:flex;align-items:flex-start;gap:12px;padding:8px 0;border-bottom:1px solid var(--border-light);">
                        <div style="width:36px;height:36px;background:rgba(13,40,24,0.08);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="ti ti-app-window" style="color:var(--primary);"></i></div>
                        <div><div style="font-weight:600;font-size:14px;">Application</div><div style="font-size:13px;color:var(--text-muted);">YGR signature Shop Manager - Premium Edition</div></div>
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:12px;padding:8px 0;border-bottom:1px solid var(--border-light);">
                        <div style="width:36px;height:36px;background:rgba(30,123,75,0.08);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="ti ti-stack-2" style="color:var(--success);"></i></div>
                        <div><div style="font-weight:600;font-size:14px;">Version</div><div style="font-size:13px;color:var(--text-muted);">2.0 Premium — <?= date('Y') ?></div></div>
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:12px;padding:8px 0;">
                        <div style="width:36px;height:36px;background:rgba(59,130,246,0.08);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="ti ti-mail" style="color:var(--info);"></i></div>
                        <div><div style="font-weight:600;font-size:14px;">Tech Stack</div><div style="font-size:13px;color:var(--text-muted);">PHP 8.2 • MySQL • Chart.js • Tabler Icons • Tailwind CSS</div></div>
                    </div>
                </div>
            </div>
            <!-- Logout -->
            <a href="<?php echo BASE_URL; ?>logout.php" class="btn btn-danger btn-block mt-lg" style="height:54px;font-size:16px;font-family:'Syne',sans-serif;border-radius:var(--radius-lg);">
                <i class="ti ti-logout" style="font-size:20px;"></i> Logout
            </a>
        </div>
    </div>

    <!-- ===== FLOATING PILL NAVBAR (Mobile) ===== -->
    <nav id="floatingNav" class="floating-nav">
        <button class="nav-item" data-index="0" onclick="setNav(0)"><i class="ti ti-pencil-plus"></i><span class="nav-label">Entry</span></button>
        <button class="nav-item" data-index="1" onclick="setNav(1)"><i class="ti ti-calendar-week"></i><span class="nav-label">Weekly</span></button>
        <button class="nav-item" data-index="2" onclick="setNav(2)"><i class="ti ti-calendar-month"></i><span class="nav-label">Monthly</span></button>
        <button class="nav-item" data-index="3" onclick="setNav(3)"><i class="ti ti-truck-delivery"></i><span class="nav-label">Online</span></button>
        <button class="nav-item active" data-index="4" onclick="setNav(4)"><i class="ti ti-settings"></i><span class="nav-label">Settings</span></button>
    </nav>

    <script src="<?php echo BASE_URL; ?>assets/js/app.js"></script>
</body>
</html>