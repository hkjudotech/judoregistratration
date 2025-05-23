<?php
// paypal_webhook.php
require_once 'config.php';

$DEBUG = DEBUG_MODE;

// Read the raw POST body from PayPal
$body = file_get_contents('php://input');
$event = json_decode($body, true);

// Get PayPal signature headers
$paypalSignature = $_SERVER['HTTP_PAYPAL_TRANSMISSION_SIG'] ?? '';
$paypalCertUrl = $_SERVER['HTTP_PAYPAL_CERT_URL'] ?? '';
$paypalTransmissionId = $_SERVER['HTTP_PAYPAL_TRANSMISSION_ID'] ?? '';
$paypalTransmissionTime = $_SERVER['HTTP_PAYPAL_TRANSMISSION_TIME'] ?? '';

// Debug logging
if ($DEBUG) {
    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Raw body: " . $body . PHP_EOL, FILE_APPEND);
    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Decoded event: " . print_r($event, true) . PHP_EOL, FILE_APPEND);
    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Signature headers: " .
        "Sig: $paypalSignature, Cert: $paypalCertUrl, ID: $paypalTransmissionId, Time: $paypalTransmissionTime" . PHP_EOL, FILE_APPEND);
}

// Verify the PayPal webhook signature
/*
if (!verifyPayPalWebhookSignature($body, $paypalSignature, $paypalCertUrl, $paypalTransmissionId, $paypalTransmissionTime)) {
    if ($DEBUG) {
        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [ERROR] PayPal signature verification failed" . PHP_EOL, FILE_APPEND);
    }
    http_response_code(403);
    echo "Signature verification failed";
    exit;
}

if ($DEBUG) {
    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] PayPal signature verified successfully" . PHP_EOL, FILE_APPEND);
}
*/

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

    //check if it is a club payment or event payment
    // Determine if this is a club payment or event payment based on custom_id
    $isClubPayment = strpos($custom_id, 'club_') === 0;
    $isEventPayment = strpos($custom_id, 'event_') === 0;

    if ($DEBUG) {
        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Payment type: " .
            ($isClubPayment ? "Club Payment" : ($isEventPayment ? "Event Payment" : "Unknown")) . PHP_EOL, FILE_APPEND);
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
        } catch (PDOException $e) {
            if ($DEBUG) {
                file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [ERROR] Database connection failed: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            }
            http_response_code(500);
            echo "Database error";
            exit;
        }

        if ($DEBUG) {
            file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] PDO obtained. Executing Update SQL for club_payments.... "  . PHP_EOL, FILE_APPEND);
        }

        try {
            // Begin transaction
            $pdo->beginTransaction();

            if ($isClubPayment) {

                // Process club payment
                if ($DEBUG) {
                    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Processing club payment. Executing Update SQL for club_payments..." . PHP_EOL, FILE_APPEND);
                }
            // Update the payment record as paid
            $stmt = $pdo->prepare("UPDATE club_payments SET paid = 1, payer_email = ?, paid_at = NOW() WHERE custom_id = ?");
            $result = $stmt->execute([$payer_email, $custom_id]);

            if (!$result) {
                throw new Exception("Club Database update failed: " . print_r($stmt->errorInfo(), true));
            }
            } elseif ($isEventPayment) {
                // Process event payment
                if ($DEBUG) {
                    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Processing event payment. Executing Update SQL for temp_ipn..." . PHP_EOL, FILE_APPEND);
                }
                // Update the payment record as paid
                $stmt = $pdo->prepare("UPDATE temp_ipn SET payment = 'paid' WHERE custom_id = ?");
                $result = $stmt->execute([$custom_id]);

                if (!$result) {
                    throw new Exception("event Database update failed: " . print_r($stmt->errorInfo(), true));
                }
            } else {
                if ($DEBUG) {
                    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [ERROR] Unknown payment type" . PHP_EOL, FILE_APPEND);
                }
            }
            // Commit transaction
            $pdo->commit();

            if ($DEBUG) {
                file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] DB update result: success" . PHP_EOL, FILE_APPEND);
            }
        } catch (Exception $e) {
            // Rollback transaction on error
            $pdo->rollBack();

            if ($DEBUG) {
                file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [ERROR] Transaction failed: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            }

            http_response_code(500);
            echo "Database update error";
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

/**
 * Verify PayPal webhook signature
 * 
 * @param string $requestBody The raw request body
 * @param string $transmissionSig The PayPal-Transmission-Sig header
 * @param string $certUrl The PayPal-Cert-Url header
 * @param string $transmissionId The PayPal-Transmission-Id header
 * @param string $transmissionTime The PayPal-Transmission-Time header
 * @return bool True if signature is valid, false otherwise
 */
function verifyPayPalWebhookSignature($requestBody, $transmissionSig, $certUrl, $transmissionId, $transmissionTime)
{
    global $DEBUG;

    // Validate required parameters
    if (
        empty($requestBody) || empty($transmissionSig) || empty($certUrl) ||
        empty($transmissionId) || empty($transmissionTime) || empty(PAYPAL_WEBHOOK_ID)
    ) {
        if ($DEBUG) {
            file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [ERROR] Missing required signature parameters" . PHP_EOL, FILE_APPEND);
        }
        return false;
    }

    // Validate cert URL (only accept PayPal domains)
    if (
        !preg_match('/^https:\/\/api\.paypal\.com\//', $certUrl) &&
        !preg_match('/^https:\/\/api\.sandbox\.paypal\.com\//', $certUrl)
    ) {
        if ($DEBUG) {
            file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [ERROR] Invalid certificate URL" . PHP_EOL, FILE_APPEND);
        }
        return false;
    }

    // Download PayPal's public certificate
    $publicCertificate = file_get_contents($certUrl);
    if (!$publicCertificate) {
        if ($DEBUG) {
            file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [ERROR] Failed to download PayPal certificate" . PHP_EOL, FILE_APPEND);
        }
        return false;
    }

    // Extract the public key from the certificate
    $publicKey = openssl_pkey_get_public($publicCertificate);
    if (!$publicKey) {
        if ($DEBUG) {
            file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [ERROR] Failed to extract public key from certificate" . PHP_EOL, FILE_APPEND);
        }
        return false;
    }

    // Create the expected signature value
    $expectedSignature = $transmissionId . '|' . $transmissionTime . '|' . PAYPAL_WEBHOOK_ID . '|' . crc32($requestBody);

    // Decode the actual signature from base64
    $decodedSignature = base64_decode($transmissionSig);

    // Verify the signature
    $signatureVerified = openssl_verify($expectedSignature, $decodedSignature, $publicKey, OPENSSL_ALGO_SHA256);

    // Free the key resource
    openssl_free_key($publicKey);

    if ($signatureVerified === 1) {
        return true;
    } else {
        if ($DEBUG) {
            file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [ERROR] Signature verification failed. OpenSSL result: $signatureVerified" . PHP_EOL, FILE_APPEND);
        }
        return false;
    }
}
