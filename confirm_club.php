<?php
session_start();
$title = "會員資料確認";
include_once($_SERVER['DOCUMENT_ROOT']."/common/header_nologin.php");
?>

<?php


function confirm() {
    global $pdo;
    
    try {
        if(isset($_SESSION['id'])) {
            $id = $_SESSION['id'];
        } else {
            $id = "";
        }

        if($_SESSION['type'] == "new") {
            $sql = 'INSERT INTO club (name_chi, name, br, br_date, address, phone, 
                    practice_place, practice_time, coach_name, coach_dan, coach_id, 
                    coach2_name, coach2_dan, coach2_id, rep_name, rep_id, 
                    rep_address, rep_phone, rep_email, type, date) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                    
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_SESSION['name_chi'], $_SESSION['name'], $_SESSION['br'],
                $_SESSION['br_date'], $_SESSION['address'], $_SESSION['phone'],
                $_SESSION['practice_place'], $_SESSION['practice_time'],
                $_SESSION['coach_name'], $_SESSION['coach_dan'], $_SESSION['coach_id'],
                $_SESSION['coach2_name'], $_SESSION['coach2_dan'], $_SESSION['coach2_id'],
                $_SESSION['rep_name'], $_SESSION['rep_id'], $_SESSION['rep_address'],
                $_SESSION['rep_phone'], $_SESSION['rep_email'], $_SESSION['type'],
                date("F j, Y, g:i a")
            ]);
            
            echo '<meta http-equiv=REFRESH CONTENT=1;url=club_done.php>';
        } else {
            $sql = 'REPLACE INTO club (id, username, password, category, name_chi, 
                    name, br, br_date, address, phone, practice_place, practice_time,
                    coach_name, coach_dan, coach_id, coach2_name, coach2_dan, coach2_id,
                    rep_name, rep_id, rep_address, rep_phone, rep_email, type, date) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                    
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_SESSION['id'], $_SESSION['username'], $_SESSION['password'],
                $_SESSION['category'], $_SESSION['name_chi'], $_SESSION['name'],
                $_SESSION['br'], $_SESSION['br_date'], $_SESSION['address'],
                $_SESSION['phone'], $_SESSION['practice_place'], $_SESSION['practice_time'],
                $_SESSION['coach_name'], $_SESSION['coach_dan'], $_SESSION['coach_id'],
                $_SESSION['coach2_name'], $_SESSION['coach2_dan'], $_SESSION['coach2_id'],
                $_SESSION['rep_name'], $_SESSION['rep_id'], $_SESSION['rep_address'],
                $_SESSION['rep_phone'], $_SESSION['rep_email'], $_SESSION['type'],
                date("F j, Y, g:i a")
            ]);
            
            echo '<meta http-equiv=REFRESH CONTENT=1;url=front.php>';
        }
    } catch(PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        die('Error occurred while processing your request.');
    }
}


?>
<div class = "row row-block">
	<div class = "col-md-10 col-md-offset-1">
		<h3> 請確認閣下之申請資料</h3>
		<?php
		confirmColumn("團體名稱 (中文)",$_SESSION['name_chi']);
		confirmColumn("團體名稱 (英文)",$_SESSION['name']);
		confirmColumn("商業登記證/社團註冊證號碼",$_SESSION['br']);
		confirmColumn("商業登記證/社團註冊證有效日期(DD-MM-YY)",$_SESSION['br_date']);
		confirmColumn("聯絡地址",$_SESSION['address']);
		confirmColumn("電話",$_SESSION['phone']);
		confirmColumn("練習地點",$_SESSION['practice_place']);
		confirmColumn("練習時間",$_SESSION['practice_time']);
		?>
		<br>
		<?php
		confirmColumn("教練(1)姓名",$_SESSION['coach_name']);
		confirmColumn("教練(1)段數",$_SESSION['coach_dan']);
		confirmColumn("教練(1)身份證號碼",$_SESSION['coach_id']);
		confirmColumn("教練(2)姓名",$_SESSION['coach2_name']);
		confirmColumn("教練(2)段數",$_SESSION['coach2_dan']);
		confirmColumn("教練(2)身份證號碼",$_SESSION['coach2_id']);
		?>
		<br>
		<?php
		confirmColumn("負責人姓名",$_SESSION['rep_name']);
		confirmColumn("負責人身份證號碼",$_SESSION['rep_id']);
		confirmColumn("負責人通訊地址",$_SESSION['rep_address']);
		confirmColumn("負責人電話",$_SESSION['rep_phone']);
		confirmColumn("電郵地址",$_SESSION['rep_email']);
		?>
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
			<div class = "row" style = "text-align:center">
				<input class="btn btn-primary" name="submit" class="submit" type="submit" value="Submit">
			</div>
		</form>
		<form action="
			<?php
			if ($_SESSION['type'] == "new"){
				echo "club.php";
			}else if ($_SESSION['type'] == "renew_observation"){
				echo "club_renew.php";
			}else if ($_SESSION['type'] == "renew_registered"){
				echo "club_renew_r.php";
			}
			?>
			" method="post">
			<div class = "row" style = "text-align:center">
				<input class="btn btn-primary" name="return" value="上一頁">
			</div>
		</form>
	</div>
</div>

<?php 

if(isset($_POST['submit'])) { 
    $to_email = 'nikki_hkjudo@yahoo.com';
    $subject = '新申請觀察團體會員 New Application for Observed Membership';
    $message = "新申請觀察團體會員: 團體名稱 (中文):";
    $headers = 'From: hkjudo_mail@yahoo.com.hk';
    
    $retval = mail($to_email, $subject, $message, $headers);
    
    if($retval == true) {
        echo "Message sent successfully...";
    } else {
        echo "Message could not be sent...";
    }

    confirm(); 
} 

?>
							
<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?> 
