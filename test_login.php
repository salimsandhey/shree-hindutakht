<?php
// Test script to check database connection and user credentials
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=shree_hindutakht;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database connection successful!\n\n";
    
    // Check if admins table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'admins'");
    if ($stmt->rowCount() > 0) {
        echo "Admins table exists\n";
        
        // Check if the user exists
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute(['salimsandhey@gmail.com']);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "User found:\n";
            echo "ID: " . $user['id'] . "\n";
            echo "Name: " . $user['name'] . "\n";
            echo "Email: " . $user['email'] . "\n";
            echo "Password hash: " . $user['password'] . "\n";
            
            // Verify password
            if (password_verify('admin123', $user['password'])) {
                echo "Password is correct!\n";
            } else {
                echo "Password is incorrect!\n";
                echo "Trying to check if it's using a different hash...\n";
                
                // Check if it matches directly (not recommended but for testing)
                if ($user['password'] === 'admin123') {
                    echo "Password matches directly (not hashed)!\n";
                } else {
                    echo "Password doesn't match directly either.\n";
                }
            }
        } else {
            echo "User not found in admins table\n";
            
            // Check if members table exists and user might be there
            $stmt = $pdo->query("SHOW TABLES LIKE 'members'");
            if ($stmt->rowCount() > 0) {
                echo "Members table exists, checking there...\n";
                $stmt = $pdo->prepare("SELECT * FROM members WHERE email = ?");
                $stmt->execute(['salimsandhey@gmail.com']);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user) {
                    echo "User found in members table:\n";
                    echo "ID: " . $user['id'] . "\n";
                    echo "Name: " . $user['name'] . "\n";
                    echo "Email: " . $user['email'] . "\n";
                    echo "Password hash: " . $user['password'] . "\n";
                    
                    // Verify password
                    if (password_verify('admin123', $user['password'])) {
                        echo "Password is correct!\n";
                    } else {
                        echo "Password is incorrect!\n";
                    }
                } else {
                    echo "User not found in members table either\n";
                }
            }
        }
    } else {
        echo "Admins table does not exist\n";
        
        // List all tables to see what we have
        $stmt = $pdo->query("SHOW TABLES");
        echo "Available tables:\n";
        while ($row = $stmt->fetch()) {
            echo "- " . $row[0] . "\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>