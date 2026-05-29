<?php
// pages/monthly_report.php
require_once '../auth/session.php';
require_once '../config/constants.php';
check_auth();
check_role(['owner', 'branch_admin']);

$month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
define('BASE_URL', (strpos($_SERVER['SCRIPT_NAME'] ?? '', '/yarahman') !== false) ? '/yarahman/' : '/');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Monthly Report - YGR signature</title>
    
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .report-page { max-width: 1280px; margin: 0 auto; }
        .report-table { width: 100%; border-collapse: collapse; }
        .report-table th, .report-table td { border: 1px solid var(--border); padding: 6px; text-align: right; font-size: 11px; }
        .report-table th { background: var(--primary); color: #fff; text-align: center; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .cat-sales { background: rgba(21, 128, 61, 0.03); }
        .cat-exp { background: rgba(220, 38, 38, 0.03); }
        .footer-row td { font-weight: 700; background: var(--bg-alt) !important; }
        @media print { .no-print { display: none !important; } body { background: white; } }
        @media (min-width: 1024px) {
            .report-table th { position: sticky; top: 0; z-index: 5; }
            .report-table td:nth-child(1), .report-table th:nth-child(1) { position: sticky; left: 0; background: #EAF3E4; z-index: 3; min-width: 30px; }
            .report-table td:nth-child(2), .report-table th:nth-child(2) { position: sticky; left: 30px; background: #EAF3E4; z-index: 3; min-width: 70px; }
            .report-table td:nth-child(3), .report-table th:nth-child(3) { position: sticky; left: 100px; background: #EAF3E4; z-index: 3; min-width: 40px; border-right: 2px solid var(--primary); }
            .report-table th:nth-child(1), .report-table th:nth-child(2), .report-table th:nth-child(3) { background: var(--primary); z-index: 6; }
        }
        @media (max-width: 1023px) {
            .report-page { padding: 16px; padding-bottom: 120px; }
            .table-container { max-height: none !important; }
            .report-table th, .report-table td { font-size: 10px; padding: 4px; white-space: nowrap; }
            .report-table td:nth-child(2) { font-size: 9px; }
        }
    </style>
</head>
<body>
    <div class="loader-overlay"></div>

    <!-- ===== TOP HEADER (Desktop) ===== -->
    <div class="top-header no-print">
        <div class="header-left">
            <div class="header-brand">
                <div class="header-brand-icon"><i class="ti ti-bowl-rice"></i></div>
                YGR signature
            </div>
            <nav class="header-center">
                <a href="dashboard.php" class="nav-link"><i class="ti ti-layout-dashboard"></i> Dashboard</a>
                <a href="daily_entry.php" class="nav-link"><i class="ti ti-pencil-plus"></i> Entry</a>
                <a href="weekly_report.php" class="nav-link"><i class="ti ti-file-analytics"></i> Weekly</a>
                <a href="monthly_report.php" class="nav-link active"><i class="ti ti-calendar-stats"></i> Monthly</a>
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

    <!-- ===== MAIN CONTENT ===== -->
    <div class="content-area report-page">
        <div class="flex-between mb-xl no-print" style="flex-wrap:wrap;gap:12px;">
            <div>
                <h1 class="text-2xl font-bold" style="font-family:'Syne',sans-serif;">Monthly Report</h1>
                <p class="text-muted text-sm">Full month financial breakdown</p>
            </div>
            <form method="GET" style="display:flex; gap:12px; align-items:flex-end;flex-wrap:wrap;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Month</label>
                    <input type="month" name="month" id="month_input" value="<?= $month ?>" class="form-input" style="width: 220px;">
                </div>
                <button type="submit" class="btn btn-primary"><i class="ti ti-refresh"></i> Generate</button>
            </form>
        </div>

        <!-- Top Items Grid -->
        <div id="topItemsGrid" class="card" style="display: none; margin-bottom: 16px;">
            <h3 style="font-family:'Syne',sans-serif; font-size:18px; margin-bottom:16px;">
                <i class="ti ti-flame" style="color:var(--accent);"></i> Top Selling Items
            </h3>
            <div id="gridContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px;"></div>
        </div>

        <!-- Main Report Table -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div class="table-container" style="max-height: 65vh; border: none;">
                <table class="report-table" id="reportTable">
                    <thead><tr id="thead-row"></tr></thead>
                    <tbody id="tbody-items"></tbody>
                    <tfoot id="tfoot-row"></tfoot>
                </table>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-xl">
            <div class="card" style="background: rgba(45, 80, 22, 0.04); border-color: rgba(45, 80, 22, 0.15);">
                <h3 class="font-bold mb-md" style="font-family:'Syne',sans-serif; font-size:16px;">
                    <i class="ti ti-shopping-cart" style="color:var(--primary);"></i> Online Sales
                </h3>
                <div class="flex-between mb-sm"><span class="text-muted">Swiggy</span> <strong id="sum-swiggy">₹0</strong></div>
                <div class="flex-between mb-sm"><span class="text-muted">Zomato</span> <strong id="sum-zomato">₹0</strong></div>
                <hr style="margin: 8px 0; border-color: var(--border);">
                <div class="flex-between"><span style="font-weight:700;">Total Online</span> <strong id="sum-online" style="color:var(--warning);">₹0</strong></div>
            </div>
            <div class="card" style="background: rgba(45, 80, 22, 0.04); border-color: rgba(45, 80, 22, 0.15);">
                <h3 class="font-bold mb-md" style="font-family:'Syne',sans-serif; font-size:16px;">
                    <i class="ti ti-report-money" style="color:var(--success);"></i> Financial Summary
                </h3>
                <div class="flex-between mb-sm"><span class="text-muted">Total Income</span> <strong id="fin-income">₹0</strong></div>
                <div class="flex-between mb-sm"><span class="text-muted">Total Expense</span> <strong id="fin-exp">₹0</strong></div>
                <hr style="margin: 8px 0; border-color: var(--border);">
                <div class="flex-between"><span style="font-weight:700;">Net Profit</span> <strong id="fin-net" style="color:var(--success);">₹0</strong></div>
            </div>
        </div>

        <!-- ===== ONLINE SALES ANALYTICS SECTION ===== -->
        <div id="onlineAnalytics" class="mt-xl" style="display: none;">
            <h2 class="text-xl font-bold mb-lg" style="font-family:'Syne',sans-serif;">
                <i class="ti ti-analytics" style="color:var(--swiggy);"></i> Online Sales Analytics
            </h2>

            <!-- Month Summary Banner -->
            <div class="online-banner mb-lg" id="monthOnlineBanner">
                <div class="online-banner-content">
                    <div class="online-banner-title" id="onlineBannerTitle">Online Deliveries</div>
                    <div class="online-banner-stats">
                        <div class="online-banner-stat">
                            <div class="online-banner-stat-label">Total</div>
                            <div class="online-banner-stat-value" id="onlineTotalBanner">₹0</div>
                        </div>
                        <div class="online-banner-stat">
                            <div class="online-banner-stat-label">Swiggy</div>
                            <div class="online-banner-stat-value" id="onlineSwiggyBanner" style="color:#FF5200;">₹0</div>
                        </div>
                        <div class="online-banner-stat">
                            <div class="online-banner-stat-label">Zomato</div>
                            <div class="online-banner-stat-value" id="onlineZomatoBanner" style="color:#E23744;">₹0</div>
                        </div>
                        <div class="online-banner-stat">
                            <div class="online-banner-stat-label">Orders</div>
                            <div class="online-banner-stat-value" id="onlineOrdersBanner">0</div>
                        </div>
                        <div class="online-banner-stat">
                            <div class="online-banner-stat-label">Avg/Day</div>
                            <div class="online-banner-stat-value" id="onlineAvgBanner">₹0</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weekly Breakdown Cards -->
            <div class="mb-lg">
                <h3 class="font-bold mb-md" style="font-family:'Syne',sans-serif; font-size:15px;">Weekly Breakdown</h3>
                <div class="weekly-breakdown-cards" id="weeklyCards"></div>
            </div>

            <!-- Day-by-Day Online Table -->
            <div class="card mb-lg" style="padding: 0; overflow: hidden;">
                <div class="card-header" style="padding: 16px 20px; margin-bottom: 0;">
                    <span class="card-title"><i class="ti ti-table"></i> Daily Online Sales</span>
                </div>
                <div class="table-container" style="border: none;">
                    <table class="report-table" id="monthOnlineTable">
                        <thead>
                            <tr>
                                <th style="position:sticky;left:0;z-index:6;min-width:50px;">Date</th>
                                <th style="min-width:40px;">Day</th>
                                <th style="background:#FF5200;min-width:70px;">Swiggy</th>
                                <th style="background:#E23744;min-width:70px;">Zomato</th>
                                <th style="min-width:70px;">Total</th>
                                <th style="min-width:70px;">% of Sales</th>
                            </tr>
                        </thead>
                        <tbody id="monthOnlineTbody"></tbody>
                    </table>
                </div>
            </div>

            <!-- Platform Trend Line Chart -->
            <div class="chart-container mb-lg" id="trendChartContainer">
                <div class="chart-title">Daily Online Revenue Trend</div>
                <canvas id="trendChart"></canvas>
            </div>

            <!-- Heatmap Calendar -->
            <div class="card mb-lg">
                <div class="card-header">
                    <span class="card-title"><i class="ti ti-calendar-heat"></i> Online Sales Heatmap</span>
                </div>
                <div id="heatmapContainer" class="flex-center" style="flex-direction:column;"></div>
            </div>
        </div>
    </div>

    <!-- ===== FLOATING PILL NAVBAR (Mobile) ===== -->
    <nav id="floatingNav" class="floating-nav">
        <button class="nav-item" data-index="0" onclick="setNav(0)"><i class="ti ti-pencil-plus"></i><span class="nav-label">Entry</span></button>
        <button class="nav-item" data-index="1" onclick="setNav(1)"><i class="ti ti-calendar-week"></i><span class="nav-label">Weekly</span></button>
        <button class="nav-item active" data-index="2" onclick="setNav(2)"><i class="ti ti-calendar-month"></i><span class="nav-label">Monthly</span></button>
        <button class="nav-item" data-index="3" onclick="setNav(3)"><i class="ti ti-truck-delivery"></i><span class="nav-label">Online</span></button>
        <button class="nav-item" data-index="4" onclick="setNav(4)"><i class="ti ti-settings"></i><span class="nav-label">Settings</span></button>
    </nav>

    <script src="<?php echo BASE_URL; ?>assets/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', loadReport);

        function getFoodImg(name, category = 'sales') {
            const initial = (name.trim().charAt(0) || '?').toUpperCase();
            const c1 = category === 'sales' ? '#0D2818' : '#DC2626';
            const c2 = category === 'sales' ? '#1E7B4B' : '#EF4444';
            const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300">`
                + `<defs><linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="100%">`
                + `<stop offset="0%" style="stop-color:${c1}"/>`
                + `<stop offset="100%" style="stop-color:${c2}"/>`
                + `</linearGradient></defs>`
                + `<rect width="400" height="300" fill="url(#g)"/>`
                + `<text x="200" y="155" font-family="Syne,sans-serif" font-size="80" font-weight="800" fill="white" text-anchor="middle" dominant-baseline="middle">${initial}</text>`
                + `</svg>`;
            return 'data:image/svg+xml;base64,' + btoa(svg);
        }

        async function loadReport() {
            showLoader();
            const month = document.getElementById('month_input').value;
            try {
                const res = await fetch(`../api/get_report.php?type=monthly&month=${month}`);
                const data = await res.json();
                if (data.success) {
                    renderTable(data);
                    renderTopItems(data);
                    renderOnlineAnalytics(data);
                } else showToast(data.message, 'error');
            } catch (e) {
                showToast('Error loading report', 'error');
            } finally {
                hideLoader();
            }
        }

        function renderTopItems(data) {
            const grid = document.getElementById('topItemsGrid');
            const container = document.getElementById('gridContainer');
            if (!grid || !container) return;
            const itemTotals = {};
            data.items.forEach(it => itemTotals[it.item_name] = 0);
            Object.values(data.data).forEach(dayData => {
                Object.entries(dayData.items).forEach(([name, amt]) => { itemTotals[name] = (itemTotals[name] || 0) + amt; });
            });
            const items = Object.entries(itemTotals)
                .filter(([name]) => data.items.find(it => it.item_name === name && it.category === 'sales'))
                .map(([name, total]) => ({ name, total }))
                .sort((a, b) => b.total - a.total).slice(0, 8);
            if (items.length === 0) { grid.style.display = 'none'; return; }
            grid.style.display = 'block';
            container.innerHTML = items.map(it => `
                <div class="top-item-card">
                    <img src="${getFoodImg(it.name)}" alt="" class="top-item-img" loading="lazy">
                    <div class="top-item-info">
                        <div class="top-item-name">${it.name}</div>
                        <div class="top-item-amount">₹${it.total.toFixed(2)}</div>
                    </div>
                </div>
            `).join('');
        }

        function renderTable(data) {
            let th = `<th style="width:30px;">#</th><th style="width:70px;">Date</th><th style="width:40px;">Day</th>`;
            data.items.forEach(it => {
                th += `<th><span class="report-th-item"><img src="${getFoodImg(it.item_name)}" alt="" class="report-thumb-img" loading="lazy">${it.item_name}</span></th>`;
            });
            th += `<th>Cash</th><th>GPay</th><th>Swiggy</th><th>Zomato</th><th>Exp</th><th>Net</th>`;
            document.getElementById('thead-row').innerHTML = th;

            let tb = '';
            let itemTotals = {};
            data.items.forEach(it => itemTotals[it.item_name] = 0);
            let totCash = 0, totGpay = 0, monthTotExp = 0, monthTotNet = 0;
            const dayNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];

            data.days.forEach((d, idx) => {
                const dObj = new Date(d + 'T00:00:00');
                const dayName = dayNames[dObj.getDay()];
                const dayData = data.data[d];
                let dayTotExp = 0;
                let rowHtml = `<tr><td class="text-center" style="font-weight:600;">${idx + 1}</td><td>${d}</td><td>${dayName}</td>`;
                data.items.forEach(it => {
                    const amt = dayData.items[it.item_name] || 0;
                    itemTotals[it.item_name] += amt;
                    if (it.category === 'expense') dayTotExp += amt;
                    rowHtml += `<td class="${it.category==='sales' ? 'cat-sales' : 'cat-exp'}">${amt > 0 ? amt.toFixed(2) : '-'}</td>`;
                });
                const c = dayData.cash, g = dayData.gpay;
                const s = dayData.swiggy || 0, z = dayData.zomato || 0;
                totCash += c; totGpay += g; monthTotExp += dayTotExp;
                const dayNet = (c + g + s + z) - dayTotExp;
                monthTotNet += dayNet;
                rowHtml += `<td>${c.toFixed(2)}</td><td>${g.toFixed(2)}</td><td>${s.toFixed(2)}</td><td>${z.toFixed(2)}</td><td style="color:var(--danger);">${dayTotExp.toFixed(2)}</td><td style="font-weight:700;color:${dayNet>=0?'var(--success)':'var(--danger)'};">${dayNet.toFixed(2)}</td></tr>`;
                tb += rowHtml;
            });
            document.getElementById('tbody-items').innerHTML = tb;

            let tf = `<tr class="footer-row"><td colspan="3" class="text-center" style="font-weight:700;">MONTH TOTAL</td>`;
            data.items.forEach(it => {
                tf += `<td><span class="report-th-item"><img src="${getFoodImg(it.item_name)}" alt="" class="report-thumb-img" loading="lazy">${itemTotals[it.item_name].toFixed(2)}</span></td>`;
            });
            tf += `<td>${totCash.toFixed(2)}</td><td>${totGpay.toFixed(2)}</td><td>${data.swiggy.toFixed(2)}</td><td>${data.zomato.toFixed(2)}</td><td style="color:var(--danger);">${monthTotExp.toFixed(2)}</td><td style="font-weight:700;color:${monthTotNet>=0?'var(--success)':'var(--danger)'};">${monthTotNet.toFixed(2)}</td></tr>`;
            document.getElementById('tfoot-row').innerHTML = tf;

            document.getElementById('sum-swiggy').innerText = '₹' + Math.round(data.swiggy).toLocaleString();
            document.getElementById('sum-zomato').innerText = '₹' + Math.round(data.zomato).toLocaleString();
            document.getElementById('sum-online').innerText = '₹' + Math.round(data.swiggy + data.zomato).toLocaleString();

            const totInc = totCash + totGpay + data.swiggy + data.zomato;
            const netProf = totInc - monthTotExp;
            document.getElementById('fin-income').innerText = '₹' + Math.round(totInc).toLocaleString();
            document.getElementById('fin-exp').innerText = '₹' + Math.round(monthTotExp).toLocaleString();
            const netEl = document.getElementById('fin-net');
            netEl.innerText = '₹' + Math.round(netProf).toLocaleString();
            netEl.style.color = netProf >= 0 ? 'var(--success)' : 'var(--danger)';
        }

        // ===== ONLINE ANALYTICS =====
        function renderOnlineAnalytics(data) {
            const section = document.getElementById('onlineAnalytics');
            let hasOnline = false;
            data.days.forEach(d => {
                const dd = data.data[d];
                if ((dd.swiggy || 0) > 0 || (dd.zomato || 0) > 0) hasOnline = true;
            });
            if (!hasOnline) { section.style.display = 'none'; return; }
            section.style.display = 'block';

            // Month/Year for banner
            const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            const [year, monthNum] = document.getElementById('month_input').value.split('-');
            document.getElementById('onlineBannerTitle').innerText = `Online Deliveries — ${monthNames[parseInt(monthNum)-1]} ${year}`;

            // Calculate totals
            let totS = 0, totZ = 0, orderDays = 0;
            const dayTotals = {};
            data.days.forEach(d => {
                const dd = data.data[d];
                const s = dd.swiggy || 0;
                const z = dd.zomato || 0;
                dayTotals[d] = { s, z, total: s + z };
                totS += s; totZ += z;
                if (s + z > 0) orderDays++;
            });
            const total = totS + totZ;
            const avg = data.days.length > 0 ? total / data.days.length : 0;
            document.getElementById('onlineTotalBanner').innerText = '₹' + Math.round(total).toLocaleString();
            document.getElementById('onlineSwiggyBanner').innerText = '₹' + Math.round(totS).toLocaleString();
            document.getElementById('onlineZomatoBanner').innerText = '₹' + Math.round(totZ).toLocaleString();
            document.getElementById('onlineOrdersBanner').innerText = orderDays;
            document.getElementById('onlineAvgBanner').innerText = '₹' + Math.round(avg).toLocaleString();

            // Weekly breakdown
            const weeks = [[], [], [], []];
            data.days.forEach((d, idx) => {
                const weekIdx = Math.min(Math.floor(idx / 7), 3);
                weeks[weekIdx].push(d);
            });
            const wc = document.getElementById('weeklyCards');
            wc.innerHTML = weeks.map((days, wi) => {
                let ws = 0, wz = 0;
                days.forEach(d => {
                    ws += dayTotals[d]?.s || 0;
                    wz += dayTotals[d]?.z || 0;
                });
                const wTotal = ws + wz;
                const swPct = wTotal > 0 ? (ws / wTotal * 100) : 0;
                const zoPct = wTotal > 0 ? (wz / wTotal * 100) : 0;
                return `<div class="week-card">
                    <div class="week-card-title">Week ${wi + 1}</div>
                    <div class="week-card-amount" style="color:var(--swiggy);">₹${Math.round(ws).toLocaleString()}</div>
                    <div style="font-size:11px;color:var(--text-muted);">Swiggy</div>
                    <div class="week-card-amount" style="color:var(--zomato);">₹${Math.round(wz).toLocaleString()}</div>
                    <div style="font-size:11px;color:var(--text-muted);">Zomato</div>
                    <div class="week-card-bar">
                        <div class="week-card-bar-fill" style="width:${swPct}%;background:var(--swiggy);"></div>
                        <div class="week-card-bar-fill" style="width:${zoPct}%;background:var(--zomato);"></div>
                    </div>
                    <div style="font-size:10px;color:var(--text-muted);margin-top:4px;">₹${Math.round(wTotal).toLocaleString()} total</div>
                </div>`;
            }).join('');

            // Day-by-day table
            const dayNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
            const tbody = document.getElementById('monthOnlineTbody');
            let tbHtml = '';
            let totalDailySales = {};
            Object.values(data.data).forEach(dayData => {
                Object.entries(dayData.items).forEach(([name, amt]) => {
                    const item = data.items.find(it => it.item_name === name);
                    if (item && item.category === 'sales') {
                        totalDailySales[dayData] = (totalDailySales[dayData] || 0) + amt;
                    }
                });
            });
            data.days.forEach(d => {
                const dd = data.data[d];
                const dObj = new Date(d + 'T00:00:00');
                const s = dd.swiggy || 0;
                const z = dd.zomato || 0;
                const onlineTotal = s + z;
                // Calculate daily sales total
                let daySales = 0;
                Object.entries(dd.items).forEach(([name, amt]) => {
                    const item = data.items.find(it => it.item_name === name);
                    if (item && item.category === 'sales') daySales += amt;
                });
                const pct = daySales > 0 ? (onlineTotal / daySales * 100) : 0;
                let pctColor = 'var(--text-muted)';
                if (pct > 30) pctColor = 'var(--success)';
                else if (pct > 20) pctColor = 'var(--warning)';
                tbHtml += `<tr>
                    <td style="text-align:left;font-weight:600;position:sticky;left:0;background:white;z-index:2;">${d}</td>
                    <td>${dayNames[dObj.getDay()]}</td>
                    <td style="background:var(--swiggy-light);">${s > 0 ? '₹' + Math.round(s).toLocaleString() : '-'}</td>
                    <td style="background:var(--zomato-light);">${z > 0 ? '₹' + Math.round(z).toLocaleString() : '-'}</td>
                    <td style="font-weight:700;">₹${Math.round(onlineTotal).toLocaleString()}</td>
                    <td style="font-weight:600;color:${pctColor};">${pct.toFixed(1)}%</td>
                </tr>`;
            });
            tbody.innerHTML = tbHtml;

            // Trend line chart
            setTimeout(() => {
                const ctx = document.getElementById('trendChart');
                if (!ctx) return;
                new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: data.days.map(d => parseInt(d.substring(8))),
                        datasets: [
                            { label: 'Swiggy', data: data.days.map(d => dayTotals[d]?.s || 0), borderColor: '#FF5200', backgroundColor: 'rgba(255,82,0,0.15)', fill: true, tension: 0.4, pointRadius: 0, pointHoverRadius: 5 },
                            { label: 'Zomato', data: data.days.map(d => dayTotals[d]?.z || 0), borderColor: '#E23744', backgroundColor: 'rgba(226,55,68,0.15)', fill: true, tension: 0.4, pointRadius: 0, pointHoverRadius: 5 }
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: true, labels: { usePointStyle: true, padding: 12, font: { size: 11 } } } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#F3F4F6' }, ticks: { font: { size: 10 }, callback: v => '₹' + (v/1000).toFixed(0)+'K' } },
                            x: { grid: { display: false }, ticks: { font: { size: 9 } } }
                        }
                    }
                });
            }, 100);

            // Heatmap
            const heatmap = document.getElementById('heatmapContainer');
            const dayNamesShort = ['S','M','T','W','T','F','S'];
            const firstDay = new Date(data.days[0] + 'T00:00:00').getDay();
            let heatHtml = '<div class="heatmap-grid">';
            // Header
            dayNamesShort.forEach(d => { heatHtml += `<div style="text-align:center;font-size:9px;color:var(--text-muted);padding:2px;">${d}</div>`; });
            // Empty cells before first day
            for (let i = 0; i < firstDay; i++) { heatHtml += '<div></div>'; }
            const maxVal = Math.max(...data.days.map(d => (dayTotals[d]?.total || 0)), 1);
            data.days.forEach(d => {
                const val = dayTotals[d]?.total || 0;
                const intensity = Math.min(val / maxVal, 1);
                const r = Math.round(13 + (30 - 13) * intensity);
                const g = Math.round(40 + (123 - 40) * intensity);
                const b = Math.round(24 + (75 - 24) * intensity);
                const dayNum = parseInt(d.substring(8));
                const sVal = dayTotals[d]?.s || 0;
                const zVal = dayTotals[d]?.z || 0;
                heatHtml += `<div class="heatmap-cell" style="background:rgba(${r},${g},${b},${0.15 + intensity * 0.85});">
                    <span style="color:${val > 0 ? 'white' : 'var(--text-muted)'};">${dayNum}</span>
                    <div class="heatmap-tooltip">${d} — Swiggy ₹${Math.round(sVal).toLocaleString()} + Zomato ₹${Math.round(zVal).toLocaleString()} = ₹${Math.round(val).toLocaleString()}</div>
                </div>`;
            });
            heatHtml += '</div>';
            heatmap.innerHTML = heatHtml;
        }
    </script>
</body>
</html>