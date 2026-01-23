<?php
// Script to reset password for salimsandhey@gmail.com
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=shree_hindutakht;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database connection successful!\n\n";
    
    // First, let's check if the user exists
    $stmt = $pdo->prepare("SELECT id, name, email FROM members WHERE email = ?");
    $stmt->execute(['salimsandhey@gmail.com']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // User exists, update password
        $newPassword = 'admin123';
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $updateStmt = $pdo->prepare("UPDATE members SET password = ? WHERE email = ?");
        $updateStmt->execute([$hashedPassword, 'salimsandhey@gmail.com']);
        
        echo "Password reset successfully for user:\n";
        echo "  ID: " . $user['id'] . "\n";
        echo "  Name: " . $user['name'] . "\n";
        echo "  Email: " . $user['email'] . "\n";
        echo "  New password: $newPassword\n";
    } else {
        // User doesn't exist, let's create them
        echo "User with email 'salimsandhey@gmail.com' not found. Creating new user...\n";
        
        $newPassword = 'admin123';
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Generate a unique member ID
        $memberId = 'HT' . str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
        
        $insertStmt = $pdo->prepare("INSERT INTO members (member_id, name, email, password, phone, address, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $insertStmt->execute([
            $memberId,
            'Salim Sandhey',
            'salimsandhey@gmail.com',
            $hashedPassword,
            '', // phone
            '', // address
            'active',
        ]);
        
        $userId = $pdo->lastInsertId();
        
        echo "New user created successfully:\n";
        echo "  ID: $userId\n";
        echo "  Member ID: $memberId\n";
        echo "  Name: Salim Sandhey\n";
        echo "  Email: salimsandhey@gmail.com\n";
        echo "  Password: $newPassword\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>