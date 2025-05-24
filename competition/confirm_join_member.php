<?php
session_start();
$title = "確認報名";
include_once($_SERVER['DOCUMENT_ROOT'] . "/common/header.php");

$DEBUG = false;

//Number of Columns
$column = 5;

$item = array("個人會員年費", 30, 0);

if ($DEBUG) {
	var_dump($item);
	echo "Event Fee:" . $_SESSION['fee'];
	echo "Member Fee:" . $_SESSION['memberfee'];
	echo " Pay:" . $_SESSION['pay'];
	echo " Store:" . $_SESSION['store'];
	echo " Special fee:" . $item[0] . "=" . $item[1];
	echo "<br>";
}

try {
	// Get club name
	$stmt = $pdo->prepare('SELECT name, name_chi FROM club WHERE username = ?');
	$stmt->execute([$username]);
	$name3 = $stmt->fetch();

	echo '</a></h3><div><p>';

	// Generate unique custom_id for this registration batch using uniqid
	$custom_id = 'event_' . uniqid();

	// Calculate total amount and counts
	$eventFeeTotal = $_SESSION["fee"] * $_SESSION["pay"];
	$membershipFeeTotal = $item[1] * $item[2];
	$totalAmount = $eventFeeTotal + $membershipFeeTotal;
	$participantCount = $_SESSION["pay"];
	$membershipFeeCount = $item[2];

	if ($DEBUG) {
		echo "Custom ID: " . $custom_id . "<br>";
		echo "Total Amount: " . $totalAmount . "<br>";
	}

?>

<!-- Add PayPal SDK using variable from header -->
<script src="<?php echo $paypal_sdk_url; ?>"></script>

<div class="row row-block">
	<div class="row text-center">
		<?php echo $_SESSION['competition_chi']; ?><br><?php echo $_SESSION['competition_eng']; ?><br>
	</div>

	<div class="row mt2">
		<?php
		//displaying all players - testing purpose
		if ($DEBUG) {
			for ($a = 1; $a < $_SESSION['store']; $a++) {
				echo $_SESSION['player' . $a] . ":" . $_SESSION['name' . $a] . ":" . $_SESSION['group' . $a] . ":" . $_SESSION['weight' . $a] . ":" . $_SESSION['active_member' . $a];
				echo "<br>";
			}
		}

		if ($_SESSION['ref']) {
			echo '
			<div class = "col-md-1 col-md-offset-2"></div>
			<div class = "col-md-3">身份<br>Identity</div>
			<div class = "col-md-4">參加者<br>Participants</div>';
		} else {
			echo '
			<div class = "col-md-1 col-md-offset-1"></div>
			<div class = "col-md-2">性別<br>Gender</div>
			<div class = "col-md-2">組別<br>Division</div>
			<div class = "col-md-2">體重級別<br>Weight Category</div>
			<div class = "col-md-3">參賽者<br>Participants</div>
			<div class = "col-md-1">現任個人會員 <br>Current Individual Member</div>';
		} ?>
	</div>

	<?php
	for ($a = 1; $a < $_SESSION['store']; $a++) {
		echo '
		<div class= "row mt1">
			<div class = "col-md-1 col-md-offset-1">' . $a . '</div>';

		if ($_SESSION['ref']) {
			echo '
				<div class = "col-md-3">' . $_SESSION["position" . $a] . '</div>
				<div class = "col-md-4">' . $_SESSION["player" . $a] . '</div>';
		} else {
			echo '
			<div class = "col-md-2">' . $_SESSION["gender" . $a] . '</div>
			<div class = "col-md-2">' . $_SESSION["group" . $a] . '</div>
			<div class = "col-md-2">' . $_SESSION["weight" . $a] . '</div>
			<div class = "col-md-3">' . $_SESSION["player" . $a] . '</div>
			<div class = "col-md-1">' . $_SESSION["active_member" . $a] . '</div>';

			if ($_SESSION["active_member" . $a] != 'Y') {
				$item[2]++;
			}
		}
		echo '</div>';
	}
	?>

	<div class="row text-center mt2">
		<?php if ($_SESSION['ref']) {
			// Referee registration - no payment required, insert immediately
			echo '
			<form method="POST" action="' . $_SERVER['PHP_SELF'] . '" method="post">
				<input type="submit" name="submit" value="確認 Confirm">
			</form>
			<form>
				<input type="button" name="return" value="重新填寫 Back" onclick="history.back()">
			</form>';
		} else {
			// Regular player registration
			echo '
			一經確認，本會將處理閣下之報名, 取消參加的退款將會被收取行政費每位港幣10元。<br>年齡未滿十八歲者已獲家長或監護人同意<br>
			Once confirmed and submitted, we will proceed with your application. Any subsequent cancellation will be subjected HKD 10 handling fee per player <br>
			Parent/Guardian has acknowledged and agreed to permit their players who are under the age of 18 to participate in this competition.  
			<br><br>';

			echo '
			<div class="alert alert-info">
				<strong>付款須知 Payment Notice:</strong><br>
				報名必須即時付款才能完成。Registration must be completed with immediate payment.
			</div>';

			echo '
			<!-- PayPal Button Container -->
			<div id="paypal-button-container"></div>
			<br>';

			/* 
			// EMERGENCY: Pay Later Option (uncomment during emergencies only)
			echo '
			<form method="POST" action="' . $_SERVER['PHP_SELF'] . '" method="post">
				<input type="submit" name="submit" value="確認並稍後付款 Confirm and Pay Later">
			</form>
			<br>';
			*/

			echo '
			<form>
				<input type="button" name="return" value="重新填寫 Back" onclick="history.back()">
			</form>';
		}
		echo '</div>';

		// Handle form submissions (only for referees and emergency pay later)
		if (isset($_POST['submit'])) {
			try {
				// Begin transaction
				$pdo->beginTransaction();
				
				// Insert all registrations directly to the local table
				for ($b = 1; $b < $_SESSION['store']; $b++) {
					$confirm = 'INSERT INTO local ' . 
						'(competition, code, country, name, name_chi, gender, division, weight, identity, date, payment, custom_id) VALUES (' . 
						$_SESSION["insert" . $b] . ', ?, ?)';
					
					if ($_SESSION['ref']) {
						// Referee registration - no payment required
						$payment_status = 'confirmed';
						$registration_custom_id = null; // No custom_id needed for referees
					} else {
						// Emergency pay later option
						$payment_status = 'pending';
						$registration_custom_id = $custom_id;
					}
					
					if ($DEBUG) {
						echo "Executing: " . $confirm . " with payment: " . $payment_status . " and custom_id: " . $registration_custom_id . "<br>";
					}
					
					$stmt = $pdo->prepare($confirm);
					$stmt->execute([$payment_status, $registration_custom_id]);
				}
				
				// Commit transaction
				$pdo->commit();
				
				if ($DEBUG) {
					echo "All registrations inserted successfully";
				}
				
				echo '<meta http-equiv=REFRESH CONTENT=1;url=/front.php>';
				
			} catch (PDOException $e) {
				// Rollback transaction on error
				$pdo->rollBack();
				
				if ($DEBUG) {
					die('Error inserting registrations: ' . $e->getMessage());
				} else {
					die('Registration error occurred. Please try again later.');
				}
			}
		}
		
	} catch (PDOException $e) {
		if ($DEBUG) {
			die('Error: ' . $e->getMessage());
		} else {
			die('Database error occurred. Please try again later.');
		}
	}
?>

<script>
paypal.Buttons({
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: '<?php echo number_format($totalAmount, 2, '.', ''); ?>',
                    currency_code: 'HKD'
                },
                custom_id: '<?php echo $custom_id; ?>',
                description: '<?php echo $_SESSION["item_name"] . "/" . $name3['name'] . "/" . $name3['name_chi']; ?>'
            }]
        });
    },
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            // Payment successful - webhook will handle the database updates
            alert('Payment completed successfully! Your registration will be processed shortly.');
            
            // Redirect to success page
            window.location.href = '/thankyou_payment.php?orderID=' + data.orderID + '&custom_id=<?php echo $custom_id; ?>';
        });
    },
    onError: function(err) {
        console.error('PayPal Error:', err);
        alert('An error occurred during payment. Please try again.');
    },
    onCancel: function(data) {
        alert('Payment was cancelled. Registration cannot be completed without payment.');
    }
}).render('#paypal-button-container');
</script>

</p>
</div>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/common/footer.php"); ?>
