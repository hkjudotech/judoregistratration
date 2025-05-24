<?php

session_start();

$title = "比賽報名 Entries";

include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

?>



<div class = "row row-block">

<h4>選擇想參與的比賽 <br>Select a competition to entry</b>



<div class = "timetable">

	<div class = "row">

		<div class = "row-header col-xs-12 col-md-5">比賽名稱<br>Event Name</div>

		<div class = "row-header col-xs-4 col-md-2">日期<br>Date</div>

		<div class = "row-header col-xs-4 col-md-1">年齡限制<br>Age restriction</div>

		<div class = "row-header col-xs-4 col-md-1">餘下天數<br>Days left</div>

		<div class = "row-header col-xs-4 col-md-1">報名<br>Register</div>

		<div class = "row-header col-xs-4 col-md-1">職員報名<br>Officials Register</div>

		<div class = "row-header col-xs-4 col-md-1">現有報名<br>Current Registration</div>

	</div>



<?php


// First query - Upcoming competitions
$comp = 'SELECT * FROM competition WHERE date > ? AND type = ? ORDER by date asc';
$stmt = $pdo->prepare($comp);
$stmt->execute([$date, $_SESSION['category']]);

// Fetch and display results
while ($comp3 = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>

		<div class = "row">

			<div class = "row-content col-xs-12 col-md-5"><?php echo $comp3['name']?><br><?php echo $comp3['name_eng']?></div>

			<div class = "row-content col-xs-4 col-md-2"><?php echo date("d/m/Y",strtotime($comp3['date'])); 

				if ($comp3['date2'])

				{

					echo "-".date("d/m/Y",strtotime($comp3['date2']));

				}?>

			</div>

			<div class = "row-content col-xs-4 col-md-1">

				<?php

				if ($comp3['age2'])

				{

					echo $comp3['age']."-".$comp3['age2'];

				}else

				{

					if($comp3['age'] > 0)

					{

						echo $comp3['age']."up";

					}

					else

					{

						echo "N/A";

					}

				}

				?>

			</div>

			<?php $deadline = floor(strtotime($comp3['deadline'])/(60*60*24) - time()/(60*60*24)) + 1; ?>

			<div class = "row-content col-xs-4 col-md-1">

				<?php

				if ($deadline > -1)

				{

					echo $deadline;

				}else

				{

					echo 0;

				}

				?>

			</div>

			<div class = "row-content col-xs-4 col-md-1">

				<?php

				if ($deadline > -1)

				{

					echo "<a color = '#AAAAAA' href=competition/".$comp3['short'].".php ><font color = '#FF0000'>Register</font></a>";

				}else{

					echo "N/A";

				}

				?>

			</div>

			<div class = "row-content col-xs-4 col-md-1">

				<?php

				if(($comp3['official'] == "Y") AND ($deadline > -1) AND 
   (strpos($comp3['short'], 'coach') === false) AND 
   (strpos($comp3['short'], 'ref') === false))

				{

					echo "<a href='join_ref.php?short=".$comp3['short']."'><font color = '#00AA00'>Official</font></a>";

				}else{

					echo "N/A";

				}

				?>

			</div>

			<div class = "row-content col-xs-4 col-md-1">

				<?php 
				
				if($deadline > -1)

				{
				    echo "<td width = '90'><a href='join_check.php?short=".$comp3['short']."&dl=1'><font color = '#0000FF'>Check</font></a>"; 
				} else {
				    echo "<td width = '90'><a href='join_check.php?short=".$comp3['short']."&dl=0'><font color = '#0000FF'>Check</font></a>"; 
				}
				
				?>

			</div>

		</div>

	<?php }?>

</div>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>

