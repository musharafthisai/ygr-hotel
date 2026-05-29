<?php
// pages/weekly_report.php
require_once '../auth/session.php';
require_once '../config/constants.php';
check_auth();
check_role(['owner', 'branch_admin']);

$from = isset($_GET['from']) ? $_GET['from'] : date('Y-m-d', strtotime('monday this week'));
$to = isset($_GET['to']) ? $_GET['to'] : date('Y-m-d', strtotime('sunday this week'));
define('BASE_URL', (strpos($_SERVER['SCRIPT_NAME'] ?? '', '/yarahman') !== false) ? '/yarahman/' : '/');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Weekly Report - YGR signature</title>
    
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
        .filter-card { display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; }
        @media print { .no-print { display: none !important; } body { background: white; } }
        @media (max-width: 1023px) { .report-page { padding: 16px; padding-bottom: 120px; } }
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
                <a href="weekly_report.php" class="nav-link active"><i class="ti ti-file-analytics"></i> Weekly</a>
                <a href="monthly_report.php" class="nav-link"><i class="ti ti-calendar-stats"></i> Monthly</a>
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
        <div class="mb-xl no-print">
            <h1 class="text-2xl font-bold" style="font-family:'Syne',sans-serif;">Weekly Report</h1>
            <p class="text-muted text-sm">Comprehensive week performance analysis</p>
        </div>

        <!-- Filters -->
        <div class="card mb-lg no-print">
            <form id="filterForm" method="GET" class="filter-card">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">From</label>
                    <input type="date" name="from" id="from_date" value="<?= $from ?>" class="form-input" style="width: 170px;" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">To</label>
                    <input type="date" name="to" id="to_date" value="<?= $to ?>" class="form-input" style="width: 170px;" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Item</label>
                    <select name="item" id="item_filter" class="form-input" style="width: 200px;">
                        <option value="all">All Items</option>
                    </select>
                </div>
                <div style="display: flex; gap: 8px; padding-bottom: 2px;">
                    <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Filter</button>
                    <button type="button" class="btn btn-outline" onclick="window.print()"><i class="ti ti-printer"></i> Print</button>
                    <button type="button" class="btn btn-success" onclick="exportCSV()"><i class="ti ti-download"></i> CSV</button>
                </div>
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
            <div class="table-container" style="max-height: 70vh; border: none;">
                <table class="report-table" id="reportTable">
                    <thead>
                        <tr id="thead-row">
                            <th style="min-width: 180px; text-align: left; position: sticky; left: 0; z-index: 6;">Item</th>
                            <th style="min-width: 100px;">Week Total (₹)</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-items"></tbody>
                </table>
            </div>
        </div>

        <!-- ===== ONLINE SALES SECTION (Weekly) ===== -->
        <div id="onlineSection" class="mt-xl" style="display: none;">
            <h2 class="text-xl font-bold mb-lg" style="font-family:'Syne',sans-serif;">
                <i class="ti ti-motorbike" style="color:var(--swiggy);"></i> Online Deliveries
            </h2>

            <!-- Platform Summary Cards -->
            <div class="online-weekly-cards mb-lg">
                <div class="online-summary-card swiggy">
                    <div class="online-summary-icon"><i class="ti ti-motorbike"></i></div>
                    <div class="online-summary-label">Swiggy</div>
                    <div class="online-summary-value" id="swiggy-week-total">₹0</div>
                    <div class="online-summary-orders" id="swiggy-week-orders">0 orders</div>
                    <div class="online-summary-trend up" id="swiggy-week-trend">▲ +0% vs last week</div>
                </div>
                <div class="online-summary-card zomato">
                    <div class="online-summary-icon"><i class="ti ti-motorbike"></i></div>
                    <div class="online-summary-label">Zomato</div>
                    <div class="online-summary-value" id="zomato-week-total">₹0</div>
                    <div class="online-summary-orders" id="zomato-week-orders">0 orders</div>
                    <div class="online-summary-trend down" id="zomato-week-trend">▼ +0% vs last week</div>
                </div>
            </div>

            <!-- Day-by-Day Online Table -->
            <div class="card mb-lg" style="padding: 0; overflow: hidden;">
                <div class="card-header" style="padding: 16px 20px; margin-bottom: 0;">
                    <span class="card-title"><i class="ti ti-table"></i> Day-by-Day Online Sales</span>
                </div>
                <div class="table-container" style="border: none;">
                    <table class="report-table" id="onlineDayTable">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th style="background:#FF5200;">Swiggy Orders</th>
                                <th style="background:#FF5200;">Swiggy Amount</th>
                                <th style="background:#E23744;">Zomato Orders</th>
                                <th style="background:#E23744;">Zomato Amount</th>
                                <th>Combined</th>
                            </tr>
                        </thead>
                        <tbody id="onlineDayTbody"></tbody>
                        <tfoot id="onlineDayTfoot"></tfoot>
                    </table>
                </div>
            </div>

            <!-- Mini Bar Chart -->
            <div class="chart-container" id="onlineBarChartContainer">
                <div class="chart-title">Daily Online Sales — This Week</div>
                <canvas id="onlineBarChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- ===== FLOATING PILL NAVBAR (Mobile) ===== -->
    <nav id="floatingNav" class="floating-nav">
        <button class="nav-item" data-index="0" onclick="setNav(0)"><i class="ti ti-pencil-plus"></i><span class="nav-label">Entry</span></button>
        <button class="nav-item active" data-index="1" onclick="setNav(1)"><i class="ti ti-calendar-week"></i><span class="nav-label">Weekly</span></button>
        <button class="nav-item" data-index="2" onclick="setNav(2)"><i class="ti ti-calendar-month"></i><span class="nav-label">Monthly</span></button>
        <button class="nav-item" data-index="3" onclick="setNav(3)"><i class="ti ti-truck-delivery"></i><span class="nav-label">Online</span></button>
        <button class="nav-item" data-index="4" onclick="setNav(4)"><i class="ti ti-settings"></i><span class="nav-label">Settings</span></button>
    </nav>

    <script src="<?php echo BASE_URL; ?>assets/js/app.js"></script>
    <script>
        let reportData = null;

        document.addEventListener('DOMContentLoaded', () => {
            loadReport();
            document.getElementById('filterForm').addEventListener('submit', function(e) {
                e.preventDefault();
                loadReport();
            });
            document.getElementById('item_filter').addEventListener('change', function() {
                if (reportData) renderTable(reportData, this.value);
            });
        });

        async function loadReport() {
            showLoader();
            const from = document.getElementById('from_date').value;
            const to = document.getElementById('to_date').value;
            const item = document.getElementById('item_filter').value;
            if (!from || !to) { showToast('Please select both dates', 'error'); hideLoader(); return; }
            if (from > to) { showToast('From date cannot be after To date', 'error'); hideLoader(); return; }
            try {
                const res = await fetch(`../api/get_report.php?type=weekly&from=${from}&to=${to}&item=${encodeURIComponent(item)}`);
                const data = await res.json();
                if (data.success) {
                    reportData = data;
                    populateItemFilter(data);
                    renderTable(data, item);
                    renderTopItems(data);
                    renderOnlineSection(data);
                } else {
                    showToast(data.message, 'error');
                }
            } catch (e) {
                showToast('Error loading report', 'error');
            } finally {
                hideLoader();
            }
        }

        function populateItemFilter(data) {
            const select = document.getElementById('item_filter');
            const currentValue = select.value;
            select.innerHTML = '<option value="all">All Items</option>';
            Object.keys(data.items).sort().forEach(name => {
                const opt = document.createElement('option');
                opt.value = name; opt.textContent = name; select.appendChild(opt);
            });
            if (Array.from(select.options).map(o => o.value).includes(currentValue)) {
                select.value = currentValue;
            }
        }

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

        function renderTopItems(data) {
            try {
                const grid = document.getElementById('topItemsGrid');
                const container = document.getElementById('gridContainer');
                if (!grid || !container) return;
                const items = Object.entries(data.items || {})
                    .filter(([n, i]) => i && i.category === 'sales')
                    .map(([n, i]) => ({ name: n, total: Object.values(i.days || {}).reduce((a, b) => a + b, 0) }))
                    .sort((a, b) => b.total - a.total).slice(0, 8);
                if (items.length === 0) { grid.style.display = 'none'; return; }
                grid.style.display = 'block';
                container.innerHTML = items.map(it => `
                    <div class="top-item-card">
                        <img src="${getFoodImg(it.name)}" alt="" class="top-item-img" loading="lazy" onerror="this.style.background='linear-gradient(135deg,#0D2818,#1E7B4B)'">
                        <div class="top-item-info">
                            <div class="top-item-name">${it.name}</div>
                            <div class="top-item-amount">₹${it.total.toFixed(2)}</div>
                        </div>
                    </div>
                `).join('');
            } catch(e) {}
        }

        function renderMobileSummary(data) {
            // Deprecated - removed in favor of full responsive table
        }

        function renderTable(data, filterItem = 'all') {
            const thead = document.getElementById('thead-row');
            const tbody = document.getElementById('tbody-items');
            let thHtml = '<th style="min-width: 180px; text-align: left; position: sticky; left: 0; z-index: 6;">Item</th>';
            const dayNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
            data.dates.forEach(d => {
                const dateObj = new Date(d + 'T00:00:00');
                thHtml += `<th>${dayNames[dateObj.getDay()]}<br><span style="font-size:9px;opacity:0.8">${d.substring(5)}</span></th>`;
            });
            thHtml += '<th style="min-width: 100px;">Week Total</th>';
            thead.innerHTML = thHtml;

            let html = '';
            let totalSales = 0, totalExp = 0;
            const salesItems = [], expItems = [];
            for (const [name, info] of Object.entries(data.items)) {
                if (filterItem !== 'all' && name !== filterItem) continue;
                if (info.category === 'sales') salesItems.push({name, days: info.days});
                else expItems.push({name, days: info.days});
            }

            const renderSection = (items, isSales) => {
                if (items.length === 0) return;
                if (filterItem === 'all') {
                    html += `<tr class="section-header ${isSales ? '' : 'expense'}"><td colspan="${data.dates.length + 2}">${isSales ? 'SALES ITEMS' : 'EXPENSE ITEMS'}</td></tr>`;
                }
                let colTotal = Array(data.dates.length).fill(0);
                let sectionTotal = 0;
                items.forEach(item => {
                    let rowHtml = `<tr class="${isSales ? 'sales-row' : 'exp-row'}"><td><div class="report-td-name"><img src="${getFoodImg(item.name)}" alt="" class="report-item-img" loading="lazy" onerror="this.style.display='none'"><div class="item-name-wrap"><div>${item.name}</div><div class="item-sub">${isSales ? 'Sales' : 'Expense'}</div></div></div></td>`;
                    let rowTotal = 0;
                    data.dates.forEach((d, idx) => {
                        const amt = item.days[d] || 0;
                        rowTotal += amt; colTotal[idx] += amt;
                        rowHtml += `<td>${amt > 0 ? amt.toFixed(2) : '-'}</td>`;
                    });
                    sectionTotal += rowTotal;
                    if (isSales) totalSales += rowTotal; else totalExp += rowTotal;
                    rowHtml += `<td><strong>${rowTotal.toFixed(2)}</strong></td></tr>`;
                    html += rowHtml;
                });
                if (filterItem === 'all') {
                    html += `<tr class="total-row"><td>TOTAL ${isSales ? 'SALES' : 'EXPENSE'}</td>`;
                    data.dates.forEach((d, idx) => { html += `<td>${colTotal[idx].toFixed(2)}</td>`; });
                    html += `<td><strong>${sectionTotal.toFixed(2)}</strong></td></tr>`;
                }
            };

            renderSection(salesItems, true);
            renderSection(expItems, false);

            if (filterItem === 'all') {
                html += `<tr><td colspan="${data.dates.length + 2}" style="padding: 4px;"></td></tr>`;
                const renderPaymentRow = (label, key, color) => {
                    html += `<tr><td style="color:#2D5016;">${label}</td>`;
                    let tot = 0;
                    data.dates.forEach(d => { const amt = data[key][d]||0; tot+=amt; html+=`<td>${amt.toFixed(2)}</td>`; });
                    html += `<td><strong>${tot.toFixed(2)}</strong></td></tr>`;
                    return tot;
                };
                const totCash = renderPaymentRow('CASH (Sales)', 'daily_cash');
                const totGpay = renderPaymentRow('GPAY (Sales)', 'daily_gpay');
                const renderOnlineRow = (label, key) => {
                    html += `<tr><td style="color:#D4A843;">${label}</td>`;
                    let tot = 0;
                    data.dates.forEach(d => { const amt = data[key]?.[d]||0; tot+=amt; html+=`<td>${amt.toFixed(2)}</td>`; });
                    html += `<td><strong>${tot.toFixed(2)}</strong></td></tr>`;
                    return tot;
                };
                const totSwiggy = renderOnlineRow('SWIGGY (Online)', 'daily_swiggy');
                const totZomato = renderOnlineRow('ZOMATO (Online)', 'daily_zomato');
                const net = (totCash + totGpay + totSwiggy + totZomato) - totalExp;
                html += `<tr class="profit-row"><td>NET PROFIT</td><td colspan="${data.dates.length}"></td><td><strong>₹${net.toFixed(2)}</strong></td></tr>`;
            }
            tbody.innerHTML = html;
        }

        // ===== ONLINE SALES SECTION =====
        function renderOnlineSection(data) {
            const section = document.getElementById('onlineSection');
            let hasOnline = false;
            data.dates.forEach(d => {
                if ((data.daily_swiggy?.[d] || 0) > 0 || (data.daily_zomato?.[d] || 0) > 0) hasOnline = true;
            });
            if (!hasOnline) { section.style.display = 'none'; return; }
            section.style.display = 'block';

            // Summary
            let totSwiggy = 0, totZomato = 0, swiggyOrders = 0, zomatoOrders = 0;
            let swiggyDays = 0, zomatoDays = 0;
            data.dates.forEach(d => {
                const s = data.daily_swiggy?.[d] || 0;
                const z = data.daily_zomato?.[d] || 0;
                totSwiggy += s; totZomato += z;
                if (s > 0) { swiggyOrders++; swiggyDays++; }
                if (z > 0) { zomatoOrders++; zomatoDays++; }
            });
            document.getElementById('swiggy-week-total').innerText = '₹' + Math.round(totSwiggy).toLocaleString();
            document.getElementById('zomato-week-total').innerText = '₹' + Math.round(totZomato).toLocaleString();
            document.getElementById('swiggy-week-orders').innerText = swiggyOrders + ' orders';
            document.getElementById('zomato-week-orders').innerText = zomatoOrders + ' orders';

            // Day-by-day table
            const dayNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
            const tbody = document.getElementById('onlineDayTbody');
            const tfoot = document.getElementById('onlineDayTfoot');
            let html = '', totS = 0, totZ = 0, totC = 0;
            data.dates.forEach(d => {
                const s = data.daily_swiggy?.[d] || 0;
                const z = data.daily_zomato?.[d] || 0;
                const c = s + z;
                totS += s; totZ += z; totC += c;
                const dObj = new Date(d + 'T00:00:00');
                html += `<tr>
                    <td style="text-align:left;font-weight:600;">${dayNames[dObj.getDay()]} ${d.substring(5)}</td>
                    <td style="background:rgba(255,82,0,0.06);">${s > 0 ? '1' : '0'}</td>
                    <td style="background:var(--swiggy-light);">${s > 0 ? '₹' + Math.round(s).toLocaleString() : '-'}</td>
                    <td style="background:rgba(226,55,68,0.06);">${z > 0 ? '1' : '0'}</td>
                    <td style="background:var(--zomato-light);">${z > 0 ? '₹' + Math.round(z).toLocaleString() : '-'}</td>
                    <td style="font-weight:700;">₹${Math.round(c).toLocaleString()}</td>
                </tr>`;
            });
            tbody.innerHTML = html;
            tfoot.innerHTML = `<tr style="font-weight:700;background:var(--bg-alt);">
                <td style="text-align:left;">WEEK TOTAL</td>
                <td>${swiggyOrders}</td>
                <td>₹${Math.round(totS).toLocaleString()}</td>
                <td>${zomatoOrders}</td>
                <td>₹${Math.round(totZ).toLocaleString()}</td>
                <td>₹${Math.round(totC).toLocaleString()}</td>
            </tr>`;

            // Mini bar chart
            setTimeout(() => {
                const ctx = document.getElementById('onlineBarChart');
                if (!ctx) return;
                new Chart(ctx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: data.dates.map(d => d.substring(5)),
                        datasets: [
                            { label: 'Swiggy', data: data.dates.map(d => data.daily_swiggy?.[d] || 0), backgroundColor: '#FF5200', borderRadius: 4 },
                            { label: 'Zomato', data: data.dates.map(d => data.daily_zomato?.[d] || 0), backgroundColor: '#E23744', borderRadius: 4 }
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: true, labels: { usePointStyle: true, padding: 12, font: { size: 11 } } } },
                        scales: {
                            y: { beginAtZero: true, grid: { display: false }, ticks: { font: { size: 10 }, callback: v => '₹' + (v/1000).toFixed(0)+'K' } },
                            x: { grid: { display: false }, ticks: { font: { size: 9 } } }
                        }
                    }
                });
            }, 100);
        }

        function exportCSV() {
            if (!reportData) return;
            const table = document.getElementById('reportTable');
            let csv = '\uFEFF';
            const rows = table.querySelectorAll('tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('th, td');
                csv += Array.from(cells).map(c => '"' + c.innerText.replace(/"/g,'""') + '"').join(',') + '\n';
            });
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'weekly_report.csv';
            link.click();
        }
    </script>
</body>
</html>