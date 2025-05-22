<?php
session_start();

//not sure where this is used.

?>
<html>
<head>
    <title>註冊團體會員重新登記表</title>
		<meta http-equiv="Content-Type" content="text/html; charset=BIG5" />
    <link href="css/jquery-ui-1.8.11.custom.css" type="text/css" rel="Stylesheet" />
	<link href="css/main.css" type="text/css" rel="Stylesheet" />
</head>
<body>
 <div id="main" >
    <div id="content">
        <table id="content-table" border="0" style="margin:0px 40px;padding: 20px 0px;">
                 <tr>
                    <td>
                    <div id="accordian-description">
                        <h3>
                            <a href="#">
							<?php
						// PDO Database connection
                        try {
                           $pdo = new PDO('mysql:host=localhost;dbname=judonorg_judo;charset=utf8', 'judonorg_reg', '1024judo', [
                           PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                           PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
                               ]);
                        } catch (PDOException $e) {
                               die('Error! login: ' . $e->getMessage());
                        }
                        $qualify = 0;

                        // For login user
                        if (isset($_SESSION['username'])) {
                           $username = $_SESSION['username'];
                           $stmt = $pdo->prepare('SELECT * FROM club WHERE username = :username');
                           $stmt->execute(['username' => $username]);
                           $name3 = $stmt->fetch(PDO::FETCH_ASSOC);
                        }
						
						
						
							//Function to check if the field is filled
							function filled($input, $error_msg)
							{
								if($input != NULL)
								{
									return true;
								}echo "請填上 ".$error_msg.".<br>"; 
								return false;
							}
							//Function to check if the input is within the maximum length
							function lenmax($input, $length)
							{
								if(strlen($input) > $length)
								{
									echo "You have exceeded the maximum length of ".$length." characters.";
									return false;
								}return true;
							}
							//Function to check if the input is within the minimum length
							function lenmin($input, $length)
							{
								if(strlen($input) < $length)
								{
									echo "You have not met the minimum length of ".$length." characters.";
									return false;
								}return true;
							}
							//Function to check if it is a validated email
							function validate_email($email) 
							{
								return preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email);
							}
							function column($title, $name, $must)
							{
								global $qualify, $name3;
								echo '<tr><td>'.$title;
								if ($must == 1)
								{
									echo '*';
								}
								echo '</td><td>:</td><td><input type="text" name="'.$name.'" size="30" value="';
								if(isset($_POST[$name]))
								{
									echo $_POST[$name].'">';
									echo "</td><td>";
									if ($must == 1)
									{
										if(filled($_POST[$name],$title))
										{
											$qualify++;
										}
									}
								}else{
									echo '">';
								}
								echo "</td></tr>";
								return $qualify;
							}
							?>
							</a></h3><div><p>	
							<b>註冊團體會員重新登記表:</b>
							<br>
							請把所有打*部分妥當填好，填好後將有專人處理閣下之事宜。
							<br>
							<table border="0">
							<form method="POST" action="<?php echo $_SERVER['PHP_SELF'].'"';
							//團體名稱 (中文)*	name_chi
							echo '<tr><td>團體名稱 (中文)*</td><td>:</td><td><input type="text" name="name_chi" size="30" value="';
							if(isset($_POST['name_chi']))
							{
								echo $_POST['name_chi'].'">';
								echo "</td><td>";
								if(filled($_POST['name_chi'],"中文名稱") && lenmax($_POST['name_chi'],50))
								{
									$qualify++;
								}
							}else{
							echo '">';
							}
							echo "</td></tr>";
							column("團體名稱 (英文)","name",1);
							column("商業登記證/社團註冊證號碼","br",1);
							column("商業登記證/社團註冊證有效日期(DD-MM-YY)","br_date",1);
							column("聯絡地址","address",1);				
							column("電話","phone",1);
							column("練習地點","practice_place",1);
							column("練習時間","practice_time",1);
							echo "<tr height = '20'><td></td></tr>";
							column("教練(1)姓名","coach_name",1);
							column("教練(1)段數","coach_dan",1);
							column("教練(1)身份證號碼","coach_id",1);
							column("教練(2)姓名","coach2_name",0);
							column("教練(2)段數","coach2_dan",0);
							column("教練(2)身份證號碼","coach2_id",0);
							echo "<tr height = '20'><td></td></tr>";
							column("負責人姓名","rep_name",1);
							column("負責人身份證號碼","rep_id",1);
							column("負責人通訊地址","rep_address",1);
							column("負責人電話","rep_phone",1);
							column("電郵地址","rep_email",1);
							
							?>
							<tr><td colspan='4'><br>
							<font size = "2">
							負責人聲明: <br>
							1. 本人及本人負責之上述團體願意承諾遵守中國香港柔道總會所訂立之規條，並確認所填報的資料全部屬實。 <br>
							2. 本人接受中國香港柔道總會無須為本人及本人負責之上述團體的學員，因個人疏忽或健康或體能欠佳而引致於參加中國香港柔道總會
							所舉辦、協辦或贊助的活動中可能引致的傷亡負上任何責任。 <br><br>
							申請資格﹕ <br>
							1. 本會註冊團體會員由觀察團體會員晉升 <br>
							備註： <br>
							1. 申請團體須填妥本表格，本會將要求負責人提供以下文件﹕ <br>
							(i) 負責人身份證副本； <br>
							(ii) 商業登記證/社團註冊證副本； <br>
							(iii) 教練證書副本； <br>
							(iv) 學員名單(請列明其姓名、段數/級數、年齡及電話號碼)；及 <br>
							(v) 最近場地使用證明文件副本。(持續性的及固定性的訓練地點) <br>
							2. 本會註冊團體會員年費港幣800元(年期由4月1日至翌年3月31日)。如重新登記獲接納，本會將通知負責人繳交有關費用。 <br>
							3. 負責人所提供的資料，只限本會處理會員事宜或本會舉辦相關活動之用。在遞交申請表後，負責人如欲更改或查詢曾申報的個人資料，
							可與本會職員聯絡。  <br><br></font>
							</td></tr>
							<tr><td colspan = '4' align = 'center'>
							<input type="checkbox" name="agree" value="1"><label>本人已閱讀及同意以上之內容。</label>
							<?php
							if (isset($_POST['agree']))
							{
								$qualify++;
							} else
								
							?>
							</td>			
							</tr>
							<br>
							<tr><td colspan='4' align = 'center'><input type="submit" value="提交"></td></tr>
							</form>		
							

										
							<?php
							if ($qualify > 16)
							{
								$_SESSION['type'] = "renew_registered";
								$_SESSION['add'] = '"'.$_POST['name_chi'].'","'.$_POST['name'].'","'.$_POST['br'].'","'.$_POST['br_date'].'","'.$_POST['address'].'","'.$_POST['phone'].'","'.$_POST['practice_place'].'","'.$_POST['practice_time'].'","'.$_POST['coach_name'].'","'.$_POST['coach_dan'].'","'.$_POST['coach_id'].'","'.$_POST['coach2_name'].'","'.$_POST['coach2_dan'].'","'.$_POST['coach2_id'].'","'.$_POST['rep_name'].'","'.$_POST['rep_id'].'","'.$_POST['rep_address'].'","'.$_POST['rep_phone'].'","'.$_POST['rep_email'].'","'.$_SESSION['type'].'"';
								$_SESSION['name_chi'] = $_POST['name_chi'];
								$_SESSION['name'] = $_POST['name'];
								$_SESSION['br'] = $_POST['br'];
								$_SESSION['br_date'] = $_POST['br_date'];
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
                       </p>
                        </div>
                        
                    </div>
                </td>
           </tr>
        </table>
    </div>
 </div>
 <script src="js/js/jquery-1.5.1.min.js" type="text/javascript"></script>
 <script src="js/js/jquery-ui-1.8.11.custom.min.js" type="text/javascript"></script>
 <script type="text/javascript">

     $(document).ready(function () {

         $("#accordian").accordion();
         $("#accordian-description").accordion();
     });

</script>
</body>
</html>
