<?php
// paypal_webhook.php
// TODO: Implement additional webhook event types and improve error handling

require_once 'config.php';

$DEBUG = DEBUG_MODE;

// Read the raw POST body from PayPal
$body = file_get_contents('php://input');
$event = json_decode($body, true);

// Debug logging
if ($DEBUG) {
    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Raw body: " . $body . PHP_EOL, FILE_APPEND);
    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Decoded event: " . print_r($event, true) . PHP_EOL, FILE_APPEND);
}

// Only process payment completed events 'CHECKOUT.ORDER.APPROVED'
if (isset($event['event_type']) && $event['event_type'] === 'CHECKOUT.ORDER.APPROVED') {
    if ($DEBUG) {
        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] CHECKOUT.ORDER.APPROVED event detected" . PHP_EOL, FILE_APPEND);
    }

    $custom_id = $event['resource']['purchase_units'][0]['custom_id'] ?? null;
    $payer_email = $event['resource']['payer']['email_address'] ?? null;

    if ($DEBUG) {
        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] custom_id: **" . $custom_id . "**, payer_email: " . $payer_email . PHP_EOL, FILE_APPEND);
    }

    if ($custom_id) {
        if ($DEBUG) {
            file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Custom ID found. Connecting to Database --------------------------> " . PHP_EOL, FILE_APPEND);
        }

        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );
        } catch(PDOException $e) {
            if ($DEBUG) {
                file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [ERROR] Connection failed: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            }
            http_response_code(500);
            echo "Database connection error";
            exit;
        }
        
        if ($DEBUG) {
            file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] PDO obtained. Processing payment..." . PHP_EOL, FILE_APPEND);
        }

        try {
            // Begin transaction
            $pdo->beginTransaction();

            // Determine payment type based on custom_id prefix
            if (strpos($custom_id, 'club_') === 0) {
                // Club membership payment
                if ($DEBUG) {
                    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Processing club payment" . PHP_EOL, FILE_APPEND);
                }
                
                $stmt = $pdo->prepare("UPDATE club_payments SET paid = 1, payer_email = ?, paid_at = NOW() WHERE custom_id = ?");
                $result = $stmt->execute([$payer_email, $custom_id]);
                
                if (!$result) {
                    throw new Exception("Club payment update failed: " . print_r($stmt->errorInfo(), true));
                }
                
                if ($DEBUG) {
                    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Club payment updated successfully" . PHP_EOL, FILE_APPEND);
                }
                
            } elseif (strpos($custom_id, 'event_') === 0) {
                // Event registration payment - only update local table
                if ($DEBUG) {
                    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Processing event payment for local table" . PHP_EOL, FILE_APPEND);
                }
                
                // Check if local table has the custom_id column
                $stmt = $pdo->prepare("SHOW COLUMNS FROM `local` LIKE 'custom_id'");
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    // Update records in local table
                    $stmt = $pdo->prepare("UPDATE `local` SET payment = 'paid' WHERE custom_id = ?");
                    $result = $stmt->execute([$custom_id]);
                    
                    $affected_rows = $stmt->rowCount();
                    
                    if ($affected_rows > 0) {
                        if ($DEBUG) {
                            file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Updated $affected_rows records in local table" . PHP_EOL, FILE_APPEND);
                        }
                    } else {
                        if ($DEBUG) {
                            file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [WARNING] No records found with custom_id: $custom_id in local table" . PHP_EOL, FILE_APPEND);
                        }
                    }
                } else {
                    if ($DEBUG) {
                        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [ERROR] Local table does not have custom_id column. Please add it first." . PHP_EOL, FILE_APPEND);
                    }
                    throw new Exception("Local table missing custom_id column");
                }
                
            } else {
                if ($DEBUG) {
                    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [ERROR] Unknown payment type for custom_id: $custom_id" . PHP_EOL, FILE_APPEND);
                }
                throw new Exception("Unknown payment type");
            }

            // Commit transaction
            $pdo->commit();
            
            if ($DEBUG) {
                file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Payment processing completed successfully" . PHP_EOL, FILE_APPEND);
            }

        } catch (Exception $e) {
            // Rollback transaction on error
            $pdo->rollBack();
            
            if ($DEBUG) {
                file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [ERROR] Payment processing failed: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            }
            
            http_response_code(500);
            echo "Payment processing error";
            exit;
        }

    } else {
        if ($DEBUG) {
            file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] No custom_id found, skipping DB update." . PHP_EOL, FILE_APPEND);
        }
    }
} else {
    if ($DEBUG) {
        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Event not processed (not CHECKOUT.ORDER.APPROVED)" . PHP_EOL, FILE_APPEND);
    }
}

// Always respond with 200 OK so PayPal knows you received the webhook
http_response_code(200);
echo "OK";
?>
