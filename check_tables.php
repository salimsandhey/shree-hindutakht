<?php
// Script to check all tables and find user
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=shree_hindutakht;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database connection successful!\n\n";
    
    // List all tables
    $stmt = $pdo->query("SHOW TABLES");
    echo "Available tables:\n";
    $tables = [];
    while ($row = $stmt->fetch()) {
        echo "- " . $row[0] . "\n";
        $tables[] = $row[0];
    }
    
    echo "\n";
    
    // Check each table for user data
    $email = 'salimsandhey@gmail.com';
    
    foreach ($tables as $table) {
        // Try to see if this table has email column
        try {
            $columnsStmt = $pdo->prepare("SHOW COLUMNS FROM `$table` WHERE Field = 'email'");
            $columnsStmt->execute();
            
            if ($columnsStmt->rowCount() > 0) {
                echo "Checking table '$table' for user with email '$email'...\n";
                $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE email = ?");
                $stmt->execute([$email]);
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($users) > 0) {
                    echo "Found " . count($users) . " user(s) in table '$table':\n";
                    foreach ($users as $user) {
                        echo "  ID: " . $user['id'] . "\n";
                        echo "  Name: " . ($user['name'] ?? $user['username'] ?? 'N/A') . "\n";
                        echo "  Email: " . $user['email'] . "\n";
                        if (isset($user['password'])) {
                            echo "  Password hash: " . $user['password'] . "\n";
                        }
                        echo "\n";
                    }
                } else {
                    echo "No user found in table '$table'\n";
                }
            }
        } catch (Exception $e) {
            // Skip tables we can't access
        }
    }
    
    // Also check for common user table names
    $commonTables = ['users', 'members', 'admins', 'member', 'admin'];
    foreach ($commonTables as $table) {
        if (!in_array($table, $tables)) {
            continue;
        }
        
        echo "Checking table '$table' structure:\n";
        try {
            $stmt = $pdo->query("SHOW COLUMNS FROM `$table`");
            while ($row = $stmt->fetch()) {
                echo "  - " . $row['Field'] . " (" . $row['Type'] . ")\n";
            }
        } catch (Exception $e) {
            echo "  Could not get structure: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>