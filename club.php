<?php
session_start();
$title = "觀察團體會員申請表 Registration of Observation Organization Member";
include_once($_SERVER['DOCUMENT_ROOT']."/common/header_nologin.php");
?>

<div class = "row row-block">
	<div class = "col-md-10 col-md-offset-1">
		<h3>觀察團體會員申請表</h3>
		<h3>請把所有打*部分妥當填好，填好後將有專人處理閣下之事宜。</h3>
		<form class = "form-horizontal" id="clubForm" method="POST"  enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<?php
			textColumn("團體名稱 (中文)", "name_chi", "", 0);
			textColumn("團體名稱 (英文)","name","",1);
			textColumn("商業登記證/社團註冊證號碼","br","",1);
			dateColumn("商業登記證/社團註冊證有效日期","br_date",1);
			textColumn("聯絡地址","address","",1);				
			textColumn("電話","phone","",1);
			textColumn("練習地點","practice_place","",1);
			textColumn("練習時間","practice_time","",1);
		?>
			<br>
		<?php
			textColumn("教練(1)姓名","coach_name","",1);
			textColumn("教練(1)段數","coach_dan","",1);
			textColumn("教練(1)身份證號碼","coach_id","",1);
			textColumn("教練(2)姓名","coach2_name","",0);
			textColumn("教練(2)段數","coach2_dan","",0);
			textColumn("教練(2)身份證號碼","coach2_id","",0);
		?>
			<br>
		<?php
			textColumn("負責人姓名","rep_name","",1);
			textColumn("負責人身份證號碼","rep_id","",1);
			textColumn("負責人通訊地址","rep_address","",1);
			textColumn("負責人電話","rep_phone","",1);
			textColumn("電郵地址","rep_email","",1);
		?>
			<div class = "row">
				<label for="argee">
					負責人聲明: <br>
					1. 本人及本人負責之上述團體願意承諾遵守中國香港柔道總會所訂立之規條，並確認所填報的資料全部屬實。 <br>
					2. 本人接受中國香港柔道總會無須為本人及本人負責之上述團體的學員，因個人疏忽或健康或體能欠佳而引致於參加中國香港柔道總會
					所舉辦、協辦或贊助的活動中可能引致的傷亡負上任何責任。 <br><br>
					申請資格﹕ <br>
					1. 凡有興趣在香港推廣柔道運動的組織、社團或協會等合法團體均可申請成為本會觀察會員 <br>
					2. 申請團體必須有最少 15 名學員及 1 名持有本會確認的有效B級註冊教練證書的教練 <br>
					3. 申請團體之負責人必須年滿十八歲，並持有有效香港身份證 <br><br>
					備註： <br>
					1. 申請團體須填妥本表格，本會將要求負責人提供以下文件﹕ <br>
					(i) 負責人身份證副本； <br>
					(ii) 商業登記證/社團註冊證副本； <br>
					(iii) 教練證書副本； <br>
					(iv) 學員名單(請列明其姓名、段數/級數、年齡及電話號碼)；及 <br>
					(v) 最近場地使用證明文件副本。(持續性的及固定性的訓練地點) <br>
					2. 本會觀察團體會員首年申請費及年費共港幣 4,000 元，其後每年年費為港幣 500 元(年期由4月1日至翌年3月31日)。如申請獲接納，本會將通知負責人繳交有關費用。 <br>
					3. 負責人所提供的資料，只限本會處理會員事宜或本會舉辦相關活動之用。在遞交申請表後，負責人如欲更改或查詢曾申報的個人資料，
					可與本會職員聯絡。  
				</label>
			</div>
			<div class = "row" style = "text-align:center">
				<input class = "form-control" type="checkbox" name="agree" value="1"><label>本人已閱讀及同意以上之內容。 I have read and agree all the content above</label>
			</div>
			<div class = "row" style = "text-align:center">
				<input class="btn btn-primary" name="submit" class="submit" type="submit" value="Submit">
			</div>
		</form>
	</div>
</div>

<?php
if ($_POST['submit'])
{
	$_SESSION['type'] = "new";
	//$_SESSION['add'] = '"'.$_POST['name_chi'].'","'.$_POST['name'].'","'.$_POST['br'].'","'.$_POST['br_date'].'","'.$_POST['address'].'","'.$_POST['phone'].'","'.$_POST['practice_place'].'","'.$_POST['practice_time'].'","'.$_POST['coach_name'].'","'.$_POST['coach_dan'].'","'.$_POST['coach_id'].'","'.$_POST['coach2_name'].'","'.$_POST['coach2_dan'].'","'.$_POST['coach2_id'].'","'.$_POST['rep_name'].'","'.$_POST['rep_id'].'","'.$_POST['rep_address'].'","'.$_POST['rep_phone'].'","'.$_POST['rep_email'].'","'.$_SESSION['type'].'"';
	$_SESSION['name_chi'] = $_POST['name_chi'];
	$_SESSION['name'] = $_POST['name'];
	$_SESSION['br'] = $_POST['br'];
	$_SESSION['br_date'] = $_POST['br_date_year'].'-'.$_POST['br_date_month'].'-'.$_POST['br_date_day'];
	$_SESSION['address'] = $_POST['address'];
	$_SESSION['phone'] = $_POST['phone'];
	$_SESSION['practice_place'] = $_POST['practice_place'];
	$_SESSION['practice_time'] = $_POST['practice_time'];
	$_SESSION['coach_name'] = $_POST['coach_name'];
	$_SESSION['coach_dan'] = $_POST['coach_dan'];
	$_SESSION['coach_id'] = $_POST['coach_id'];
	$_SESSION['coach2_name'] = $_POST['coach2_name'];
	$_SESSION['coach2_dan'] = $_POST['coach2_dan'];
	$_SESSION['coach2_id'] = $_POST['coach2_id'];
	$_SESSION['rep_name'] = $_POST['rep_name'];
	$_SESSION['rep_id'] = $_POST['rep_id'];
	$_SESSION['rep_address'] = $_POST['rep_address'];
	$_SESSION['rep_phone'] = $_POST['rep_phone'];
	$_SESSION['rep_email'] = $_POST['rep_email'];
	echo '<meta http-equiv=REFRESH CONTENT=1;url=confirm_club.php>';
}
?>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>