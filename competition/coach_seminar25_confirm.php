<?php
session_start();
$title = "確認報名";
include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

$DEBUG=false;


//can be improved by using array and get data from database

$item = array (array("課程報名費",850,$_SESSION['store']-1),
			   array("A級教練重新註冊年費",450,0),
			   array("B級教練重新註冊年費",350,0),
			   array("C級教練重新註冊年費",250,0),
			   //array("考試費",300,0),
			   array("個人會員年費",30,0),
			   array("郵件掛號費", 60, 0));

if ($DEBUG){
    var_dump($item);
}


if ($DEBUG){
 
    echo "Member Fee:".$_SESSION['memberfee'];
    echo " Pay:".$_SESSION['pay'];
    echo " Store:".$_SESSION['store'];
    echo " Special fee:".$item[0]."=".$item[1];
    echo "<br>";
}

?>

<script src="/js/shopping.js" type="text/javascript"></script>



<?php
try {
    // Get club name
    $stmt = $pdo->prepare('SELECT name, name_chi FROM club WHERE username = ?');
    $stmt->execute([$username]);
    $name3 = $stmt->fetch();

echo '</a></h3>';


// putting the values to be inserted after payment in a temp_ipn database, assigning a random id to the values

for ($b = 1; $b < $_SESSION['store']; $b++)

{
    $insert = 'INSERT INTO '.$_SESSION['category'].'(competition, code, country, name, name_chi, gender, division, weight, identity, date, payment) VALUES ('.$_SESSION["insert".$b].',"paid")';
	

	//$insert = 'INSERT INTO '.$_SESSION['category'].' VALUES ('.$_SESSION["insert".$b].',"paid")';

	$ran[$b] = rand(1,999999);

     $stmt = $pdo->prepare('REPLACE INTO temp_ipn VALUES (?, ?)');
     $stmt->execute([$ran[$b], $insert]);

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
} catch(PDOException $e) {
    if($DEBUG) {
        die('Error: ' . $e->getMessage());
    } else {
        die('Database error occurred. Please try again later.');
    }
}
?>

<div class = "row row-block">

	<div class = "row text-center mt1">

		<?php

		 echo $_SESSION['competition_chi'];

		 echo '<br>';

		 echo $_SESSION['competition_eng'];

		?>

	</div>

	<div class = "row text-center mt1">

		<?php

			echo '

			<div class = "col-md-1 col-md-offset-1"></div>
			<div class = "col-md-3">參加者<br>Participants</div>
			<div class = "col-md-2">考核級別<br>Registration Grade</div>
			<div class = "col-md-2">證書掛號<br>Registered Mail</div>
			<div class = "col-md-1">費用<br>Fee</div>
			<div class = "col-md-1">現任個人會員 <br>Current Individual Member</div>
		'

		?>

	</div>

	<?php

	for ($a = 1; $a < $_SESSION['store']; $a++)

	{

		echo '

		<div class = "row mt1 text-center">

			<div class = "col-md-1 col-md-offset-1">'.$a.'</div>
			<div class = "col-md-3">'.$_SESSION['player'.$a].'</div>
			<div class = "col-md-2">'.$_SESSION['level'.$a].'</div>
			<div class = "col-md-2">'.$_SESSION['direct'.$a].'</div>
			<div class = "col-md-1">'.$_SESSION['fee'.$a].'</div>
			<div class = "col-md-1">'.$_SESSION["active_member".$a].'</div>

		</div>';
		
		
		
		//     array("課程報名費",850,$_SESSION['store']-1),
		//	   array("A級教練重新註冊費",450,0),
		//	   array("B級教練重新註冊費",350,0),
		//	   array("C級裁判註冊費",250,0),
		
		//	   array("個人會員年費",30,0),
		//	   array("郵件掛號費", 60, 0));
		
		switch($_SESSION['level'.$a]){
			
			case "A":
				$item[1][2]++;   // a license fee
				break;
			case "B":
				$item[2][2]++;    // b license fee
				break;
			case "C":
				$item[3][2]++;  // c license fee
				break;
		    
		}
	
		//check if it is existing member add annual individual member fee $30
		if($_SESSION['active_member'.$a] != "Y"){
		    $item[5][2]++;
        }

			if($_SESSION['direct'.$a] == "Y")
		{
			$item[6][2]++;
		}

	}



	?>

	<div class = "row mt2">

		<div class = "col-md-8 col-md-offset-2">

			<p align='left'>

				一經付款，本會將處理閣下之報名。參加及註冊費必須預繳，所繳費用一經收妥作實概不發還。
                
                如尚未成為本年度會員者，需加入成為中國香港柔道總會個人會員，並支付本年度年費 ($30), 個人會員必須每年支付新一年年度年費。
      
				參加者必須於課程完結後一個月內，前往總會領取其證書。若以郵寄方式領証，將不在此限。如參加者未能親身前來，可以書面授權他人代為領取。逾期者另收行政手續費用港幣貳佰元正。<br>

				確認後代表參加者同意參加「 2025年香港柔道教練研討課程 」，並明白若虛報資料，將被即時取消所有參加資格，及可能交由「 中國香港柔道總會紀律委員會 」處理，所繳費用概不發還。

				參加者的健康及體能狀況良好，適合參加此課程。如因參加者的疏忽或健康狀況欠佳，引致參加是項活動時傷亡，主辦、合辦、協辦及相關協作機構無須負責。

			</p>

		</div>

	</div>

	<div class = "row mt1">

		<div class = "col-md-3 col-md-offset-3 mt2 text-center">

			<form action="<?php echo $paypal_url;?>" method="post">

				<input type="hidden" name="cmd" value="_cart">

				<input type="hidden" name="charset" value="utf-8" >

				<input type="hidden" name="business" value="<?php echo $ac;?>">

				<input type="hidden" name="rm" value="2">

				<input type="hidden" name="custom" value="<?php echo $custom;?>">

				<input type="hidden" value="http://www.judoregistration.org/thankyou_payment.php" name="return">

				<input type="hidden" value="http://www.judoregistration.org/confirm_payment.php" name="notify_url">

				<input type="hidden" name="upload" value="1">

				<?php

				//calculating number of items needed to send to paypal

				for($a = 0, $c = 1; $a < sizeof($item); $a++)

					{

						if($item[$a][2] > 0)

						{

	     
							echo '<input type="hidden" name="item_name_'.$c.'" value="'.$item[$a][0]."/".$name3['name']."/".$name3['name_chi'].'">';

							echo '<input type="hidden" name="item_number_'.$c.'" value="'.$item[$a][0]."/".$name3['name']."/".$name3['name_chi'].'">';

							echo '<input type="hidden" name="amount_'.$c.'" value="'.$item[$a][1].'">';

							echo '<input type="hidden" name="quantity_'.$c.'" value="'.$item[$a][2].'">';

							$c++;

						}

				}

				?>

				<input type="hidden" name="currency_code" value="HKD">

				<!--input type="image" src="https://www.paypalobjects.com/zh_HK/HK/i/btn/btn_paynowCC_LG.gif" name="submit" alt="Make payments with PayPal - it's fast, free and secure!"-->

			</form>
				<form method="POST" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
	        	<input type="submit" name="submit" value="確認並稍後付款 Confirm and Pay Later">
        	</form>

		</div>

<?php 
  if (isset($_POST['submit'])) {
        for ($b = 1; $b < $_SESSION['store']; $b++) {
            $confirm = 'INSERT INTO ' . $_SESSION['category'] . 
                      '(competition, code, country, name, name_chi, gender, division, weight, identity, date, payment) VALUES (' . 
                      $_SESSION["insert" . $b] . ',"")';
            if ($DEBUG) {
                echo "In coach_seminar25_confirm: Submitted".$confirm;
            }
            $stmt = $pdo->prepare($confirm);
            $stmt->execute();
        }
        echo '<meta http-equiv=REFRESH CONTENT=1;url=/front.php>';
    }
?>

		<div class = "col-md-3 mt2 text-center">
		    

			<form action="coach_seminar25.php" method="post">

				<input type="submit" name="return" value="上一頁 Previous Page">

			</form>

		</div>

	</div>

</div>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>

