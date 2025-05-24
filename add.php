<?php
session_start();
$title = "註冊參加者 Registration of new participants";

include_once($_SERVER['DOCUMENT_ROOT'] . "/common/header.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/common/count.php");

$DEBUG = false;

// Initialize error array
$errors = array();

if ($DEBUG) {
	var_dump($item);
}
?>

<div class="row row-block">
	<div class="col-md-10 col-md-offset-1">
		<h3>註冊新參加者 Registration of New Participants</h3>

		<?php
		// Display errors if any
		if (!empty($errors)) {
			echo '<div class="alert alert-danger">';
			echo '<ul>';
			foreach ($errors as $error) {
				echo '<li>' . htmlspecialchars($error) . '</li>';
			}
			echo '</ul>';
			echo '</div>';
		}
		?>

		<form class="form-horizontal" id="signupForm" method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<div class="row">
				<div class="col-xs-10 col-xs-offset-1 col-md-5">
					<label for="name">英文姓名 English Name*</label>
				</div>
				<div class="col-xs-10 col-xs-offset-1 col-md-5 col-md-offset-0">
					<input class="form-control" name="name" type="text" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-10 col-xs-offset-1 col-md-5">
					<label for="name_chi">中文姓名 Chinese Name</label>
				</div>
				<div class="col-xs-10 col-xs-offset-1 col-md-5 col-md-offset-0">
					<input class="form-control" name="name_chi" type="text" value="<?php echo isset($_POST['name_chi']) ? htmlspecialchars($_POST['name_chi']) : ''; ?>">
				</div>
			</div>
			<div class="row">
				<div class="col-xs-10 col-xs-offset-1 col-md-5">
					<label for="gender">性別 Gender*</label>
				</div>
				<div class="col-xs-10 col-xs-offset-1 col-md-5 col-md-offset-0">
					<select class="form-control" name="gender" type="text" required>
						<option value="">請選擇 Please Select</option>
						<option value="M" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'M') ? 'selected' : ''; ?>>男 Male</option>
						<option value="F" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'F') ? 'selected' : ''; ?>>女 Female</option>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-10 col-xs-offset-1 col-md-4">
					<label>出生日期 Birthday*</label>
				</div>
				<div class="col-xs-3 col-xs-offset-1 col-md-2 col-md-offset-0">
					<label for="day">日 Day</label>
					<select class="form-control" name="day" type="text" required>
						<option value="">日</option>
						<?php
						for ($i = 1; $i < 32; $i++) {
							$selected = (isset($_POST['day']) && $_POST['day'] == $i) ? 'selected' : '';
							echo "<option value ='" . $i . "' $selected>" . $i . "</option>";
						}
						?>
					</select>
				</div>
				<div class="col-xs-3 col-md-2">
					<label for="month">月 Month</label>
					<select class="form-control" name="month" type="text" required>
						<option value="">月</option>
						<?php
						for ($i = 1; $i < 13; $i++) {
							$selected = (isset($_POST['month']) && $_POST['month'] == $i) ? 'selected' : '';
							echo "<option value ='" . $i . "' $selected>" . $i . "</option>";
						}
						?>
					</select>
				</div>
				<div class="col-xs-3 col-md-2">
					<label for="year">年 Year</label>
					<select class="form-control" name="year" type="text" required>
						<option value="">年</option>
						<?php
						$year_start = date("Y");
						for ($i = $year_start; $i > $year_start - 100; $i--) {
							$selected = (isset($_POST['year']) && $_POST['year'] == $i) ? 'selected' : '';
							echo "<option value ='" . $i . "' $selected>" . $i . "</option>";
						}
						?>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-10 col-xs-offset-1 col-md-5">
					<label for="address">地址 Address*</label>
				</div>
				<div class="col-xs-10 col-xs-offset-1 col-md-5 col-md-offset-0">
					<input class="form-control" name="address" maxlength="255" type="text" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" required>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-10 col-xs-offset-1 col-md-5">
					<label for="hkid">身份證編號/非香港居民(國藉)証件編號 HKID No/Passport No for Non HK Citizen(首4個數字 First 4th digits)*</label>
				</div>
				<div class="col-xs-10 col-xs-offset-1 col-md-5 col-md-offset-0">
					<input class="form-control" name="hkid" type="text" maxlength="5" value="<?php echo isset($_POST['hkid']) ? htmlspecialchars($_POST['hkid']) : ''; ?>" required>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-10 col-xs-offset-1 col-md-5">
					<label for="email">電郵地址 Email*</label>
				</div>
				<div class="col-xs-10 col-xs-offset-1 col-md-5 col-md-offset-0">
					<input class="form-control" type="email" name="email" maxlength=100" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-10 col-xs-offset-1 col-md-5">
					<label for="ph">聯絡電話 Phone*</label>
				</div>
				<div class="col-xs-10 col-xs-offset-1 col-md-5 col-md-offset-0">
					<input class="form-control" type="tel" name="phone" maxlength="20" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" required>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-10 col-xs-offset-1 col-md-2 ">
					<label for="cat[]">身份 Position*</label>
				</div>
				<div class="col-xs-6 col-md-2">
					<input type="checkbox" name="cat[]" value="Athlete" <?php echo (isset($_POST['cat']) && in_array('Athlete', $_POST['cat'])) ? 'checked' : ''; ?>><label>運動員 Athlete</label>
				</div>
				<div class="col-xs-6 col-md-2">
					<input type="checkbox" name="cat[]" value="Coach" <?php echo (isset($_POST['cat']) && in_array('Coach', $_POST['cat'])) ? 'checked' : ''; ?>><label>教練 Coach</label>
				</div>
				<div class="col-xs-6 col-md-2">
					<input type="checkbox" name="cat[]" value="Referee" <?php echo (isset($_POST['cat']) && in_array('Referee', $_POST['cat'])) ? 'checked' : ''; ?>><label>裁判 Referee</label>
				</div>
				<div class="col-xs-6 col-md-2">
					<input type="checkbox" name="cat[]" value="Official" <?php echo (isset($_POST['cat']) && in_array('Official', $_POST['cat'])) ? 'checked' : ''; ?>><label>工作人員 Official</label>
				</div>
			</div>

			<div class="row">
				<label for="agree">
					申請人聲明:<br>
					1. 本人願意承諾遵守中國香港柔道總會所訂立之規條，並確認所填報的資料全部屬實。<br>
					2. 本人聲明個人健康及體能良好，適宜參加柔道活動。如果因本人的疏忽或健康或體能欠佳而引致於參加中國香港柔道總會所舉辦、協辦或贊助的活動中可能引致的傷亡，中國香港柔道總會則無須負上任何責任。<br><br>
					Declartion<br>
					1. I undertake to observe all rules and regulations stuipulateed by the Judo Associaiton of Hong Kong, China and confirm that all information are correct. <br>
					2.I declare that the Judo Association of Hong Kong, China shall not be liable for any injury of death which I may suffer in any activities organized, co-organized or sponsored by the Judo Association of Hong Kong, China, if the cause of injury or death is due to own negligence or inadequacy in heath and fitness.
				</label>
			</div>
			<div class="row" style="text-align:center">
				<input type="checkbox" name="agree" value="1" <?php echo (isset($_POST['agree']) && $_POST['agree'] == '1') ? 'checked' : ''; ?> required><label>本人已閱讀及同意以上之內容。 I have read and agree all the content above</label>
			</div>
			<div class="row" style="text-align:center">
				<input class="btn btn-primary" name="submit" class="submit" type="submit" value="Submit">
			</div>
		</form>
		<div>
		</div>

		<?php

		if ($DEBUG) {
			echo "In debugging:  <br>";
		}

		// Server-side validation when form is submitted
		if ($_POST['submit']) {
			// Validate required fields
			if (empty($_POST['name'])) {
				$errors[] = "英文姓名 English Name is required";
			}

			if (empty($_POST['gender'])) {
				$errors[] = "性別 Gender is required";
			}

			if (empty($_POST['day']) || empty($_POST['month']) || empty($_POST['year'])) {
				$errors[] = "出生日期 Birthday is required";
			}

			if (empty($_POST['address'])) {
				$errors[] = "地址 Address is required";
			}

			if (empty($_POST['hkid'])) {
				$errors[] = "身份證編號 HKID No is required";
			}

			if (empty($_POST['email'])) {
				$errors[] = "電郵地址 Email is required";
			} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				$errors[] = "請輸入有效的電郵地址 Please enter a valid email address";
			}

			if (empty($_POST['phone'])) {
				$errors[] = "聯絡電話 Phone is required";
			}

			if (empty($_POST['cat']) || !is_array($_POST['cat'])) {
				$errors[] = "身份 Position is required";
			}

			if (empty($_POST['agree'])) {
				$errors[] = "您必須同意條款 You must agree to the terms";
			}

			// Validate date
			if (!empty($_POST['day']) && !empty($_POST['month']) && !empty($_POST['year'])) {
				if (!checkdate($_POST['month'], $_POST['day'], $_POST['year'])) {
					$errors[] = "請輸入有效的出生日期 Please enter a valid birthday";
				}
			}

			// If no errors, proceed with existing logic
			if (empty($errors)) {
				// Check if member already exists
				$sql = 'SELECT COUNT(*) FROM participants_' . $_SESSION['category'] .
					' WHERE birthday = :birthday AND hkid = :hkid';
				$stmt = $pdo->prepare($sql);
				$birthday = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'];
				$stmt->execute([
					':birthday' => $birthday,
					':hkid' => $_POST['hkid']
				]);
				$number3 = $stmt->fetch(PDO::FETCH_ASSOC);

				if ($number3['COUNT(*)'] > 0) {
					// Get existing member details
					$sql = 'SELECT * FROM participants_' . $_SESSION['category'] .
						' WHERE birthday = :birthday AND hkid = :hkid';
					$stmt = $pdo->prepare($sql);
					$stmt->execute([
						':birthday' => $birthday,
						':hkid' => $_POST['hkid']
					]);

					while ($exist3 = $stmt->fetch(PDO::FETCH_ASSOC)) {
						if ($_SESSION['username'] != $exist3['club']) {
							echo "該會員已於其他會登記，如有問題，請與本會查詢<br>";
							break;
						}
						if ($_POST['cat'] == $exist3['category']) {
							echo "該會員已登記該身份，如有問題，請與本會查詢<br>";
							break;
						}
					}
				}

				// Continue with existing form processing logic
				$_SESSION['add'] = '"' . $_SESSION['username'] . '","' . $name3['name'] . '","' . $_POST['name'] . '","' . $_POST['name_chi'] . '","' . $_POST['gender'] . '","' . $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'] . '","' . $_POST['address'] . '","' . $_POST['hkid'] . '","' . $_POST['email'] . '","' . $_POST['phone'] . '","' . date("F j, Y, g:i a") . '"';
				$_SESSION['name'] = ucwords(strtolower($_POST['name']));
				$_SESSION['name_chi'] = $_POST['name_chi'];
				$_SESSION['gender'] = $_POST['gender'];
				$_SESSION['birthday'] = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'];
				$_SESSION['address'] = $_POST['address'];
				$_SESSION['hkid'] = $_POST['hkid'];
				$_SESSION['email'] = $_POST['email'];
				$_SESSION['phone'] = $_POST['phone'];
				$_SESSION['cat'] = $_POST['cat'];
				move_uploaded_file($_FILES["file"]["tmp_name"], "tempupload/" . $_FILES["file"]["name"]);
				$_SESSION['filename'] = $_FILES["file"]["name"];
				$_SESSION['adding'] = 1;
				echo '<meta http-equiv=REFRESH CONTENT=1;url=confirm.php>';
				$_qualify = 0;
			} else {
				// If there are errors, the form will be redisplayed with error messages
				if ($DEBUG) {
					echo "Validation errors found:<br>";
					foreach ($errors as $error) {
						echo $error . "<br>";
					}
				}
			}
		}

		?>


		<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/common/footer.php"); ?>