<?php
session_start();
$title = "主頁 Home";
include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");
include_once($_SERVER['DOCUMENT_ROOT']."/common/count.php");

try {

  // Club query
    $clubquery = "SELECT name, name_chi, type, Ref_id FROM club WHERE username = ?";
    $stmt = $pdo->prepare($clubquery);
    $stmt->execute([$username]);
    $clubrow = $stmt->fetch(PDO::FETCH_ASSOC);

    // Temp IPN database insertions
    $ran = []; // Initialize as empty array
    for ($b = 1; $b < $_SESSION['store']; $b++) {
      $insert = "INSERT INTO " . $_SESSION['category'] . " VALUES (" . $_SESSION["insert".$b] . ",'paid')";
      $ran[$b] = rand(1,999999);
   
       // Replace into temp_ipn
        $in = "REPLACE INTO temp_ipn VALUES (?, ?)";
        $stmt = $pdo->prepare($in);
        $stmt->execute([$ran[$b], $insert]);
    }

    // Building custom string
    $custom = implode("_", array_slice($ran, 1));


?>

<div class="row row-block">
    <h4>

        
        
        <?php echo "會員號碼 Membership No.: " . $clubrow['Ref_id']; ?><br><br>
        
        <?php echo '
        <form action="' . $paypal_url . '" method="post">
            <input type="hidden" name="cmd" value="_cart">
            <input type="hidden" name="charset" value="utf-8">
            <input type="hidden" name="business" value="' . $ac . '">
            <input type="hidden" name="rm" value="2">
            <input type="hidden" name="custom" value="' . $custom . '">
            <input type="hidden" name="return" value="http://www.judoregistration.org/thankyou_payment.php">
            <input type="hidden" name="notify_url" value="http://www.judoregistration.org/confirm_payment.php">
            <input type="hidden" name="upload" value="1">
            <input type="hidden" name="item_name_1" value="會費 Membership Fees [' . $clubrow['Ref_id'] . '/' . $clubrow['name'] . '/' . $clubrow['name_chi'] . ']">
            <input type="hidden" name="item_number_1" value="會費 Membership Fees [' . $clubrow['Ref_id'] . '/' . $clubrow['name'] . '/' . $clubrow['name_chi'] . ']">';
        
        $fee = ($clubrow['type'] == "renew_observation") ? 500 : 800;
        
        echo '
            <input type="hidden" name="amount_1" value="' . $fee . '">
            <input type="hidden" name="quantity_1" value="1">
            <input type="hidden" name="currency_code" value="HKD">如果您尚未繳交本年度團體會員會費，請使用以下付款鏈結 Annual Club Membership Fees Payment (If not paid for this year): <br>
            <input type="image" src="https://www.paypalobjects.com/zh_HK/HK/i/btn/btn_paynowCC_LG.gif" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!">
        </form>'; ?>
    </h4>
</div>

<div class="row row-block">
    <h3>可報名比賽 Available Competition</h3>
    <div class="row">
        <?php
        // Competition query
            $comp = "SELECT * FROM competition WHERE type = ?";
            $stmt = $pdo->prepare($comp);
            $stmt->execute([$_SESSION['category']]);
            
            while ($comp3 = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $deadline = floor(strtotime($comp3['deadline'])/(60*60*24) - time()/(60*60*24)) + 1;
                if ($deadline > -1) {
                    ?>
                    <h4><a href='join.php'><?= $comp3['name'] ?><br><?= $comp3['name_eng'] ?></a></h4>
                    <?php
                }
            }
            ?>
    </div>
</div>

<?php 

} catch(PDOException $e) {
    die('Error: ' . $e->getMessage());
}

include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php");
?>