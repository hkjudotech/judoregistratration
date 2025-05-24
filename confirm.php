<?php

session_start();

$title = "註冊參加者 Registration of new participants";

include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");



//If no new data, return to add.php

if ($_SESSION['adding'] != 1)

{

	echo '<meta http-equiv=REFRESH CONTENT=1;url=add.php>';

}



function confirm(): void

{

    global $pdo;

    

    try {

        // Begin transaction

        $pdo->beginTransaction();

        

        // Prepare the SQL statement once

        $stmt = $pdo->prepare('INSERT INTO participants_' . $_SESSION['category'] . 

                             '(club, club_name, name, name_chi, gender, birthday, 

                               address, hkid, email, phone, date, category) 

                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

        

        // Get the base data array from session

        $baseData = explode('","', trim($_SESSION['add'], '"'));

        

        // Insert for each category

        foreach($_SESSION['cat'] as $cat) {

            $data = $baseData;

            $data[] = $cat;

            $stmt->execute($data);

        }

        

        

        // Commit transaction

        $pdo->commit();

        

        // Update session

        $_SESSION['confirm'] = 1;

        $_SESSION['adding'] = 0;

        

    	echo '<meta http-equiv=REFRESH CONTENT=1;url=add.php>';

        exit();

        

    } catch (PDOException $e) {

        $pdo->rollBack();

        error_log("Database Error in confirm(): " . $e->getMessage());

        throw new RuntimeException('Database error occurred. Please try again later.');

    } catch (Exception $e) {

        error_log("General Error in confirm(): " . $e->getMessage());

        throw new RuntimeException('An error occurred while processing your request.');

    }

}

?>

<div class = "row row-block">

	<div class = "col-xs-6 col-xs-offset-4">

		<h5>

			<div class = "row">

				<div class = "col-xs-5">英文姓名 English Name</div>

				<div class = "col-xs-7">:<?php echo $_SESSION['name']?></div>

			</div>

			<div class = "row">

				<div class = "col-xs-5">中文姓名 Chinese Name</div>

				<div class = "col-xs-7">:<?php echo $_SESSION['name_chi']?></div>

			</div>

			<div class = "row">

				<div class = "col-xs-5">性別 Gender</div>

				<div class = "col-xs-7">:<?php echo $_SESSION['gender']?></div>

			</div>

			<div class = "row">

				<div class = "col-xs-5">出生日期 Birthday</div>

				<div class = "col-xs-7">:<?php echo $_SESSION['birthday']?></div>

			</div>

			<div class = "row">

				<div class = "col-xs-5">地址 Address</div>

				<div class = "col-xs-7">:<?php echo $_SESSION['address']?></div>

			</div>

			<div class = "row">

				<div class = "col-xs-5">身份證編號/護照編號 HKID/Passport Number</div>

				<div class = "col-xs-7">:<?php echo $_SESSION['hkid']?></div>

			</div>

			<div class = "row">

				<div class = "col-xs-5">電郵地址 Email</div>

				<div class = "col-xs-7">:<?php echo $_SESSION['email']?></div>

			</div>

			<div class = "row">

				<div class = "col-xs-5">聯絡電話 Phone</div>

				<div class = "col-xs-7">:<?php echo $_SESSION['phone']?></div>

			</div>

			<div class = "row">

				<div class = "col-xs-5">身份 Position</div>

				<div class = "col-xs-7">:

					<?php 

					foreach($_SESSION['cat'] as $cat)

					{

						echo $cat." ";

					}

					?>

				</div>

			</div>

		</h5>

	</div>

</div>

<div class = "row row-block">

<div class = "col-xs-6 col-xs-offset-3">

	<div class = "col-xs-12">

		<?php

			if ($_SESSION['category'] == "local")

			{

				echo'

				 <h5>一經確認，本會將承認閣下為中國香港柔道總會的個人會員, 年度個人會員會費將於本年度第一次報名比賽或課程時一併收取。<br>

				 <br>

				 After Confirmation, You will become the Personal Member of the Judo Association of Hong Kong, China. The annual individual membership fee will be charged when you first register for a competition or course in the current year.<br>

	<br>';

			}

		?>

	</div>

	<div class = "col-xs-6" style = "text-align:center">

	<form method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">

		

		<input class = "btn btn-primary" type="submit" name="submit" value="確認 Confirm">

	</form>

	</div>

	<div class = "col-xs-6" style = "text-align:center">

		<form action="add.php" method="post">

			<input class = "btn btn-primary" type="submit" name="return" value="上一頁 Previous Page">

		</form>

	</div>

	<?php

	if(isset($_POST['submit']))

	{

		confirm();

	}

	?>

</div>



<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>

