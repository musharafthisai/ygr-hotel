/* ============================================================
   BRIYANI SHOP MANAGER — Premium App Framework v2
   Shared utilities for all pages
   ============================================================ */

// ============================================================
// GLOBAL STATE — Mobile Navigation
// ============================================================
let currentPageIndex = 0;
const pageNames = ['entry', 'weekly', 'monthly', 'online', 'settings'];
const pageUrls = [
    'daily_entry.php',
    'weekly_report.php',
    'monthly_report.php',
    'online_sales.php',
    'settings.php'
];
const pageIcons = [
    'ti-pencil-plus',
    'ti-calendar-week',
    'ti-calendar-month',
    'ti-truck-delivery',
    'ti-settings'
];

// ============================================================
// DOM READY
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    initFloatingNav();
    initScrollHideNav();
    initStaggerAnimations();
    initSkeletonLoaders();
});

// ============================================================
// FLOATING PILL NAVBAR — Premium Navigation
// ============================================================
function initFloatingNav() {
    const nav = document.getElementById('floatingNav');
    if (nav) nav.classList.remove('hidden');

    const activeNav = document.querySelector('.floating-nav .nav-item.active');
    if (activeNav) {
        currentPageIndex = parseInt(activeNav.dataset.index) || 0;
    }
}

function setNav(index) {
    const items = document.querySelectorAll('.floating-nav .nav-item');
    items.forEach((item, i) => {
        item.classList.toggle('active', i === index);
    });
    currentPageIndex = index;

    // Haptic feedback (brief vibration on supported devices)
    if (navigator.vibrate) {
        navigator.vibrate(10);
    }

    // Animate icon
    const activeItem = items[index];
    if (activeItem) {
        const icon = activeItem.querySelector('i');
        if (icon) {
            icon.style.transition = 'transform 300ms cubic-bezier(0.34, 1.56, 0.64, 1)';
            icon.style.transform = 'scale(1.3)';
            setTimeout(() => { icon.style.transform = ''; }, 300);
        }
    }

    // Navigate after brief delay for feedback
    if (pageUrls[index]) {
        setTimeout(() => {
            window.location.href = pageUrls[index];
        }, 150);
    }
}

// ============================================================
// SCROLL HIDE/SHOW FOR FLOATING NAV — Smooth auto-hide
// ============================================================
let lastScroll = 0;
let navScrollTimer = null;
function initScrollHideNav() {
    var nav = document.getElementById('floatingNav');
    if (nav) nav.classList.remove('hidden');
}

// ============================================================
// STAGGER ANIMATIONS
// ============================================================
function initStaggerAnimations() {
    document.querySelectorAll('.stagger').forEach((el, i) => {
        el.style.animationDelay = (i * 0.04) + 's';
    });
}

// ============================================================
// SKELETON LOADERS
// ============================================================
function initSkeletonLoaders() {
    // Auto-hide skeleton after timeout if no real data
    setTimeout(() => {
        document.querySelectorAll('.skeleton-loader').forEach(el => {
            el.classList.remove('skeleton', 'skeleton-card', 'skeleton-chart');
            const content = el.querySelector('.content-real');
            if (content) content.style.display = '';
        });
    }, 2000);
}

// ============================================================
// TOAST SYSTEM
// ============================================================
function showToast(message, type = 'success', duration = 4000) {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    
    const toast = document.createElement('div');
    toast.className = `toast ${type === 'error' ? 'error' : type === 'warning' ? 'warning' : 'success'}`;
    
    const iconMap = {
        success: 'ti ti-circle-check',
        error: 'ti ti-alert-circle',
        warning: 'ti ti-alert-triangle',
        info: 'ti ti-info-circle'
    };
    const icon = iconMap[type] || iconMap.success;
    
    toast.innerHTML = `
        <i class="${icon}"></i>
        <span>${message}</span>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i class="ti ti-x"></i>
        </button>
        <div class="toast-progress"></div>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }
    }, duration);
}

// ============================================================
// LOADER SYSTEM
// ============================================================
function showLoader() {
    let overlay = document.querySelector('.loader-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'loader-overlay';
        overlay.innerHTML = '<div class="loader-spinner"></div>';
        document.body.appendChild(overlay);
    }
    overlay.classList.add('active');
}

function hideLoader() {
    const overlay = document.querySelector('.loader-overlay');
    if (overlay) overlay.classList.remove('active');
}

// ============================================================
// CONFIRM MODAL (replaces browser confirm())
// ============================================================
function showConfirmModal(message, confirmText = 'Confirm', cancelText = 'Cancel') {
    return new Promise((resolve) => {
        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';
        overlay.innerHTML = `
            <div class="modal-content" style="max-width: 420px;">
                <div class="modal-body">
                    <div class="confirm-dialog">
                        <p>${message}</p>
                        <div class="confirm-actions">
                            <button class="btn btn-outline btn-cancel">${cancelText}</button>
                            <button class="btn btn-primary btn-confirm">${confirmText}</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(overlay);
        
        overlay.querySelector('.btn-confirm').addEventListener('click', () => {
            overlay.remove();
            resolve(true);
        });
        
        overlay.querySelector('.btn-cancel').addEventListener('click', () => {
            overlay.remove();
            resolve(false);
        });
        
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.remove();
                resolve(false);
            }
        });
    });
}

// ============================================================
// FORMATTING UTILITIES
// ============================================================
function formatCurrency(amount) {
    return '₹' + Math.round(amount).toLocaleString('en-IN');
}

function formatCurrencyFull(amount) {
    return '₹' + Number(amount).toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function getFoodImageDataUri(name, category = 'sales') {
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

function getFoodImage(itemName, category = 'sales') {
    return getFoodImageDataUri(itemName, category);
}

function getFoodImageUrl(name, category = 'sales') {
    return getFoodImageDataUri(name, category);
}

// ============================================================
// COUNT-UP ANIMATION
// ============================================================
function animateCountUp(element, targetValue, duration = 1200) {
    const startTime = performance.now();
    const startValue = 0;
    
    function update(currentTime) {
        const progress = Math.min((currentTime - startTime) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const currentValue = Math.floor(eased * targetValue);
        element.textContent = '₹' + currentValue.toLocaleString('en-IN');
        
        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }
    requestAnimationFrame(update);
}

function triggerCountUp() {
    document.querySelectorAll('.count-up').forEach(el => {
        const text = el.innerText.replace(/[₹,]/g, '');
        const target = parseInt(text) || 0;
        animateCountUp(el, target);
    });
}

// ============================================================
// MOUSE TRACKING ON KPI CARDS
// ============================================================
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

// ============================================================
// INTERSECTION OBSERVER FOR ANIMATIONS
// ============================================================
function observeElements(elements, callback) {
    if (!elements || elements.length === 0) return;
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                callback(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    elements.forEach(el => observer.observe(el));
}

// ============================================================
// IMAGE ERROR HANDLING
// ============================================================
function handleImageError(img, fallbackText = '?') {
    img.style.display = 'none';
    const fallback = img.nextElementSibling;
    if (fallback) {
        fallback.style.display = 'flex';
        if (fallback.classList.contains('img-fallback-text')) {
            fallback.textContent = fallbackText;
        }
    }
}

// ============================================================
// MODAL HELPERS
// ============================================================
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// ============================================================
// MOBILE MODE DETECTION
// ============================================================
function isMobile() {
    return window.innerWidth < 1024;
}

// Initialize mobile mode
if (isMobile()) {
    document.body.classList.add('mobile-mode');
}

window.addEventListener('resize', () => {
    if (window.innerWidth < 1024) {
        document.body.classList.add('mobile-mode');
    } else {
        document.body.classList.remove('mobile-mode');
    }
});

// ============================================================
// SAFE JSON PARSE
// ============================================================
function safeJsonParse(str, fallback = null) {
    try {
        return JSON.parse(str);
    } catch (e) {
        return fallback;
    }
}

// ============================================================
// DEBOUNCE
// ============================================================
function debounce(func, wait = 300) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}