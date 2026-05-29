<?php
// c:/xampp/htdocs/yarahman/config/constants.php

define('APP_NAME', 'YGR signature Shop Manager');
define('APP_VERSION', '1.0');

// Fixed items list as per requirements
const ITEMS_SALES = [
    'Chicken Briyani (Day)',
    'Chicken Briyani (Eve)',
    'Chicken Briyani (Nig)',
    'Mutton Briyani'
];

const ITEMS_EXPENSE = [
    'Meat',
    'Vegetable',
    'Stock',
    'Cylinder',
    'Fish/Prawn',
    'Curd',
    'Kubbus',
    'Gas',
    'Beverages',
    'Tea / Others',
    'Salary',
    'RENT / EB'
];

const ROLES = [
    'owner' => 'Owner',
    'branch_admin' => 'Branch Admin',
    'staff' => 'Staff'
];

/* ============================================================
   FOOD IMAGES — Local SVG Placeholder System
   Generates clean SVG data URIs with item initials on
   category-themed gradients. Never broken, no network req.
   Place real JPGs in assets/img/items/{slug}.jpg to override.
   ============================================================ */
/**
 * Generate an SVG data URI placeholder for an item.
 * Shows first letter of item name on a gradient background.
 */
function getFoodImageDataUri(string $name, string $category = 'sales'): string {
    $initial = strtoupper(substr(trim($name), 0, 1)) ?: '?';
    $c1 = $category === 'sales' ? '#0D2818' : '#DC2626';
    $c2 = $category === 'sales' ? '#1E7B4B' : '#EF4444';
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300">'
         . '<defs><linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="100%">'
         . '<stop offset="0%" style="stop-color:' . $c1 . '"/>'
         . '<stop offset="100%" style="stop-color:' . $c2 . '"/>'
         . '</linearGradient></defs>'
         . '<rect width="400" height="300" fill="url(#g)"/>'
         . '<text x="200" y="155" font-family="Syne,sans-serif" font-size="80" font-weight="800" fill="white" text-anchor="middle" dominant-baseline="middle">' . $initial . '</text>'
         . '</svg>';
    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}

/**
 * Get food image for an item — tries local file first, falls back to SVG.
 * @param string $name Item name
 * @param string $category 'sales' or 'expense'
 * @return string URL or data URI (never empty, never broken)
 */
function getFoodImageUrl(string $name, string $category = 'sales'): string {
    $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', trim($name)));
    $slug = trim($slug, '-');
    $base_dir = __DIR__ . '/../assets/img/items/';
    foreach (['jpg', 'jpeg', 'png', 'webp', 'gif'] as $ext) {
        if (file_exists($base_dir . $slug . '.' . $ext)) {
            $base = (strpos($_SERVER['SCRIPT_NAME'] ?? '', '/yarahman') !== false) ? '/yarahman/' : '/';
            return $base . 'assets/img/items/' . $slug . '.' . $ext;
        }
    }
    return getFoodImageDataUri($name, $category);
}
?>
