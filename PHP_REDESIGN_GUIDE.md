# YARAHAM SHOP MANAGER - PHP REDESIGN IMPLEMENTATION GUIDE

## Overview
All PHP pages will use the shared premium design system from `assets/css/premium-design.css`. Include this at the top of every PHP page:

```php
<!-- Premium Design System -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/premium-design.css">
<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<!-- Tabler Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<!-- Chart.js (for dashboard) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
```

---

## 1. LOGIN PAGE (`pages/index.php` or `auth/login.php`)

### Key Features
- Animated gradient mesh background (CSS-only, no images needed)
- Split layout: 45% gradient left, 55% form right (desktop)
- Full-screen form on mobile
- Brand logo (SVG-based, no images)
- Form validation with error shake animation
- Loading state on submit
- Password visibility toggle

### Structure
```php
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Yaraham Manager</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Premium Design -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/premium-design.css">
    
    <style>
        /* Gradient Mesh Background */
        .gradient-mesh {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #0D2818 0%, #1A4A2E 100%);
        }

        .gradient-mesh::before, .gradient-mesh::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
        }

        .gradient-mesh::before {
            width: 400px;
            height: 400px;
            background: rgba(251, 54, 64, 0.4);
            top: -100px;
            left: -100px;
            animation: float 15s ease-in-out infinite;
        }

        .gradient-mesh::after {
            width: 300px;
            height: 300px;
            background: rgba(30, 123, 75, 0.3);
            bottom: -50px;
            right: -50px;
            animation: float 18s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(20px, -20px); }
        }

        .toggle-switch input[type="checkbox"] {
            width: 44px;
            height: 24px;
            appearance: none;
            background: var(--border);
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .toggle-switch input[type="checkbox"]::before {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: white;
            top: 2px;
            left: 2px;
            transition: all 0.2s;
        }

        .toggle-switch input[type="checkbox"]:checked {
            background: var(--success);
        }

        .toggle-switch input[type="checkbox"]:checked::before {
            left: 22px;
        }
    </style>
</head>
<body class="antialiased">
    <div class="flex h-screen">
        <!-- LEFT: Gradient Mesh (Hidden on Mobile) -->
        <div class="gradient-mesh hidden lg:flex lg:w-[45%] flex-col items-center justify-center p-8 relative">
            <div class="text-center z-10 relative">
                <!-- Brand Logo SVG -->
                <svg class="w-16 h-16 mx-auto mb-8" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M32 8C20.95 8 12 16.95 12 28C12 35.33 16.45 41.5 22.5 44.5V56H41.5V44.5C47.55 41.5 52 35.33 52 28C52 16.95 43.05 8 32 8Z" fill="#FB3640"/>
                    <circle cx="24" cy="26" r="3" fill="#F8FAF9"/>
                    <circle cx="40" cy="26" r="3" fill="#F8FAF9"/>
                    <path d="M28 36C30 38 34 38 36 36" stroke="#F8FAF9" stroke-width="2" stroke-linecap="round"/>
                </svg>

                <h1 class="text-4xl font-bold text-white mb-2">Yaraham</h1>
                <p class="text-lg text-white/90 italic" style="font-size: 15px;">Your restaurant, managed brilliantly.</p>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-6 mt-12 text-white">
                    <div>
                        <div class="text-2xl font-bold">₹18.5K</div>
                        <div class="text-xs opacity-75 mt-1">Today Sales</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">₹3.2K</div>
                        <div class="text-xs opacity-75 mt-1">Expenses</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">₹15.3K</div>
                        <div class="text-xs opacity-75 mt-1">Net Profit</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: Login Form -->
        <div class="w-full lg:w-[55%] flex items-center justify-center p-6" style="background: linear-gradient(135deg, #0D2818 0%, #1A4A2E 100%);">
            <div style="max-width: 400px; width: 100%; background: white; border-radius: 20px; padding: 40px; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
                <!-- Heading -->
                <h2 class="text-3xl font-bold" style="color: var(--primary); margin-bottom: 8px;">Welcome back</h2>
                <p class="text-sm text-gray-500 mb-6">Please enter your manager credentials</p>

                <!-- Form -->
                <form id="loginForm" method="POST" action="<?php echo BASE_URL; ?>auth/login.php">
                    <!-- Username -->
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <div style="position: relative;">
                            <i class="ti ti-user" style="position: absolute; left: 12px; top: 12px; color: var(--text-secondary); pointer-events: none;"></i>
                            <input type="text" name="username" placeholder="manager_id" required 
                                   style="padding-left: 40px;">
                        </div>
                        <div class="error-message" id="usernameError"></div>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div style="position: relative;">
                            <i class="ti ti-lock" style="position: absolute; left: 12px; top: 12px; color: var(--text-secondary); pointer-events: none;"></i>
                            <input type="password" id="password" name="password" placeholder="••••••••" required
                                   style="padding-left: 40px;">
                            <i class="ti ti-eye" id="togglePassword" onclick="togglePassword()" 
                               style="position: absolute; right: 12px; top: 12px; cursor: pointer; color: var(--text-secondary);"></i>
                        </div>
                        <div class="error-message" id="passwordError"></div>
                    </div>

                    <!-- Remember Me -->
                    <label class="toggle-switch mb-6" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="remember">
                        <span style="font-size: 14px; font-weight: 500; color: var(--text); user-select: none;">Remember me</span>
                    </label>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-full mb-4" id="submitBtn">
                        <span class="btn-text">Sign in</span>
                        <i class="ti ti-arrow-right"></i>
                    </button>

                    <!-- Links -->
                    <div style="display: flex; justify-content: space-between; padding-top: 16px; border-top: 1px solid var(--border-light);">
                        <a href="<?php echo BASE_URL; ?>forgot_password.php" style="font-size: 14px; font-weight: 600; color: var(--primary); text-decoration: none;">Forgot password?</a>
                    </div>

                    <!-- Footer -->
                    <p style="font-size: 12px; color: var(--text-muted); text-align: center; margin-top: 16px;">
                        Access restricted to registered managers only
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('togglePassword');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                input.type = 'password';
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }
        }

        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const username = document.querySelector('input[name="username"]');
            const password = document.querySelector('input[name="password"]');
            const submitBtn = document.getElementById('submitBtn');
            
            // Validation
            let hasError = false;
            if (!username.value.trim()) {
                username.parentElement.parentElement.classList.add('error-shake');
                username.parentElement.parentElement.classList.add('form-error');
                document.getElementById('usernameError').textContent = 'Username required';
                hasError = true;
            }
            if (!password.value) {
                password.parentElement.parentElement.classList.add('error-shake');
                password.parentElement.parentElement.classList.add('form-error');
                document.getElementById('passwordError').textContent = 'Password required';
                hasError = true;
            }

            if (hasError) return;

            // Loading state
            submitBtn.disabled = true;
            submitBtn.querySelector('.btn-text').innerHTML = '<span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span> Signing in...';

            // Submit form after delay for UX
            setTimeout(() => {
                document.getElementById('loginForm').submit();
            }, 800);
        });
    </script>
</body>
</html>
```

---

## 2. DASHBOARD (`pages/dashboard.php`)

### Key Features
- Bento grid layout: 4-column KPI cards (desktop), 2-column (tablet), 1-column (mobile)
- Mouse-tracking glow effect on KPI cards
- Count-up animation on numbers
- Premium dark card for "This Month Net Profit"
- Bar chart (Sales vs Expenses) + Doughnut chart (Payment Split)
- Skeleton loaders during async data loading
- Quick action cards (Add Entry, Online Sales, Reports)

### Structure
```php
<?php
session_start();
// Check authentication
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

// Fetch dashboard data (from your API/database)
$todaySales = 18500;
$todayExpenses = 3200;
$todayCash = 10500;
$todayGpay = 8000;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Yaraham Manager</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/premium-design.css">
</head>
<body>
    <!-- Top Header -->
    <div class="top-header">
        <div class="header-brand">
            <i class="ti ti-bowl-rice"></i>
            Yaraham
        </div>
        <div class="header-actions">
            <button class="btn btn-outline" style="height: 36px; padding: 0 12px;">
                <i class="ti ti-git-branch"></i>
                <span class="hidden sm:inline">Main Branch</span>
            </button>
            <i class="ti ti-bell text-lg" style="cursor: pointer; color: var(--primary);"></i>
            <div class="avatar">JM</div>
        </div>
    </div>

    <!-- Main Content -->
    <div style="padding: 24px; padding-bottom: 100px; max-width: 1280px; margin: 0 auto;">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2">Dashboard</h1>
            <p class="text-gray-500">Today's performance at a glance</p>
        </div>

        <!-- KPI Grid Row 1 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Today Sales (Skeleton initially) -->
            <div class="kpi-card skeleton-card card-enter" style="--i: 0;" id="kpi-sales">
                <div style="display: none;" class="kpi-content">
                    <div class="kpi-card-icon" style="background: rgba(30, 123, 75, 0.1); color: var(--success);">
                        <i class="ti ti-cash"></i>
                    </div>
                    <div class="kpi-card-label">Today Sales</div>
                    <div class="kpi-card-value count-up" data-value="<?php echo $todaySales; ?>">₹0</div>
                    <span class="kpi-card-trend positive">
                        <i class="ti ti-trending-up"></i> +12%
                    </span>
                </div>
            </div>

            <!-- Today Expenses -->
            <div class="kpi-card skeleton-card card-enter" style="--i: 1;" id="kpi-expenses">
                <div style="display: none;" class="kpi-content">
                    <div class="kpi-card-icon" style="background: rgba(220, 38, 38, 0.1); color: var(--danger);">
                        <i class="ti ti-receipt"></i>
                    </div>
                    <div class="kpi-card-label">Today Expenses</div>
                    <div class="kpi-card-value count-up" data-value="<?php echo $todayExpenses; ?>">₹0</div>
                    <span class="kpi-card-trend negative">
                        <i class="ti ti-trending-down"></i> -5%
                    </span>
                </div>
            </div>

            <!-- Today Cash -->
            <div class="kpi-card skeleton-card card-enter" style="--i: 2;" id="kpi-cash">
                <div style="display: none;" class="kpi-content">
                    <div class="kpi-card-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--info);">
                        <i class="ti ti-wallet"></i>
                    </div>
                    <div class="kpi-card-label">Today Cash</div>
                    <div class="kpi-card-value count-up" data-value="<?php echo $todayCash; ?>">₹0</div>
                    <span class="kpi-card-trend positive">
                        <i class="ti ti-trending-up"></i> +8%
                    </span>
                </div>
            </div>

            <!-- Today GPay -->
            <div class="kpi-card skeleton-card card-enter" style="--i: 3;" id="kpi-gpay">
                <div style="display: none;" class="kpi-content">
                    <div class="kpi-card-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                        <i class="ti ti-device-mobile"></i>
                    </div>
                    <div class="kpi-card-label">Today GPay</div>
                    <div class="kpi-card-value count-up" data-value="<?php echo $todayGpay; ?>">₹0</div>
                    <span class="kpi-card-trend positive">
                        <i class="ti ti-trending-up"></i> +15%
                    </span>
                </div>
            </div>
        </div>

        <!-- Premium Net Profit Card -->
        <div class="card-premium mb-6 card-enter" style="--i: 4;">
            <div style="position: relative; z-index: 1; text-align: center;">
                <div style="font-size: 12px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">
                    This Month Net Profit
                </div>
                <div class="kpi-card-value count-up" data-value="456800" style="color: white; font-size: 40px;">₹0</div>
                <div style="font-size: 14px; font-weight: 600; margin-top: 12px; display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 255, 255, 0.1); padding: 6px 12px; border-radius: 20px;">
                    <i class="ti ti-trending-up"></i>
                    +32% from last month
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="card" style="position: relative; min-height: 320px;" id="barChartContainer">
                <div style="display: none;">
                    <div style="font-size: 14px; font-weight: 600; margin-bottom: 16px;">Last 7 Days (Sales vs Expenses)</div>
                    <canvas id="barChart" height="80"></canvas>
                </div>
            </div>

            <div class="card" style="position: relative; min-height: 320px;" id="doughnutChartContainer">
                <div style="display: none;">
                    <div style="font-size: 14px; font-weight: 600; margin-bottom: 16px;">Payment Split</div>
                    <canvas id="doughnutChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 card-enter" style="--i: 5;">
            <a href="<?php echo BASE_URL; ?>pages/daily_entry.php" class="card" style="text-align: center; cursor: pointer; text-decoration: none;">
                <div style="font-size: 36px; color: var(--success); margin-bottom: 12px;">
                    <i class="ti ti-pencil-plus"></i>
                </div>
                <div style="font-size: 14px; font-weight: 600; color: var(--text);">Add Today's Entry</div>
            </a>

            <a href="<?php echo BASE_URL; ?>pages/online_sales.php" class="card" style="text-align: center; cursor: pointer; text-decoration: none;">
                <div style="font-size: 36px; color: #F97316; margin-bottom: 12px;">
                    <i class="ti ti-shopping-cart"></i>
                </div>
                <div style="font-size: 14px; font-weight: 600; color: var(--text);">Online Sales</div>
            </a>

            <a href="<?php echo BASE_URL; ?>pages/weekly_report.php" class="card" style="text-align: center; cursor: pointer; text-decoration: none;">
                <div style="font-size: 36px; color: var(--info); margin-bottom: 12px;">
                    <i class="ti ti-file-analytics"></i>
                </div>
                <div style="font-size: 14px; font-weight: 600; color: var(--text);">View Reports</div>
            </a>
        </div>
    </div>

    <!-- Bottom Nav -->
    <div class="bottom-nav lg:hidden">
        <a href="<?php echo BASE_URL; ?>pages/dashboard.php" class="nav-item active">
            <i class="ti ti-layout-dashboard"></i>
            Dashboard
        </a>
        <a href="<?php echo BASE_URL; ?>pages/daily_entry.php" class="nav-item">
            <i class="ti ti-pencil-plus"></i>
            Entry
        </a>
        <a href="<?php echo BASE_URL; ?>pages/weekly_report.php" class="nav-item">
            <i class="ti ti-calendar-week"></i>
            Weekly
        </a>
        <a href="<?php echo BASE_URL; ?>pages/monthly_report.php" class="nav-item">
            <i class="ti ti-calendar-month"></i>
            Monthly
        </a>
        <a href="<?php echo BASE_URL; ?>pages/settings.php" class="nav-item">
            <i class="ti ti-settings"></i>
            Settings
        </a>
    </div>

    <script>
        function loadDashboard() {
            setTimeout(() => {
                // Show content
                document.querySelectorAll('.kpi-card').forEach(card => {
                    card.classList.remove('skeleton-card');
                    const content = card.querySelector('.kpi-content');
                    if (content) content.style.display = '';
                });

                document.querySelectorAll('[id*="ChartContainer"]').forEach(container => {
                    const content = container.querySelector('div');
                    if (content) content.style.display = '';
                });

                // Initialize charts
                initCharts();
                
                // Count-up animations
                triggerCountUp();

                // Mouse tracking
                attachMouseTracking();
            }, 1500);
        }

        function attachMouseTracking() {
            document.querySelectorAll('.kpi-card').forEach(card => {
                card.addEventListener('mousemove', (e) => {
                    const rect = card.getBoundingClientRect();
                    const x = ((e.clientX - rect.left) / rect.width) * 100;
                    const y = ((e.clientY - rect.top) / rect.height) * 100;
                    card.style.setProperty('--mx', x + '%');
                    card.style.setProperty('--my', y + '%');
                });
            });
        }

        function countUp(el, target, duration = 1200) {
            const start = performance.now();
            const update = (now) => {
                const progress = Math.min((now - start) / duration, 1);
                const ease = 1 - Math.pow(1 - progress, 3);
                const value = Math.floor(ease * target);
                el.textContent = '₹' + value.toLocaleString('en-IN');
                if (progress < 1) requestAnimationFrame(update);
            };
            requestAnimationFrame(update);
        }

        function triggerCountUp() {
            document.querySelectorAll('.count-up').forEach(el => {
                const target = parseInt(el.dataset.value);
                countUp(el, target);
            });
        }

        function initCharts() {
            // Bar Chart
            const barCtx = document.getElementById('barChart');
            if (barCtx) {
                new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [
                            {
                                label: 'Sales',
                                data: [18500, 16200, 19800, 21500, 17800, 24500, 18500],
                                backgroundColor: '#1E7B4B',
                                borderRadius: 8,
                            },
                            {
                                label: 'Expenses',
                                data: [3200, 2800, 3500, 3100, 2900, 3800, 3200],
                                backgroundColor: '#DC2626',
                                borderRadius: 8,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { labels: { font: { size: 12 } } } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#F3F4F6' }
                            }
                        }
                    }
                });
            }

            // Doughnut Chart
            const doughnutCtx = document.getElementById('doughnutChart');
            if (doughnutCtx) {
                new Chart(doughnutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Cash', 'GPay', 'Online'],
                        datasets: [{
                            data: [45, 35, 20],
                            backgroundColor: ['#1E7B4B', '#3B82F6', '#F59E0B'],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }
        }

        loadDashboard();
    </script>
</body>
</html>
```

---

## 3. QUICK IMPLEMENTATION CHECKLIST

### For Each PHP Page:
- [ ] Add `<?php session_start(); ?> and authentication check at top
- [ ] Include CSS/JS links in `<head>`
- [ ] Use `<div class="top-header">` structure
- [ ] Add bottom nav with `class="bottom-nav lg:hidden"`
- [ ] Use CSS classes: `.card`, `.kpi-card`, `.btn`, `.btn-primary`, `.form-group`
- [ ] Replace browser `confirm()` with modal overlays
- [ ] Add `card-enter` class with `--i` variable for staggered animations
- [ ] Use `class="skeleton" id="..."` for loading states
- [ ] Use `.toast` for success/error messages
- [ ] Apply responsive grid: `grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4`

### JavaScript Utilities (Create in `assets/js/premium-helpers.js`):
```javascript
// Count-up animation
function countUp(el, target, duration = 1200) {
    const start = performance.now();
    const update = (now) => {
        const progress = Math.min((now - start) / duration, 1);
        const ease = 1 - Math.pow(1 - progress, 3);
        const value = Math.floor(ease * target);
        el.textContent = '₹' + value.toLocaleString('en-IN');
        if (progress < 1) requestAnimationFrame(update);
    };
    requestAnimationFrame(update);
}

// Modal helper
function showModal(html, onConfirm) {
    const overlay = document.createElement('div');
    overlay.className = 'modal-overlay active';
    overlay.innerHTML = html;
    document.body.appendChild(overlay);
    overlay.querySelector('.modal-close').onclick = () => overlay.remove();
}

// Toast helper
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    const icons = { success: 'ti-check', error: 'ti-x', warning: 'ti-alert-triangle', info: 'ti-info-circle' };
    toast.innerHTML = `<i class="ti ${icons[type]}"></i><span>${message}</span>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Mouse tracking for KPI cards
function attachMouseTracking(selector = '.kpi-card') {
    document.querySelectorAll(selector).forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            card.style.setProperty('--mx', x + '%');
            card.style.setProperty('--my', y + '%');
        });
    });
}
```

---

## 4. KEY DESIGN PATTERNS

### Form Validation
```php
<?php
$errors = [];
if ($_POST) {
    if (empty($_POST['username'])) $errors['username'] = 'Username required';
    if (empty($_POST['password'])) $errors['password'] = 'Password required';
}
?>

<div class="form-group <?php echo isset($errors['username']) ? 'form-error' : ''; ?>">
    <label class="form-label">Username</label>
    <input class="form-input" type="text" name="username">
    <?php if (isset($errors['username'])): ?>
        <div class="error-message"><?php echo $errors['username']; ?></div>
    <?php endif; ?>
</div>
```

### Modal Confirmation
```php
<div class="modal-overlay" id="deleteModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Confirm Delete</div>
            <button class="modal-close" onclick="this.closest('.modal-overlay').classList.remove('active')">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Are you sure? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="this.closest('.modal-overlay').classList.remove('active')">Cancel</button>
            <button class="btn btn-primary" onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>
```

---

## 5. IMPLEMENTATION ORDER

1. **Day 1**: Create `premium-design.css` + redesign Login page
2. **Day 2**: Redesign Dashboard with KPI cards & charts
3. **Day 3**: Redesign Daily Entry with entry cards
4. **Day 4**: Redesign Reports (Weekly & Monthly)
5. **Day 5**: Redesign Online Sales & Settings
6. **Day 6**: Redesign Auth pages (Forgot Password, 2FA)
7. **Day 7**: Testing & refinement

---

All pages now use a consistent, premium design system with:
✅ Responsive mobile-first layout
✅ Smooth animations & interactions
✅ Professional color palette & typography
✅ Tailwind utilities for flexibility
✅ No images needed (CSS-only gradients & shapes)
✅ Tabler icons throughout
✅ Modal-based confirmations
✅ Toast notifications
✅ Skeleton loaders
