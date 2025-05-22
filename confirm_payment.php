<?php
session_start();
$title = "交易處理中";




include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

try {
    // Assuming you have PDO connection established as $pdo
    $stmt = $pdo->prepare("SELECT name, name_chi FROM club WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $name3 = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $name3['name_chi'];
    echo " ";
    echo $name3['name'];
    echo '</a></h3><div><p>';
} catch(PDOException $e) {
    die('Error! ' . $e->getMessage());
}


// CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
// Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
// Set this to 0 once you go live or don't require logging.
define("DEBUG", 1);
$DEBUG=true;
// Set to 0 once you're ready to go live
define("LOG_FILE", "./ipn.log");
// Read POST data
// reading posted data directly from $_POST causes serialization
// issues with array data in POST. Reading raw POST data from input stream instead.
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
	$keyval = explode ('=', $keyval);
	if (count($keyval) == 2)
		$myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
	$get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
	if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
		$value = urlencode(stripslashes($value));
	} else {
		$value = urlencode($value);
	}
	$req .= "&$key=$value";
}
// Post IPN data back to PayPal to validate the IPN data is genuine
// Without this step anyone can fake IPN data
$ch = curl_init($paypal_url);
if ($ch == FALSE) {
	return FALSE;
}
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
if(DEBUG == true) {
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
}
// CONFIG: Optional proxy configuration
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
// Set TCP timeout to 30 seconds
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
// of the certificate as shown below. Ensure the file is readable by the webserver.
// This is mandatory for some environments.
//$cert = __DIR__ . "./cacert.pem";
//curl_setopt($ch, CURLOPT_CAINFO, $cert);
$res = curl_exec($ch);
if (curl_errno($ch) != 0) // cURL error
	{
	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
		echo "Confirm_payment: can't connect to Paypal to validate IPN message";
	}
	curl_close($ch);
	exit;
} else {
		// Log the entire HTTP response if debug is switched on.
		if(DEBUG == true) {
			error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
			error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
			echo "Confirm_payment: HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT)." for IPN payload: ".$req;
			echo "Confirm_payment: HTTP response of validation request:".$req;
		}
		curl_close($ch);
}
// Inspect IPN validation result and act accordingly
// Split response headers and payload, a better way for strcmp
$tokens = explode("\r\n\r\n", trim($res));
$res = trim(end($tokens));
echo $res;
echo "<br>";


if (strcmp ($res, "VERIFIED") == 0) {
    try {
        $num = $_POST['num_cart_items'];
        $total = $_POST['mc_gross'];
        $payer_email = $_POST['payer_email'];
        $custom = $_POST['custom'];
        $split = explode("_",$custom);
        
        for($i = 0; $i < sizeof($split); $i++) {
            // Select id from temp_ipn
            $stmt = $pdo->prepare("SELECT text FROM temp_ipn WHERE id = :id");
            $stmt->execute(['id' => $split[$i]]);
            $get_id = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Execute the stored query
            $stmt = $pdo->prepare($get_id['text']);
            $stmt->execute();
            
            // Delete the used id
            $stmt = $pdo->prepare("DELETE FROM temp_ipn WHERE id = :id");
            $stmt->execute(['id' => $split[$i]]);
        }
        
        // Insert information into payment database
        $stmt = $pdo->prepare("INSERT INTO payment (payer_email, num_items, total, payment_date) VALUES (:email, :num, :total, :date)");
        $stmt->execute([
            'email' => $payer_email,
            'num' => $num,
            'total' => $total,
            'date' => date("F j, Y, g:i a")
        ]);
        
        if(DEBUG == true) {
            error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
        }
    } catch(PDOException $e) {
        if(DEBUG == true) {
            error_log(date('[Y-m-d H:i e] '). "Database Error: " . $e->getMessage() . PHP_EOL, 3, LOG_FILE);
        }
        die('Error! ' . $e->getMessage());
    }
} else if (strcmp ($res, "INVALID") == 0) {
    echo '<meta http-equiv=REFRESH CONTENT=1;url=front.php>';
    
    if(DEBUG == true) {
        error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
    }
}
include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>
