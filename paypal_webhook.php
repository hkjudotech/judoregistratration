<?php
// paypal_webhook.php

$DEBUG = true; // Set to false to turn off debugging

// Read the raw POST body from PayPal
$body = file_get_contents('php://input');
$event = json_decode($body, true);

// Debug logging
if ($DEBUG) {
    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Raw body: " . $body . PHP_EOL, FILE_APPEND);
    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Decoded event: " . print_r($event, true) . PHP_EOL, FILE_APPEND);
}

// Only process payment completed events 'CHECKOUT.ORDER.APPROVED' 'PAYMENT.CAPTURE.COMPLETED'
if (isset($event['event_type']) && $event['event_type'] === 'CHECKOUT.ORDER.APPROVED') {
    if ($DEBUG) {
        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] PAYMENT.CAPTURE.COMPLETED event detected" . PHP_EOL, FILE_APPEND);
    }

    $custom_id = $event['resource']['custom_id'] ?? null;
    $payer_email = $event['resource']['payer']['email_address'] ?? null;

    if ($DEBUG) {
        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] custom_id: **" . $custom_id . "**, payer_email: " . $payer_email . PHP_EOL, FILE_APPEND);
    }

    if ($custom_id) {
        
        if ($DEBUG) {
        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Custom ID found. Connecting to Database --------------------------> " . PHP_EOL, FILE_APPEND);
        }

        // Connect to database
        include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php"); // for $pdo


        // Update the payment record as paid
        $stmt = $pdo->prepare("UPDATE club_payments SET paid = 1, payer_email = ?, paid_at = NOW() WHERE custom_id = ?");
        //$result = $stmt->execute([$payer_email, $custom_id]);
        $result = $stmt->execute(["xxx", "club_XX0001_682cb9247e55f5.68123293"]);
        
        if ($DEBUG) {
            file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG]  "  . PHP_EOL, FILE_APPEND);
        }


        if (!$result) {
            file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] PDO error: " . print_r($stmt->errorInfo(), true) . PHP_EOL, FILE_APPEND);
        }
        
        if ($DEBUG) {
            file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] DB update result: " . ($result ? "success" : "fail") . PHP_EOL, FILE_APPEND);
        }
    } else {
        if ($DEBUG) {
            file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] No custom_id found, skipping DB update." . PHP_EOL, FILE_APPEND);
        }
    }
} else {
    if ($DEBUG) {
        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Event not processed (not PAYMENT.CAPTURE.COMPLETED)" . PHP_EOL, FILE_APPEND);
    }
}

// Always respond with 200 OK so PayPal knows you received the webhook
http_response_code(200);
echo "OK";
?>