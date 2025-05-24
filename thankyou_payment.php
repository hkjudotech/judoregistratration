<?php
session_start();
$title = "Payment Confirmation";
include_once($_SERVER['DOCUMENT_ROOT'] . "/common/header.php");

$DEBUG = false;

// Get the PayPal order ID from the URL parameter
$orderID = $_GET['orderID'] ?? null;

// Calculate total amount paid
$event_total = $_SESSION['fee'] * $_SESSION['pay'];
$membership_total = $_SESSION['total_membership_fee'];
$grand_total = $event_total + $membership_total;

if ($DEBUG) {
    file_put_contents(__DIR__ . '/payment_debug.log', date('c') . " [DEBUG] Thank you page accessed with orderID: " . $orderID . PHP_EOL, FILE_APPEND);
}

?>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-check-circle"></i>
                        付款成功 Payment Successful
                    </h3>
                </div>
                <div class="panel-body">
                    <?php if ($orderID): ?>
                        <div class="alert alert-success">
                            <h4>感謝您的付款！Thank you for your payment!</h4>
                            <p>您的付款已成功處理。Your payment has been processed successfully.</p>
                            <p><strong>交易編號 Transaction ID:</strong> <?php echo htmlspecialchars($orderID); ?></p>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h5>比賽資訊 Competition Information:</h5>
                                <p><strong><?php echo $_SESSION['competition_chi'] ?? 'N/A'; ?></strong></p>
                                <p><?php echo $_SESSION['competition_eng'] ?? 'N/A'; ?></p>
                            </div>
                            <div class="col-md-6">
                                <h5>付款詳情 Payment Details:</h5>
                                <p><strong>參賽費 Event Fee:</strong> HKD <?php echo $event_total; ?> (<?php echo $_SESSION['pay']; ?> participants)</p>
                                <p><strong>會員費 Membership Fee:</strong> HKD <?php echo $membership_total; ?> (<?php echo $_SESSION['membership_fee_count']; ?> new members)</p>
                                <hr>
                                <p><strong>總金額 Total Amount Paid:</strong> HKD <?php echo $grand_total; ?></p>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h5>下一步 Next Steps:</h5>
                            <ul>
                                <li>您將在24小時內收到確認電郵 You will receive a confirmation email within 24 hours</li>
                                <li>請保留此交易編號作記錄 Please keep this transaction ID for your records</li>
                                <li>如有任何問題，請聯絡我們 If you have any questions, please contact us</li>
                            </ul>
                        </div>

                        <?php
                        // Optional: Verify payment status with PayPal
                        if ($DEBUG) {
                            echo '<div class="alert alert-warning">';
                            echo '<h5>Debug Information:</h5>';
                            echo '<p>Order ID: ' . htmlspecialchars($orderID) . '</p>';
                            echo '<p>Session Data: ' . print_r($_SESSION, true) . '</p>';
                            echo '</div>';
                        }
                        ?>

                    <?php else: ?>
                        <div class="alert alert-warning">
                            <h4>付款狀態未知 Payment Status Unknown</h4>
                            <p>我們無法確認您的付款狀態。請聯絡我們以獲得協助。</p>
                            <p>We cannot confirm your payment status. Please contact us for assistance.</p>
                        </div>
                    <?php endif; ?>

                    <div class="text-center mt-4">
                        <a href="/front.php" class="btn btn-primary">
                            <i class="fa fa-home"></i> 返回主頁 Return to Home
                        </a>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Optional: Clean up session data after successful payment
if ($orderID) {
    // You might want to keep some session data for record keeping
    // or clear it completely depending on your application flow

    if ($DEBUG) {
        file_put_contents(__DIR__ . '/payment_debug.log', date('c') . " [DEBUG] Payment confirmation completed for order: " . $orderID . PHP_EOL, FILE_APPEND);
    }

    // Optionally clear sensitive session data
    unset($_SESSION['fee'], $_SESSION['pay'], $_SESSION['store'], $_SESSION['total_membership_fee'], $_SESSION['membership_fee_count']);
}

include_once($_SERVER['DOCUMENT_ROOT'] . "/common/footer.php");
?>

<style>
    .panel-success {
        border-color: #d6e9c6;
    }

    .panel-success>.panel-heading {
        background-color: #dff0d8;
        border-color: #d6e9c6;
        color: #3c763d;
    }

    .mt-4 {
        margin-top: 2rem;
    }

    .fa {
        margin-right: 5px;
    }
</style>