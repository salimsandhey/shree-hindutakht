<?php
// Script to check all users in all relevant tables
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=shree_hindutakht;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database connection successful!\n\n";
    
    // Check users table
    echo "Checking users table:\n";
    try {
        $stmt = $pdo->query("SELECT id, name, email, role FROM users LIMIT 10");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($users) > 0) {
            foreach ($users as $user) {
                echo "  ID: " . $user['id'] . ", Name: " . $user['name'] . ", Email: " . $user['email'] . ", Role: " . $user['role'] . "\n";
            }
        } else {
            echo "  No users found\n";
        }
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
    
    // Check admins table
    echo "Checking admins table:\n";
    try {
        $stmt = $pdo->query("SELECT id, name, username, email FROM admins LIMIT 10");
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($admins) > 0) {
            foreach ($admins as $admin) {
                echo "  ID: " . $admin['id'] . ", Name: " . $admin['name'] . ", Username: " . $admin['username'] . ", Email: " . $admin['email'] . "\n";
            }
        } else {
            echo "  No admins found\n";
        }
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
    
    // Check members table
    echo "Checking members table:\n";
    try {
        $stmt = $pdo->query("SELECT id, member_id, name, email FROM members LIMIT 10");
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($members) > 0) {
            foreach ($members as $member) {
                echo "  ID: " . $member['id'] . ", Member ID: " . $member['member_id'] . ", Name: " . $member['name'] . ", Email: " . $member['email'] . "\n";
            }
        } else {
            echo "  No members found\n";
        }
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
    
    // Let's also check if there are any migrations that haven't been run
    echo "Checking migrations:\n";
    try {
        $stmt = $pdo->query("SELECT migration, batch FROM migrations ORDER BY batch, migration");
        $migrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($migrations) > 0) {
            echo "  Total migrations: " . count($migrations) . "\n";
            echo "  Latest batch: " . max(array_column($migrations, 'batch')) . "\n";
        } else {
            echo "  No migrations found\n";
        }
    } catch (Exception $e) {
        echo "  Error checking migrations: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>