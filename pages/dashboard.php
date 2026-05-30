<?php
// pages/dashboard.php
require_once '../auth/session.php';
require_once '../config/constants.php';
check_auth();
check_role(['owner', 'branch_admin']);
$branch_name = $_SESSION['branch_name'] ?? 'Main Branch';
define('BASE_URL', (strpos($_SERVER['SCRIPT_NAME'] ?? '', '/yarahman') !== false) ? '/yarahman/' : '/');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard - YGR signature</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#F7F7FF',
                        accent: '#FB3640',
                        swiggy: '#FF5200',
                        zomato: '#E23744',
                    },
                    fontFamily: {
                        display: ['Syne','sans-serif'],
                        body: ['DM Sans','sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Premium Design System -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/premium.css">
</head>
<body>
    <!-- Loader -->
    <div class="loader-overlay"></div>

    <!-- ===== TOP HEADER (Desktop) ===== -->
    <div class="top-header">
        <div class="header-left">
            <div class="header-brand">
                <div class="header-brand-icon"><img src="../assets/img/logo.png" alt="YGR" style="height:28px;width:auto;filter:brightness(0)"></div>
                YGR signature
            </div>
            <nav class="header-center">
                <a href="dashboard.php" class="nav-link active"><i class="ti ti-layout-dashboard"></i> Dashboard</a>
                <a href="daily_entry.php" class="nav-link"><i class="ti ti-pencil-plus"></i> Entry</a>
                <a href="weekly_report.php" class="nav-link"><i class="ti ti-file-analytics"></i> Weekly</a>
                <a href="monthly_report.php" class="nav-link"><i class="ti ti-calendar-stats"></i> Monthly</a>
                <a href="online_sales.php" class="nav-link"><i class="ti ti-truck-delivery"></i> Online</a>
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
                <i class="ti ti-logout"></i>
                <span>Logout</span>
            </a>
            <div class="avatar"><?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 2)); ?></div>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="content-area">
        <!-- Page Title -->
        <div class="mb-xl card-enter" style="--i: 0;">
            <h1 class="text-3xl font-bold mb-sm" style="font-family:'Syne',sans-serif;">Dashboard</h1>
            <p class="text-muted">Today's performance at a glance</p>
        </div>

        <!-- Row 1: Today's KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-lg">
            <div class="kpi-card skeleton-card card-enter" style="--i: 1;" id="kpi-sales">
                <div class="kpi-content" style="display: none;">
                    <div class="kpi-card-icon" style="background: rgba(39, 24, 126, 0.1); color: var(--success);">
                        <i class="ti ti-cash"></i>
                    </div>
                    <div class="kpi-card-label">Today Sales</div>
                    <div class="kpi-card-value count-up" id="dash-today-sales">₹0</div>
                </div>
            </div>
            <div class="kpi-card skeleton-card card-enter" style="--i: 2;" id="kpi-expenses">
                <div class="kpi-content" style="display: none;">
                    <div class="kpi-card-icon" style="background: rgba(220, 38, 38, 0.1); color: var(--danger);">
                        <i class="ti ti-receipt"></i>
                    </div>
                    <div class="kpi-card-label">Today Expenses</div>
                    <div class="kpi-card-value count-up" id="dash-today-exp">₹0</div>
                </div>
            </div>
            <div class="kpi-card skeleton-card card-enter" style="--i: 3;" id="kpi-cash">
                <div class="kpi-content" style="display: none;">
                    <div class="kpi-card-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--info);">
                        <i class="ti ti-wallet"></i>
                    </div>
                    <div class="kpi-card-label">Today Cash</div>
                    <div class="kpi-card-value count-up" id="dash-today-cash">₹0</div>
                </div>
            </div>
            <div class="kpi-card skeleton-card card-enter" style="--i: 4;" id="kpi-gpay">
                <div class="kpi-content" style="display: none;">
                    <div class="kpi-card-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                        <i class="ti ti-device-mobile"></i>
                    </div>
                    <div class="kpi-card-label">Today GPay</div>
                    <div class="kpi-card-value count-up" id="dash-today-gpay">₹0</div>
                </div>
            </div>
        </div>

        <!-- Row 2: Week Overview KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-lg">
            <div class="kpi-card skeleton-card card-enter" style="--i: 5;" id="kpi-week-sales">
                <div class="kpi-content" style="display: none;">
                    <div class="kpi-card-icon" style="background: rgba(39, 24, 126, 0.1); color: var(--success);">
                        <i class="ti ti-trending-up"></i>
                    </div>
                    <div class="kpi-card-label">Week Sales</div>
                    <div class="kpi-card-value count-up" id="dash-week-sales">₹0</div>
                </div>
            </div>
            <div class="kpi-card skeleton-card card-enter" style="--i: 6;" id="kpi-week-expenses">
                <div class="kpi-content" style="display: none;">
                    <div class="kpi-card-icon" style="background: rgba(220, 38, 38, 0.1); color: var(--danger);">
                        <i class="ti ti-receipt"></i>
                    </div>
                    <div class="kpi-card-label">Week Expenses</div>
                    <div class="kpi-card-value count-up" id="dash-week-exp">₹0</div>
                </div>
            </div>
            <div class="kpi-card skeleton-card card-enter" style="--i: 7;" id="kpi-week-swiggy">
                <div class="kpi-content" style="display: none;">
                    <div class="kpi-card-icon" style="background: rgba(255, 82, 0, 0.1); color: var(--swiggy);">
                        <i class="ti ti-brand-stripe"></i>
                    </div>
                    <div class="kpi-card-label">Week Swiggy</div>
                    <div class="kpi-card-value count-up" id="dash-week-swiggy">₹0</div>
                </div>
            </div>
            <div class="kpi-card skeleton-card card-enter" style="--i: 8;" id="kpi-week-zomato">
                <div class="kpi-content" style="display: none;">
                    <div class="kpi-card-icon" style="background: rgba(226, 55, 68, 0.1); color: var(--zomato);">
                        <i class="ti ti-brand-instagram"></i>
                    </div>
                    <div class="kpi-card-label">Week Zomato</div>
                    <div class="kpi-card-value count-up" id="dash-week-zomato">₹0</div>
                </div>
            </div>
        </div>

        <!-- Row 3: Premium Net Profit Card -->
        <div class="premium-card card-enter mb-lg" style="--i: 9;">
            <div class="premium-card-content">
                <div class="premium-card-label">This Month Net Profit</div>
                <div class="premium-card-value count-up" id="dash-month-net">₹0</div>
            </div>
        </div>

        <!-- Row 4: Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-lg">
            <div class="chart-container skeleton-chart card-enter" style="--i: 10;" id="barChartContainer">
                <div class="chart-title">Last 7 Days (Sales vs Expenses)</div>
                <canvas id="barChart"></canvas>
            </div>
            <div class="chart-container skeleton-chart card-enter" style="--i: 11;" id="doughnutChartContainer">
                <div class="chart-title">Payment Split</div>
                <canvas id="doughnutChart"></canvas>
            </div>
        </div>

        <!-- Row 5: Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 card-enter" style="--i: 12;">
            <div class="quick-action-card" onclick="window.location.href='daily_entry.php'">
                <div class="quick-action-icon" style="background: rgba(39, 24, 126, 0.1); color: var(--success);">
                    <i class="ti ti-pencil-plus"></i>
                </div>
                <div class="quick-action-label">Add Today's Entry</div>
            </div>
            <div class="quick-action-card" onclick="window.location.href='online_sales.php'">
                <div class="quick-action-icon" style="background: rgba(255, 82, 0, 0.1); color: var(--swiggy);">
                    <i class="ti ti-shopping-cart"></i>
                </div>
                <div class="quick-action-label">Online Sales</div>
            </div>
            <div class="quick-action-card" onclick="window.location.href='weekly_report.php'">
                <div class="quick-action-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--info);">
                    <i class="ti ti-file-analytics"></i>
                </div>
                <div class="quick-action-label">View Reports</div>
            </div>
        </div>
    </div>

    <!-- ===== FLOATING PILL NAVBAR (Mobile) ===== -->
    <nav id="floatingNav" class="floating-nav">
        <button class="nav-item" data-index="0" onclick="setNav(0)">
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
        // Simulate data loading with skeleton
        function loadDashboard() {
            setTimeout(() => {
                document.querySelectorAll('.kpi-card').forEach(card => {
                    card.classList.remove('skeleton-card');
                    const content = card.querySelector('.kpi-content');
                    if (content) content.style.display = '';
                });
                document.querySelectorAll('.chart-container').forEach(container => {
                    container.classList.remove('skeleton-chart');
                });
                initBarChart();
                initDoughnutChart();
                triggerCountUp();
                attachMouseTracking();
            }, 1200);
        }

        // Count Up Animation
        function countUp(element, target, duration = 1200) {
            const start = performance.now();
            const update = (now) => {
                const progress = Math.min((now - start) / duration, 1);
                const ease = 1 - Math.pow(1 - progress, 3);
                const value = Math.floor(ease * target);
                element.textContent = '₹' + value.toLocaleString('en-IN');
                if (progress < 1) requestAnimationFrame(update);
            };
            requestAnimationFrame(update);
        }

        function triggerCountUp() {
            document.querySelectorAll('.count-up').forEach(el => {
                const target = parseInt(el.innerText.replace(/[₹,]/g, '')) || 0;
                countUp(el, target);
            });
        }

        // Mouse Tracking on Cards
        function attachMouseTracking() {
            document.querySelectorAll('.kpi-card, .quick-action-card, .item-card, .entry-card, .top-item-card, .staff-card').forEach(card => {
                card.addEventListener('mousemove', (e) => {
                    const rect = card.getBoundingClientRect();
                    const x = ((e.clientX - rect.left) / rect.width) * 100;
                    const y = ((e.clientY - rect.top) / rect.height) * 100;
                    card.style.setProperty('--mx', x + '%');
                    card.style.setProperty('--my', y + '%');
                });
            });
        }

        // Bar Chart
        function initBarChart() {
            const ctx = document.getElementById('barChart');
            if (!ctx) return;
            new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [
                        {
                            label: 'Sales',
                            data: [18500, 16200, 19800, 21500, 17800, 24500, 18500],
                            backgroundColor: '#27187E',
                            borderRadius: 6,
                            borderSkipped: false,
                        },
                        {
                            label: 'Expenses',
                            data: [3200, 2800, 3500, 3100, 2900, 3800, 3200],
                            backgroundColor: '#DC2626',
                            borderRadius: 6,
                            borderSkipped: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                font: { size: 11, family: "'DM Sans', sans-serif" },
                                padding: 12,
                                color: '#6B7280',
                                usePointStyle: true,
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) { return '₹' + (value / 1000).toFixed(0) + 'K'; },
                                font: { size: 11 },
                                color: '#9CA3AF'
                            },
                            grid: { color: '#F3F4F6', drawBorder: false }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 }, color: '#6B7280' }
                        }
                    }
                }
            });
        }

        // Doughnut Chart
        function initDoughnutChart() {
            const ctx = document.getElementById('doughnutChart');
            if (!ctx) return;
            new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Cash', 'GPay', 'Online'],
                    datasets: [{
                        data: [45, 35, 20],
                        backgroundColor: ['#27187E', '#3B82F6', '#F59E0B'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                font: { size: 11, family: "'DM Sans', sans-serif" },
                                padding: 12,
                                color: '#6B7280',
                                usePointStyle: true,
                            }
                        }
                    }
                }
            });
        }

        // Load real data from API
        document.addEventListener('DOMContentLoaded', async () => {
            showLoader();
            try {
                const res = await fetch('../api/get_dashboard.php');
                const json = await res.json();
                if (json.success) {
                    populateDashboard(json.data);
                }
            } catch (e) {
                loadDashboard();
            } finally {
                hideLoader();
            }
        });

        function populateDashboard(data) {
            document.getElementById('dash-today-sales').innerText = '₹' + data.todaySales.toLocaleString();
            document.getElementById('dash-today-exp').innerText = '₹' + data.todayExp.toLocaleString();
            document.getElementById('dash-today-cash').innerText = '₹' + data.todayCash.toLocaleString();
            document.getElementById('dash-today-gpay').innerText = '₹' + data.todayGPay.toLocaleString();
            document.getElementById('dash-week-sales').innerText = '₹' + data.weekSales.toLocaleString();
            document.getElementById('dash-week-exp').innerText = '₹' + data.weekExp.toLocaleString();
            document.getElementById('dash-week-swiggy').innerText = '₹' + data.weekSwiggy.toLocaleString();
            document.getElementById('dash-week-zomato').innerText = '₹' + data.weekZomato.toLocaleString();
            
            const netEl = document.getElementById('dash-month-net');
            netEl.innerText = '₹' + data.monthNetProfit.toLocaleString();

            // Show content
            document.querySelectorAll('.kpi-card').forEach(card => {
                card.classList.remove('skeleton-card');
                const content = card.querySelector('.kpi-content');
                if (content) content.style.display = '';
            });
            document.querySelectorAll('.chart-container').forEach(container => {
                container.classList.remove('skeleton-chart');
            });

            triggerCountUp();
            attachMouseTracking();

            Chart.defaults.font.family = "'DM Sans', sans-serif";
            Chart.defaults.color = '#6B7280';

            const ctxBar = document.getElementById('barChart');
            if (ctxBar && data.last7Days) {
                new Chart(ctxBar.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: data.last7Days.labels,
                        datasets: [
                            {
                                label: 'Sales',
                                data: data.last7Days.sales,
                                backgroundColor: '#27187E',
                                borderRadius: 6,
                                borderSkipped: false,
                            },
                            {
                                label: 'Expenses',
                                data: data.last7Days.expenses,
                                backgroundColor: '#DC2626',
                                borderRadius: 6,
                                borderSkipped: false,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: true, labels: { usePointStyle: true, padding: 12, font: { size: 11 } } }
                        },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#F3F4F6', drawBorder: false } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            const ctxDoughnut = document.getElementById('doughnutChart');
            if (ctxDoughnut && data.pieData) {
                new Chart(ctxDoughnut.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Cash', 'GPay', 'Online'],
                        datasets: [{
                            data: data.pieData,
                            backgroundColor: ['#27187E', '#3B82F6', '#F59E0B'],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, font: { size: 11 } } }
                        }
                    }
                });
            }
        }
    </script>
</body>
</html>