<?php
session_start();
$title = "確認報名";
include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

$DEBUG=false; 
//Number of Columns
$column = 5;



try {
    // Get club name
    $stmt = $pdo->prepare('SELECT name, name_chi FROM club WHERE username = ?');
    $stmt->execute([$username]);
    $name3 = $stmt->fetch();

    echo '</a></h3><div><p>';

    // Putting values to be inserted after payment in temp_ipn database
    for ($b = 1; $b < $_SESSION['store']; $b++) {
        $insert = 'INSERT INTO '.$_SESSION['category'].'(competition, code, country, name, name_chi, gender, division, weight, identity, date, payment) VALUES ('.$_SESSION["insert".$b].',"paid")';
        $ran[$b] = rand(1,999999);
        
        $stmt = $pdo->prepare('REPLACE INTO temp_ipn VALUES (?, ?)');
        $stmt->execute([$ran[$b], $insert]);
    }

    // Build custom string for PayPal
    $custom = '';
    for ($i = 1; $i < sizeof($ran) + 1; $i++) {
        $custom .= $ran[$i];
        if(sizeof($ran) > $i) {
            $custom .= "_";
        }
    }

    if(isset($_POST['submit'])) {
        for ($b = 1; $b < $_SESSION['store']; $b++) {
            $confirm = 'INSERT INTO '.$_SESSION['category'].'(competition, code, country, name, name_chi, gender, division, weight, identity, date, payment) VALUES ('.$_SESSION["insert".$b].',"")';
            if ($DEBUG) {
                echo $confirm;
            }
            $stmt = $pdo->prepare($confirm);
            $stmt->execute();
        }
      echo '<meta http-equiv=REFRESH CONTENT=1;url=/front.php>';
    }

} catch(PDOException $e) {
    if($DEBUG) {
        die('Error: ' . $e->getMessage());
    } else {
        die('Database error occurred. Please try again later.');
    }
}
?>

<div class = "row row-block">
	<div class = "row text-center">
		<?php echo $_SESSION['competition_chi'];?><br><?php echo $_SESSION['competition_eng'];?><br>
	</div>
	<div class = "row mt1">
		<?php
		//displaying all players - testing purpose
		/*for ($a = 1; $a < $_SESSION['store']; $a++)
		{
			echo $_SESSION['player'.$a];
			echo "<br>";
		}*/
		if($_SESSION['category'] == "international")
		{
			echo '
			<div class = "col-md-1 col-md-offset-1"></div>
			<div class = "col-md-2">Identity</div>
			<div class = "col-md-2">Division</div>
			<div class = "col-md-2">Weight Category</div>
			<div class = "col-md-3">Participant</div>';
		}else if ($_SESSION['ref']){
			echo '
			<div class = "col-md-1 col-md-offset-1"></div>
			<div class = "col-md-5">身份 Identity</div>
			<div class = "col-md-5">參加者 Participants</div>';
		}else{
			echo '
			<div class = "col-md-1 col-md-offset-1"></div>
			<div class = "col-md-2">性別 Gender</div>
			<div class = "col-md-2">組別 Division</div>
			<div class = "col-md-2">體重級別 Weight Category</div>
			<div class = "col-md-3">參賽者 Participants</div>';
		}?>
	</div>

	<?php
	for ($a = 1; $a < $_SESSION['store']; $a++)
	{
		echo '
		<div>
			<div class = "col-md-1 col-md-offset-1">'.$a.'</div>';
		if($_SESSION['category'] == "international")
		{
			echo '
			<div class = "col-md-2">'.$_SESSION["identity".$a].'</div>
			<div class = "col-md-2">'.$_SESSION["group".$a].'</div>
			<div class = "col-md-2">'.$_SESSION["weight".$a].'</div>
			<div class = "col-md-3">'.$_SESSION["player".$a].'</div>';
		}else if($_SESSION['ref']){
			echo '
			<div class = "col-md-5">'.$_SESSION["position".$a].'</div>
			<div class = "col-md-5">'.$_SESSION["player".$a].'</div>';
		}else{
			echo '
			<div class = "col-md-2">'.$_SESSION["gender".$a].'</div>
			<div class = "col-md-2">'.$_SESSION["group".$a].'</div>
			<div class = "col-md-2">'.$_SESSION["weight".$a].'</div>
			<div class = "col-md-3">'.$_SESSION["player".$a].'</div>';
		}
	echo '</div>';
	}
	?>
	<div class = "row text-center mt3">
	 <?php if ($_SESSION['ref'])
	 {
		echo '
		<form method="POST" action="'.$_SERVER['PHP_SELF'].'" method="post">
			<input type="submit" name="submit" value="確認 Confirm">
		</form>
		<form action="/join.php" method="post">
			<input type="submit" name="return" value="重新填寫 Back">
		</form>';
	 }else{
		echo '
		<p>閣下可選擇Paypal即時付款或從表格上的其他途徑付款，一經確認，本會將處理閣下之報名。<br><br>
		You may either pay by paypal or other method stated, after confirmation, we will proceed your application of the competition.<br>
		<br><br>
		<form action="'.$paypal_url.'" method="post">
			<input type="hidden" name="cmd" value="_cart">
			<input type="hidden" name="charset" value="utf-8" >
			<input type="hidden" name="business" value="'.$ac.'">
			<input type="hidden" name="rm" value="2">
			<input type="hidden" name="custom" value="'.$custom.'">
			<input type="hidden" name="return" value="http://www.judoregistration.org/thankyou_payment.php" >
			<input type="hidden" name="notify_url" value="http://www.judoregistration.org/confirm_payment.php" >
			<input type="hidden" name="upload" value="1">
			<input type="hidden" name="item_name_1" value="'.$_SESSION["item_name"].'">
			<input type="hidden" name="item_number_1" value="'.$_SESSION["item_name"].'">
			<input type="hidden" name="amount_1" value="'.$_SESSION["fee"].'">
			<input type="hidden" name="quantity_1" value="'.$_SESSION["pay"].'">
			<input type="hidden" name="currency_code" value="HKD">
			<input type="image" src="https://www.paypalobjects.com/zh_HK/HK/i/btn/btn_paynowCC_LG.gif" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!">
		</form>
		<!--form method="POST" action="'.$_SERVER['PHP_SELF'].'" method="post">
			<input type="submit" name="submit" value="確認並自行付款 Confirm and Pay Later">
		</form-->
		<form action="/join.php" method="post">
			<input type="submit" name="return" value="重新填寫 Back">
		</form>
	 </div>';
	 }


 ?>

   </p>
	</div>


  <?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>
