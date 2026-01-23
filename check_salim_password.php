<?php
// Script to check Salim's password
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=shree_hindutakht;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database connection successful!\n\n";
    
    // Check Salim's record
    echo "Checking Salim Ahmed's record:\n";
    try {
        $stmt = $pdo->prepare("SELECT id, member_id, name, email, password FROM members WHERE email = ?");
        $stmt->execute(['salim@hindutakht.com']);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "  ID: " . $user['id'] . "\n";
            echo "  Member ID: " . $user['member_id'] . "\n";
            echo "  Name: " . $user['name'] . "\n";
            echo "  Email: " . $user['email'] . "\n";
            echo "  Password hash: " . $user['password'] . "\n";
            
            // Check if password is set
            if (empty($user['password'])) {
                echo "  Password is empty!\n";
            } else {
                echo "  Password is set\n";
                
                // Try common passwords
                $commonPasswords = ['admin123', 'password', '123456', 'salim123'];
                foreach ($commonPasswords as $password) {
                    if (password_verify($password, $user['password'])) {
                        echo "  Password matches: $password\n";
                        break;
                    }
                }
            }
        } else {
            echo "  User not found\n";
        }
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
    
    // Also check the admin user
    echo "Checking admin user:\n";
    try {
        $stmt = $pdo->prepare("SELECT id, name, username, email, password FROM admins WHERE email = ?");
        $stmt->execute(['admin@hindutakht.com']);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "  ID: " . $user['id'] . "\n";
            echo "  Name: " . $user['name'] . "\n";
            echo "  Username: " . $user['username'] . "\n";
            echo "  Email: " . $user['email'] . "\n";
            echo "  Password hash: " . $user['password'] . "\n";
            
            // Try common passwords
            $commonPasswords = ['admin123', 'password', '123456', 'admin'];
            foreach ($commonPasswords as $password) {
                if (password_verify($password, $user['password'])) {
                    echo "  Password matches: $password\n";
                    break;
                }
            }
        } else {
            echo "  Admin user not found\n";
        }
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>