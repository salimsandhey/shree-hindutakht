<?php
// Test script to check database functionality for likes
require_once 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

// Set up Eloquent ORM
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'hindutakht',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);
$capsule->setEventDispatcher(new Dispatcher(new Container));
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Test database connection
try {
    $pdo = $capsule->getConnection()->getPdo();
    echo "Database connection successful!\n\n";
    
    // Check if posts table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'posts'");
    if ($stmt->rowCount() > 0) {
        echo "Posts table exists\n";
        
        // Check structure of posts table
        $stmt = $pdo->query("DESCRIBE posts");
        echo "Posts table structure:\n";
        while ($row = $stmt->fetch()) {
            echo "  {$row['Field']} ({$row['Type']})\n";
        }
        echo "\n";
    } else {
        echo "Posts table does not exist\n";
    }
    
    // Check if post_likes table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'post_likes'");
    if ($stmt->rowCount() > 0) {
        echo "Post likes table exists\n";
        
        // Check structure of post_likes table
        $stmt = $pdo->query("DESCRIBE post_likes");
        echo "Post likes table structure:\n";
        while ($row = $stmt->fetch()) {
            echo "  {$row['Field']} ({$row['Type']})\n";
        }
        echo "\n";
    } else {
        echo "Post likes table does not exist\n";
    }
    
    // Test inserting a like
    echo "Testing like insertion...\n";
    $postId = 1; // Test with post ID 1
    $memberId = 1; // Test with member ID 1
    
    // Check if like already exists
    $stmt = $pdo->prepare("SELECT * FROM post_likes WHERE post_id = ? AND member_id = ?");
    $stmt->execute([$postId, $memberId]);
    if ($stmt->rowCount() > 0) {
        echo "Like already exists, deleting it first...\n";
        $stmt = $pdo->prepare("DELETE FROM post_likes WHERE post_id = ? AND member_id = ?");
        $stmt->execute([$postId, $memberId]);
    }
    
    // Insert new like
    $stmt = $pdo->prepare("INSERT INTO post_likes (post_id, member_id, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
    $result = $stmt->execute([$postId, $memberId]);
    
    if ($result) {
        echo "Like inserted successfully!\n";
        
        // Update likes_count in posts table
        $stmt = $pdo->prepare("UPDATE posts SET likes_count = likes_count + 1 WHERE id = ?");
        $stmt->execute([$postId]);
        echo "Likes count updated in posts table\n";
        
        // Count total likes for this post
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM post_likes WHERE post_id = ?");
        $stmt->execute([$postId]);
        $row = $stmt->fetch();
        echo "Total likes for post {$postId}: {$row['count']}\n";
        
        // Get current likes_count from posts table
        $stmt = $pdo->prepare("SELECT likes_count FROM posts WHERE id = ?");
        $stmt->execute([$postId]);
        $row = $stmt->fetch();
        echo "Posts table likes_count for post {$postId}: {$row['likes_count']}\n";
    } else {
        echo "Failed to insert like\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>