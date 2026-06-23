<?php
include 'config.php';
try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database: $db\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "No tables found in database.\n";
    } else {
        echo "Tables:\n";
        foreach ($tables as $table) {
            echo "- $table\n";
            $columnsStmt = $pdo->query("DESCRIBE `$table`");
            $columns = $columnsStmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($columns as $column) {
                echo "  * {$column['Field']} ({$column['Type']}) - Null: {$column['Null']}, Key: {$column['Key']}\n";
            }
        }
    }
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage() . "\n";
}
