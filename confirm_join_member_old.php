<?php
session_start();
$title = "確認報名";
include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

$DEBUG=false;


//Number of Columns
$column = 5;

$item = array("個人會員年費",30,0);

if ($DEBUG){
    var_dump($item);
}

$name = 'SELECT name, name_chi FROM club WHERE username = "'.$username.'"';
$name2 = mysql_query($name) or die('Error! ' . mysql_error());
$name3 = mysql_fetch_array($name2);
//testing code

if ($DEBUG){
    echo "Event Fee:".$_SESSION['fee'];
    echo "Member Fee:".$_SESSION['memberfee'];
    echo " Pay:".$_SESSION['pay'];
    echo " Store:".$_SESSION['store'];
    echo " Special fee:".$item[0]."=".$item[1];
    echo "<br>";
}
echo '</a></h3><div><p>';
?>
<?
	// putting the values to be inserted after payment in a temp_ipn database, assigning a random id to the values
	for ($b = 1; $b < $_SESSION['store']; $b++)
	{
		$insert = 'INSERT INTO '.$_SESSION['category'].'(competition, code, country, name, name_chi, gender, division, weight, identity, date, payment) VALUES ('.$_SESSION["insert".$b].',"paid")';
		$ran[$b] = rand(1,999999);
		$in = 'REPLACE INTO temp_ipn VALUES ('.$ran[$b].',\''.$insert.'\')';
		$in2 = mysql_query($in) or die('Error! ' . mysql_error());
	}
	// passing the random id through the "custom" variable in paypal
	for ($i = 1; $i < sizeof($ran) + 1;$i++)
	{
		$custom = $custom.$ran[$i];
		if(sizeof($ran) > $i)
		{
			$custom = $custom."_";
		}
	}
//echo $custom;
?>

<div class = "row row-block">
	<div class = "row text-center">
		<?php echo $_SESSION['competition_chi'];?><br><?php echo $_SESSION['competition_eng'];?><br>
	</div>
	<div class = "row mt2">
		<?php
		//displaying all players - testing purpose
		if ($DEBUG) {
		    for ($a = 1; $a < $_SESSION['store']; $a++)
    		{
	    		echo $_SESSION['player'.$a].":".$_SESSION['name'.$a].":".$_SESSION['group'.$a].":".$_SESSION['weight'.$a].":".$_SESSION['active_member'.$a];
		    	echo "<br>";
		    }
		}
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
			<div class = "col-md-1 col-md-offset-2"></div>
			<div class = "col-md-3">身份<br>Identity</div>
			<div class = "col-md-4">參加者<br>Participants</div>';
		}else{
			echo '
			<div class = "col-md-1 col-md-offset-1"></div>
			<div class = "col-md-2">性別<br>Gender</div>
			<div class = "col-md-2">組別<br>Division</div>
			<div class = "col-md-2">體重級別<br>Weight Category</div>
			<div class = "col-md-3">參賽者<br>Participants</div>
			<div class = "col-md-1">現任個人會員 <br>Current Individual Member</div>';
		}?>
	</div>

	<?php
	for ($a = 1; $a < $_SESSION['store']; $a++)
	{
		echo '
		<div class= "row mt1">
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
			<div class = "col-md-3">'.$_SESSION["position".$a].'</div>
			<div class = "col-md-4">'.$_SESSION["player".$a].'</div>';
		}else{
			echo '
			<div class = "col-md-2">'.$_SESSION["gender".$a].'</div>
			<div class = "col-md-2">'.$_SESSION["group".$a].'</div>
			<div class = "col-md-2">'.$_SESSION["weight".$a].'</div>
			<div class = "col-md-3">'.$_SESSION["player".$a].'</div>
			<div class = "col-md-1">'.$_SESSION["active_member".$a].'</div>';
			
			if ($_SESSION["active_member".$a]!='Y'){
			    $item[2]++;
			}
		}
	echo '</div>';
	}
	?>
	<div class = "row text-center mt2">
	<? if ($_SESSION['ref'])
	{
	echo '
	<form method="POST" action="'.$_SERVER['PHP_SELF'].'" method="post">
		<input type="submit" name="submit" value="確認 Confirm">
	</form>
<form>
		<input type="button" name="return" value="重新填寫 Back" onclick="history.back()">
	</form>';
	
	}else{
	echo '
	一經確認，本會將處理閣下之報名, 取消參加的退款將會被收取行政費每位港幣10元。<br>年齡未滿十八歲者已獲家長或監護人同意<br>
	Once confirmed and submitted, we will proceed with your application.Any subsequent cancellation will be subjected HKD 10 handling fee per player <br>
	Parent/Guardian has acknowledged and agreed to permit their players who are under the age of 18 to participate in this competition.  
	<br><br>
	<form action="'.$paypal_url.'" method="post">
		<input type="hidden" name="cmd" value="_cart">
		<input type="hidden" name="charset" value="utf-8" >
		<input type="hidden" name="business" value="'.$ac.'">
		<input type="hidden" name="rm" value="2">
		<input type="hidden" name="custom" value="'.$custom.'">
		<input type="hidden" name="return" value="http://www.judoregistration.org/thankyou_payment.php" >
		<input type="hidden" name="notify_url" value="http://www.judoregistration.org/confirm_payment.php" >
		<input type="hidden" name="upload" value="1">';
		
		
		//calculating number of items needed to send to paypal
		   echo '<input type="hidden" name="item_name_1" value="'.$_SESSION["item_name"]."/".$name3['name']."/".$name3['name_chi'].'">';
     		echo '<input type="hidden" name="item_number_1" value="'.$_SESSION["item_name"]."/".$name3['name']."/".$name3['name_chi'].'">';
			echo '<input type="hidden" name="amount_1" value="'.$_SESSION["fee"].'">';
			echo '<input type="hidden" name="quantity_1" value="'.$_SESSION["pay"].'">';
		
		if($item[2] > 0)
        {
		
		    echo '<input type="hidden" name="item_name_2" value="'.$item[0].'">';
			echo '<input type="hidden" name="item_number_2" value="'.$item[0].'">';
            echo '<input type="hidden" name="amount_2" value="'.$item[1].'">';
			echo '<input type="hidden" name="quantity_2" value="'.$item[2].'">';
		
        }
				
		echo '
		<input type="hidden" name="currency_code" value="HKD">
		<input type="image" src="https://www.paypalobjects.com/zh_HK/HK/i/btn/btn_paynowCC_LG.gif" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!">
	</form>
	<!--form method="POST" action="'.$_SERVER['PHP_SELF'].'" method="post">
		<input type="submit" name="submit" value="確認並自行付款 Confirm and Pay Later">
	</form-->
	
	<form>
		<input type="button" name="return" value="重新填寫 Back" onclick="history.back()">
	</form>';
	}
	echo '</div>';
	if(isset($_POST['submit']))
	{
	for ($b = 1; $b < $_SESSION['store']; $b++)
	{
		$confirm = 'INSERT INTO '.$_SESSION['category'].'(competition, code, country, name, name_chi, gender, division, weight, identity, date, payment) VALUES ('.$_SESSION["insert".$b].',"")';
		if ($DEBUG){echo $confirm;}
		$confirm2 = mysql_query($confirm) or die('Error! ' . mysql_error());
	}
	echo '<meta http-equiv=REFRESH CONTENT=1;url=/front.php>';
}
?>

	</p>
</div>

<? include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>
