<?php
// Test script to check login API
try {
    // Test login with the new credentials
    $url = 'http://localhost:8000/api/auth/login';
    $data = [
        'email' => 'salimsandhey@gmail.com',
        'password' => 'admin123'
    ];
    
    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ],
    ];
    
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    if ($result === FALSE) {
        echo "Error: Unable to connect to the login API\n";
    } else {
        $response = json_decode($result, true);
        echo "Login API Response:\n";
        echo json_encode($response, JSON_PRETTY_PRINT) . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>