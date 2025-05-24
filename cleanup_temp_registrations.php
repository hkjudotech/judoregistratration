<?php
// cleanup_temp_registrations.php
// Run this script periodically (e.g., via cron job) to clean up old temp registrations

require_once 'config.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );
    
    // Delete temp registrations older than 24 hours
    $stmt = $pdo->prepare("DELETE FROM temp_registrations WHERE created_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)");
    $result = $stmt->execute();
    $deletedCount = $stmt->rowCount();
    
    echo date('c') . " - Cleanup completed. Deleted $deletedCount old temp registrations.\n";
    
} catch(PDOException $e) {
    echo date('c') . " - Cleanup failed: " . $e->getMessage() . "\n";
}
?>
