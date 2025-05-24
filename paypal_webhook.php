<?php
// paypal_webhook.php
// TODO: Implement additional webhook event types and improve error handling
// TODO: Refactor the code to store the database details in a separate config file

$config = include(__DIR__ . '/config.php');


$DEBUG = true; // Set to false to turn off debugging
// PayPal Production Configuration
$PAYPAL_WEBHOOK_ID = "YOUR_PRODUCTION_WEBHOOK_ID_HERE"; // Replace with your actual webhook ID
$PAYPAL_CLIENT_ID = "YOUR_PRODUCTION_CLIENT_ID_HERE";
$PAYPAL_CLIENT_SECRET = "YOUR_PRODUCTION_CLIENT_SECRET_HERE";
$PAYPAL_BASE_URL = "https://api-m.paypal.com"; // Production URL

// Read the raw POST body from PayPal
$body = file_get_contents('php://input');
$event = json_decode($body, true);

// Get headers for verification
$headers = getallheaders();
$auth_algo = $headers['PAYPAL-AUTH-ALGO'] ?? '';
$transmission_id = $headers['PAYPAL-TRANSMISSION-ID'] ?? '';
$cert_id = $headers['PAYPAL-CERT-ID'] ?? '';
$transmission_sig = $headers['PAYPAL-TRANSMISSION-SIG'] ?? '';
$transmission_time = $headers['PAYPAL-TRANSMISSION-TIME'] ?? '';


$dbhost = "localhost";
$dbname = "judonorg_judo";
$dbuser = "judonorg_reg";
$dbpass = "1024judo";

// Debug logging
if ($DEBUG) {
    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Raw body: " . $body . PHP_EOL, FILE_APPEND);
    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Decoded event: " . print_r($event, true) . PHP_EOL, FILE_APPEND);
    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Headers: " . print_r($headers, true) . PHP_EOL, FILE_APPEND);
}

// Function to get PayPal access token
function getPayPalAccessToken($client_id, $client_secret, $base_url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $base_url . '/v1/oauth2/token');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $client_id . ":" . $client_secret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Accept-Language: en_US'));

    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        return false;
    }

    $json = json_decode($result, true);
    return $json['access_token'] ?? false;
}

// Function to verify webhook signature
function verifyWebhookSignature($webhook_id, $access_token, $base_url, $headers, $body) {
    $verification_data = array(
        'auth_algo' => $headers['PAYPAL-AUTH-ALGO'] ?? '',
        'cert_id' => $headers['PAYPAL-CERT-ID'] ?? '',
        'transmission_id' => $headers['PAYPAL-TRANSMISSION-ID'] ?? '',
        'transmission_sig' => $headers['PAYPAL-TRANSMISSION-SIG'] ?? '',
        'transmission_time' => $headers['PAYPAL-TRANSMISSION-TIME'] ?? '',
        'webhook_id' => $webhook_id,
        'webhook_event' => json_decode($body, true)
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $base_url . '/v1/notifications/verify-webhook-signature');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($verification_data));

    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        return false;
    }

    $response = json_decode($result, true);
    return ($response['verification_status'] ?? '') === 'SUCCESS';
}

// Verify webhook authenticity for production
if (!$DEBUG) { // Only verify in production
    // Get access token
    $access_token = getPayPalAccessToken($PAYPAL_CLIENT_ID, $PAYPAL_CLIENT_SECRET, $PAYPAL_BASE_URL);
    
    if (!$access_token) {
        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [ERROR] Failed to get PayPal access token" . PHP_EOL, FILE_APPEND);
        http_response_code(401);
        echo "Authentication failed";
        exit;
    }

    // Verify webhook signature
    if (!verifyWebhookSignature($PAYPAL_WEBHOOK_ID, $access_token, $PAYPAL_BASE_URL, $headers, $body)) {
        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [ERROR] Webhook signature verification failed" . PHP_EOL, FILE_APPEND);
        http_response_code(401);
        echo "Verification failed";
        exit;
    }

    if ($DEBUG) {
        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Webhook signature verified successfully" . PHP_EOL, FILE_APPEND);
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////
// Only process payment completed events 'CHECKOUT.ORDER.APPROVED'
if (isset($event['event_type']) && $event['event_type'] === 'CHECKOUT.ORDER.APPROVED') {
    if ($DEBUG) {
        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] CHECKOUT.ORDER.APPROVED event detected" . PHP_EOL, FILE_APPEND);
    }

    $custom_id = $event['resource']['purchase_units'][0]['custom_id'] ?? null;
    $payer_email = $event['resource']['payer']['email_address'] ?? null;
    $amount = $event['resource']['purchase_units'][0]['amount']['value'] ?? null;
    $currency = $event['resource']['purchase_units'][0]['amount']['currency_code'] ?? 'HKD';
    $description = $event['resource']['purchase_units'][0]['description'] ?? '';

    if ($DEBUG) {
        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] custom_id: **" . $custom_id . "**, payer_email: " . $payer_email . ", amount: " . $amount . ", currency: " . $currency . PHP_EOL, FILE_APPEND);
    }

    if ($custom_id) {
        if ($DEBUG) {
            file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Custom ID found. Connecting to Database --------------------------> " . PHP_EOL, FILE_APPEND);
        }

        try {
            $pdo = new PDO(
                "mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4",
                $dbuser,
                $dbpass,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );
        } catch (PDOException $e) {
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
                // Event registration payment - execute stored SQL statements
                if ($DEBUG) {
                    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Processing event payment. Looking for temp_registrations..." . PHP_EOL, FILE_APPEND);
                }

                // Get all stored SQL statements for this custom_id
                $stmt = $pdo->prepare("SELECT insert_sql, participant_count, membership_fee_count FROM temp_registrations WHERE custom_id = ? ORDER BY id");
                $stmt->execute([$custom_id]);
                $registrations = $stmt->fetchAll();

                if (empty($registrations)) {
                    throw new Exception("No temp registrations found for custom_id: " . $custom_id);
                }

                // Get the counts from the first record (they should be the same for all records with same custom_id)
                $participant_count = $registrations[0]['participant_count'] ?? 0;
                $membership_fee_count = $registrations[0]['membership_fee_count'] ?? 0;

                if ($DEBUG) {
                    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Found " . count($registrations) . " registrations. Participants: " . $participant_count . ", Membership fees: " . $membership_fee_count . PHP_EOL, FILE_APPEND);
                }

                // Execute each stored SQL statement directly
                foreach ($registrations as $registration) {
                    $sql = $registration['insert_sql'];

                    if ($DEBUG) {
                        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Executing SQL: " . $sql . PHP_EOL, FILE_APPEND);
                    }

                    // Execute the stored SQL directly
                    $stmt = $pdo->prepare($sql);
                    $result = $stmt->execute();

                    if (!$result) {
                        throw new Exception("Failed to execute registration SQL: " . print_r($stmt->errorInfo(), true));
                    }

                    if ($DEBUG) {
                        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] SQL executed successfully" . PHP_EOL, FILE_APPEND);
                    }
                }

                // Parse description to extract competition and club info
                $descriptionParts = explode('/', $description);
                $competition = $descriptionParts[0] ?? '';
                $club_name = $descriptionParts[1] ?? '';
                $club_name_chi = $descriptionParts[2] ?? '';

                // Create event payment record 
                try {
                    $stmt = $pdo->prepare("INSERT INTO event_payment (custom_id, competition, club_name, club_name_chi, participant_count, membership_fee_count, total_amount, currency, paid, payer_email, paid_at, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?, NOW(), NOW())");
                    $result = $stmt->execute([$custom_id, $competition, $club_name, $club_name_chi, $participant_count, $membership_fee_count, $amount, $currency, $payer_email]);

                    if ($DEBUG) {
                        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Event payment record created successfully with " . $participant_count . " participants and " . $membership_fee_count . " membership fees" . PHP_EOL, FILE_APPEND);
                    }
                } catch (PDOException $e) {
                    // If event_payment table doesn't exist, just log and continue
                    if ($DEBUG) {
                        file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Event payment table not found or error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                    }
                }

                // Delete the temp registrations after successful execution
                $stmt = $pdo->prepare("DELETE FROM temp_registrations WHERE custom_id = ?");
                $stmt->execute([$custom_id]);

                if ($DEBUG) {
                    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Event registrations completed and temp records cleaned up" . PHP_EOL, FILE_APPEND);
                }
            } else {
                if ($DEBUG) {
                    file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] Unknown payment type for custom_id: " . $custom_id . PHP_EOL, FILE_APPEND);
                }
                throw new Exception("Unknown payment type for custom_id: " . $custom_id);
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
                file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [ERROR] Transaction failed: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            }

            http_response_code(500);
            echo "Payment processing error";
            exit;
        }
    } else {
        if ($DEBUG) {
            file_put_contents(__DIR__ . '/paypal_webhook.log', date('c') . " [DEBUG] No custom_id found, skipping payment processing." . PHP_EOL, FILE_APPEND);
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
