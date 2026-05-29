<?php
$sql_file = __DIR__ . '/data/u777110831_briyani_shop (2).sql';
$sql_content = file_get_contents($sql_file);

if ($sql_content === false) {
    die("Failed to read SQL file: $sql_file\n");
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=briyani_shop;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    // Split by semicolons and execute each statement
    $statements = explode(';', $sql_content);
    $count = 0;
    foreach ($statements as $stmt) {
        $stmt = trim($stmt);
        if (!empty($stmt) && !str_starts_with($stmt, '--') && !str_starts_with($stmt, '/*')) {
            try {
                $pdo->exec($stmt);
                $count++;
            } catch (PDOException $e) {
                // Ignore duplicate key errors and other expected errors
                echo "Skipped: " . substr($stmt, 0, 80) . "... -> " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "SQL import completed. Statements executed: $count\n";
    
    // Verify data
    $tables = ['branches', 'daily_entries', 'daily_payments', 'item_rates', 'online_sales', 'users', 'login_attempts'];
    foreach ($tables as $table) {
        try {
            $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
            echo "  $table: $count rows\n";
        } catch (Exception $e) {
            echo "  $table: ERROR - " . $e->getMessage() . "\n";
        }
    }
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}