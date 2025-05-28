<?php

session_start();

$debug="N";
$title = "儀表板 Reporting Dashboard";

include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");


// Now $conn should be available
if (!$pdo) {
    die('Database connection not available');
}

if (!$_SESSION['admin'])
{
	echo '<meta http-equiv=REFRESH CONTENT=1;url=/index.php>';
}

// Set current date
$date = date('Y-m-d');


// First query - Upcoming competitions
$myQuery = 'SELECT * FROM competition WHERE date > ? AND (type = ? OR type = ?) ORDER by date DESC';
$stmt = $pdo->prepare($myQuery);
$stmt->execute([$date, "local", "check"]);

if ($debug=="Y")  {
	print($date."\n");
	print("DEBUG:::::::Fetching rows in the result set:\n");
	print($myQuery." ".$date." ".$type1."\n");
	print("DEBUG:::::::doclink set:".$_SESSION['doclink']);
	print($params[1]);
}

?>

<div class = "row row-block">

	<h3>比賽 Upcoming Competition</h3>

<div class = "timetable" >

<!--header-->
<div class = "row">

	<div class = "row-header col-xs-12 col-md-5">比賽名稱<br>Event Name</div>

	<div class = "row-header col-xs-4 col-md-2">比賽日期<br>Date</div>

	<div class = "row-header col-xs-4 col-md-1">年齡限制<br>Age restriction</div>

	<div class = "row-header col-xs-4 col-md-1">餘下天數<br>Days left</div>

	<div class = "row-header col-xs-4 col-md-1">截止日期<br>Deadline</div>

	<div class = "row-header col-xs-4 col-md-1">報名名單<br>Current Registration</div>

	
</div>
<!-- end of header -->


<?php


// Fetch and display results
while ($comp = $stmt->fetch(PDO::FETCH_ASSOC)) {

if ($debug=="Y")  {
	print("DEBUG:::::::Found 1 entry:".$comp['name']."\n");

}

?>

<div class = "row">

<div class = "row-content col-xs-12 col-md-5"><?php echo $comp['name']?><br><?php echo $comp['name_eng']?></div>

<div class = "row-content col-xs-4 col-md-2"><?php echo date("d/m/Y",strtotime($comp['date'])); 

	if ($comp['date2'])

	{

		echo "-".date("d/m/Y",strtotime($comp['date2']));

	}?>

</div>

<div class = "row-content col-xs-4 col-md-1">

	<?php

	if ($comp['age2'])

	{

		echo $comp['age']."-".$comp['age2'];

	}else

	{

		if($comp['age'] > 0)

		{

			echo $comp['age']."up";

		}

		else

		{

			echo "N/A";

		}

	}

	?>

</div>

<?php $deadline = floor(strtotime($comp['deadline'])/(60*60*24) - time()/(60*60*24)) + 1; ?>

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

		echo date("d/m/Y",strtotime($comp['deadline']));
		

	}else{

		echo "N/A";

	}

	?>

</div>

<div class = "row-content col-xs-4 col-md-1">

	<?php

		echo "<a href='comp_listing.php?short=".$comp['short']."&eventname=".$comp['name']."&deadline=".$comp['deadline']."'>參加者</a>";
		echo " / <a href='../competition/comp_quota.php?short=".$comp['short']."'>報名人數</a>";

	?>

</div>


</div>

<?php } ?>

</div>


</div>


<div class = "row row-block list-group">
	<h3>會籍及會員管理 Club and Membership Management</h3>
	<div class = "timetable">
	<h4><a href='new_club.php' class="list-group-item list-group-item-action " >新申請觀察會員</a></h4>
	<h4><a href='school_listing.php' class="list-group-item list-group-item-action " >學校一覽 School Listing </a></h4>
	<h4><a href='club_listing.php' class="list-group-item list-group-item-action " >會籍一覽 Club Listing</a></h4>
	<h4><a href='event_payment_listing.php' class="list-group-item list-group-item-action " >活動網上付款一覽</a></h4>
	<h4><a href='club_payment_listing.php' class="list-group-item list-group-item-action " >會籍付款一覽</a></h4>
	<!--h4><a href='ranking_listing.php' class="list-group-item list-group-item-action">已參加排名運動員 Ranked Athletes </a></h4-->
	<h4><a href='member_listing.php?role=Referee' class="list-group-item list-group-item-action">裁判 Referees</a></h4>
	<h4><a href='member_listing.php?role=Coach' class="list-group-item list-group-item-action"> 教練 Coaches</a></h4>
	<h4><a href='member_listing.php?role=Athlete' class="list-group-item list-group-item-action"> 運動員 Athletes</a></h4>
	
	
</div>

<div class = "row row-block">

	<h3> Past Events</h3>
	
	<div class = "timetable">
<!--header-->
<div class = "row">

		<div class = "row-header col-xs-12 col-md-5">比賽名稱<br>Event Name</div>

<div class = "row-header col-xs-4 col-md-2">比賽日期<br>Date</div>

<div class = "row-header col-xs-4 col-md-1">年齡限制<br>Age restriction</div>

<div class = "row-header col-xs-4 col-md-1">章程<br>Prgram</div>

<div class = "row-header col-xs-4 col-md-1">報名名單<br>Current Registration</div>


	
</div>
<!-- end of header -->


<?php

// Second query - Past events
$myQuery = 'SELECT * FROM competition WHERE date <= ? AND (type = ? OR type = ?) ORDER by date DESC';
$stmt = $pdo->prepare($myQuery);
$stmt->execute([$date, "local", "check"]);

if ($debug=="Y") {
    print($date."\n");
    print("DEBUG:::::::Fetching rows in the result set:\n");
    print($myQuery." ".$date."\n");
}


// Second while loop for past events
while ($comp = $stmt->fetch(PDO::FETCH_ASSOC)) {




?>

<div class = "row">

<div class = "row-content col-xs-12 col-md-5"><?php echo $comp['name']?><br><?php echo $comp['name_eng']?></div>

<div class = "row-content col-xs-4 col-md-2"><?php echo date("d/m/Y",strtotime($comp['date'])); 

	if ($comp['date2'])

	{

		echo "-".date("d/m/Y",strtotime($comp['date2']));

	}?>

</div>

<div class = "row-content col-xs-4 col-md-1">

	<?php

	if ($comp['age2'])

	{

		echo $comp['age']."-".$comp['age2'];

	}else

	{

		if($comp['age'] > 0)

		{

			echo $comp['age']."up";

		}

		else

		{

			echo "N/A";

		}

	}

	?>

</div>


<div class = "row-content col-xs-4 col-md-1">

<?php

if ($debug=="Y")  {
	print("DEBUG:::::::doclink set:".$_SESSION['doclink']);
}

	echo "<a href=".$_SESSION['doclink'].$comp['short'].".pdf".">章程</a>";

?>

</div>

<div class = "row-content col-xs-4 col-md-1">

	<?php

		echo "<a href='comp_listing.php?short=".$comp['short']."&eventname=".$comp['name']."&deadline=".$comp['deadline']."'>參加者</a>";

	?>

</div>


</div>

<?php }?>


</div>


</div>



<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>

