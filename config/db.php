<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
date_default_timezone_set('Asia/Kolkata');

$host   = getenv('DB_HOST') ?: 'localhost';
$db     = getenv('DB_NAME') ?: 'briyani_shop';
$user   = getenv('DB_USER') ?: 'root';
$pass   = getenv('DB_PASS') ?: '';
$socket = getenv('DB_SOCKET') ?: '';

if ($socket) {
    $dsn = "mysql:unix_socket=$socket;dbname=$db;charset=utf8mb4";
} else {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
}

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    try {
        $pdo->query("SELECT allowed_categories FROM users LIMIT 1");
    } catch (\PDOException $col_ex) {
        try {
            $pdo->exec("ALTER TABLE users ADD COLUMN allowed_categories VARCHAR(50) DEFAULT 'all'");
        } catch (\PDOException $alter_ex) {}
    }
} catch (\PDOException $e) {
    die("Database connection failed. Please contact the administrator.");
}
