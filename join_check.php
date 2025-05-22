<?php
session_start();
$title = "現有報名 Current Registration";
include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

//Number of Columns
$column = 15;
$name = 'SELECT name, name_chi FROM club WHERE username = "'.$username.'"';
$name2 = mysql_query($name) or die('Error! ' . mysql_error());
$name3 = mysql_fetch_array($name2);

if(isset($_GET["short"]))
{
	$_SESSION['short'] = $_GET["short"];
}
if(isset($_GET["dl"]))
{
	$_SESSION['dl'] = $_GET['dl'];
    echo '</a></h3><div><p>';
}


// Name of the competition
$comp = 'SELECT name, name_eng FROM competition WHERE short = "'.$_SESSION['short'].'"';
$comp2 = mysql_query($comp) or die('Error! ' . mysql_error());
$comp3 = mysql_fetch_array($comp2);

//function to delete
function delete($del)
{
	$delete = "DELETE FROM ".$_SESSION['category']." WHERE id = '".$del."'";
    //$delete = "UPDATE ".$_SESSION['category']." SET "." WHERE id = '".$del."'";
	$delete2 = mysql_query($delete) or die('Error! ' . mysql_error());
	echo " Participant deleted";
	echo '<meta http-equiv=REFRESH CONTENT=2;>';
}

//Select data from participants
$part = "SELECT id, name, name_chi, gender, division, weight, identity, payment FROM ".$_SESSION['category']." where country = '".$name3['name']."' AND competition = '".$_SESSION['short']."' ORDER BY gender, weight, name";
$part2 = mysql_query($part) or die('Error! ' . mysql_error());
?>


<div class = "row row-block">
	<form name="myForm" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<div class ="row text-center">
			<?php
			echo $comp3['name'];
			echo "<br>";
			echo $comp3['name_eng'];
			?>
		</div>

		<div class = "row mt2">
			<div class = "col-md-1 col-md-offset-1">身份<br>Identity</div>
			<div class = "col-md-2">參賽者<br>Participants</div>
			<div class = "col-md-1">中文名稱<br>Chinese Name</div>
			<div class = "col-md-1">性別年齡<br>Gender/Age</div>
			<div class = "col-md-1">Division<br>Division</div>
			<div class = "col-md-1">體重<br> Weight</div>
			<div class = "col-md-2">已網上付款<br>Paid on Web</div>
			<div class = "col-md-1">刪除<br>Delete</div>
		</div>

		<?php
		$count = 1;
		while ($part3 = mysql_fetch_array($part2))
		 {
			$p_id[$count] = $part3['id'];
			if($part3['payment'] == "paid"){
				$paid = "Yes";
			}else{
				$paid = "No";
			}
			
			
			echo
			'<div class = "row mt1">
				<div class = "col-md-1 col-md-offset-1">'.$part3['identity'].'</div>
				<div class = "col-md-2">'.$part3['name'].'</div>
				<div class = "col-md-1">'.$part3['name_chi'].'</div>
				<div class = "col-md-1">'.$part3['gender'].'</div>
				<div class = "col-md-1">'.$part3['division'].'</div>
				<div class = "col-md-1">'.$part3['weight'].'</div>
				<div class = "col-md-2">'.$paid.'</div>';
				if ($_SESSION['dl']  > 0){
					echo '<div class="col-md-1"><input type="submit" name="delete'.$count.'" value="Delete"/></div>';
				}else{
					echo '<div class="col-md-1"></div>';
				}
			echo 
			'</div>';
			$count++;
		}


		?>
	</form>
</div>


<?php

// Delete
for($i = 1; $i < $count + 1; $i++)
{
	if(isset($_POST['delete'.$i]))
	{
		delete($p_id[$i]);
	}
}
?>
</p>
</div>


<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>
