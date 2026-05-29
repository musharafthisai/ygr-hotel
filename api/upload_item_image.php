<?php
require_once '../auth/session.php';
require_once '../config/db.php';
check_auth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['image']) || !isset($_POST['item_name'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$item_name = trim($_POST['item_name']);
$file = $_FILES['image'];

$allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
if (!in_array($file['type'], $allowed)) {
    echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, WebP & GIF allowed']);
    exit;
}

if ($file['size'] > 2 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'Image must be under 2MB']);
    exit;
}

$slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $item_name));
$slug = trim($slug, '-');
$ext = match ($file['type']) {
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
    'image/gif'  => 'gif',
    default      => 'jpg'
};

$upload_dir = __DIR__ . '/../assets/img/items/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0775, true);

$dest = $upload_dir . $slug . '.' . $ext;

// Remove old files for same item
foreach (['jpg', 'jpeg', 'png', 'webp', 'gif'] as $old_ext) {
    $old = $upload_dir . $slug . '.' . $old_ext;
    if (file_exists($old) && $old !== $dest) unlink($old);
}

if (move_uploaded_file($file['tmp_name'], $dest)) {
    echo json_encode(['success' => true, 'message' => 'Image uploaded', 'url' => 'assets/img/items/' . $slug . '.' . $ext]);
} else {
    echo json_encode(['success' => false, 'message' => 'Upload failed']);
}
