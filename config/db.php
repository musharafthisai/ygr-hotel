<?php
// c:/xampp/htdocs/yarahman/config/db.php

// Error reporting for development. Disable in production if needed.
error_reporting(E_ALL);
ini_set('display_errors', 1); // Show errors for debugging

date_default_timezone_set('Asia/Kolkata');


$host = 'localhost';
$db   = 'briyani_shop'; // Changed to match your local database name
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // Programmatic migration: Auto-add allowed_categories to users if it doesn't exist
    try {
        $pdo->query("SELECT allowed_categories FROM users LIMIT 1");
    } catch (\PDOException $col_ex) {
        try {
            $pdo->exec("ALTER TABLE users ADD COLUMN allowed_categories VARCHAR(50) DEFAULT 'all'");
        } catch (\PDOException $alter_ex) {
            // Log or ignore
        }
    }
} catch (\PDOException $e) {
    // Hide actual errors in production
    die("Database connection failed. Please contact the administrator.");
}
?>
