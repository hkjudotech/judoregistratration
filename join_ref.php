<?php
	session_start();
	$title = "工作人員報名";
	include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");
	//Number of Columns
	$column = 5;
	
	
	    // Get club name
    $stmt = $pdo->prepare('SELECT name, name_chi FROM club WHERE username = ?');
    $stmt->execute([$username]);
    $name3 = $stmt->fetch();
    
	echo '</a></h3><div class = "row row-block"><p>';
													
	//Get the competition name
	if(isset($_GET["short"]))
	{
		$_SESSION['short'] = $_GET['short'];
	}
	$comp = 'SELECT name, name_eng,deadline FROM competition WHERE short = "'.$_SESSION['short'].'"';
	$comp2 = mysql_query($comp) or die('Error! ' . mysql_error());
	$comp3 = mysql_fetch_array($comp2);
	$comp_name = $comp3['name'];
	$comp_namee = $comp3['name_eng'];
	$deadline = $comp3['deadline'];
		
	//Select data from participants
	$play = 'SELECT id, name, name_chi, category, gender, birthday FROM participants_local WHERE club = "'.$username.'" ORDER BY name';
	$play2 = mysql_query($play) or die('Error! ' . mysql_error());
	$i = 0;
	$first = 0;
	echo "<script type='text/javascript'>";
	echo "temp = new Array();";
	while ($play3 = mysql_fetch_array($play2))
	{
		echo "temp[".$i."] = new Array(6);";
		echo "temp[".$i."][0] = '".$play3['id']."';";
		echo "temp[".$i."][1] = '".$play3['name']."/".$play3['name_chi']."';";
		echo "temp[".$i."][2] = '".$play3['name']."';";
		echo "temp[".$i."][3] = '".$play3['name_chi']."';";
		echo "temp[".$i."][4] = '".$play3['category']."';";
		echo "temp[".$i."][5] = '".$play3['gender']."';";
		$i++;	
		if ($play3['category'] == "Referee")
		{
			$firstColumn[$first] = $play3['name']."/".$play3['name_chi'];
			$first++;
		}
	}
	echo "</script>";
?>
	
						 
<form name="myForm" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<div class = "row text-center">
		<?php 
			echo $comp_name."<br>".$comp_namee."<br><br>";
		?>
	</div>
	<div class = "row mt2">
		<div class = "col-md-1 col-md-offset-3"></div>
		<div class = "col-md-3">類別 Position</div>
		<div class = "col-md-4">參賽者 Participants</div>
	</div>
	<?php
	for ($f = 1; $f < $column + 1; $f++)
	{
		 echo
		 '<div class = "row mt1">
				<div class= "col-md-1 col-md-offset-3">'.$f.'</div>
				<div class = "col-md-3">
					<select name=position'.$f.' OnChange="Buildkey'.$f.'0(this.selectedIndex);">
						<option value = "Referee">Referee</option>
						<option value = "Coach">Coach</option>
					</select>
				</div>
				<div class = "col-md-4">
					<select name=player'.$f.' style="font-family: Arial" size="1">
					<option></option>';
					for($a = 0; $a < sizeof($firstColumn); $a++)
					{
						echo "<option>";
						echo $firstColumn[$a];
						echo "</option>";
					}
			echo 
					'</select>
				</div>
			</div>';
	}
	
	?>	 
	<div class = "row text-center mt2">
		<input type="submit" name="submit" value="提交 Submit">
	</div>
</form>


<?php

	if(isset($_POST['submit']))
	{ 
		$store = 1;
		for($p = 1; $p < $column + 1;$p++)
		{
			if($_POST['player'.$p] != NULL)
			{
				$_SESSION['position'.$store] = $_POST['position'.$p];
				$split = explode("/",$_POST['player'.$p]);
				$_SESSION['player'.$store] = $_POST['player'.$p];
				$_SESSION['name'.$store] = $split[0];
				$_SESSION['name_chi'.$store] = $split[1];
				$_SESSION['insert'.$store] = '"'.$_SESSION['short'].'","'.$name3['code'].'","'.$name3['name'].'","'.$split[0].'","'.$split[1].'","","","","'.$_POST["position".$p].'","'.date("F j, Y, g:i a").'"';
				$store++;
			}
		}
		$_SESSION['ref'] = true;
		$_SESSION['store'] = $store;
		$_SESSION['competition_chi'] = $comp_name;
		$_SESSION['competition_eng'] = $comp_namee;
		$_SESSION['category'] = "local";
		echo '<meta http-equiv=REFRESH CONTENT=1;url=confirm_join.php>';
	}
?>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>

 <script type="text/javascript">

	// First Layer
	key = new Array(2);
	key[0] = new Array();
	key[1] = new Array();
	// Referee
	key[0][0] = "";
	for(i=0, j=1; i < temp.length; i++)
	{
		if(temp[i][4] == "Referee")
		{
			key[0][j] = temp[i][1];
			j++;
		}
	}
	key[1][0] = "";
	for(i=0, j=1; i < temp.length; i++)
	{
		if(temp[i][6] > 17)
		{
			key[1][j] = temp[i][1];
			j++;
		}
	}
	</script>
	<?php
	echo "<script type='text/javascript'>";
	for ($d = 1; $d < $column + 1; $d++)
	{
		echo "function Buildkey".$d."0(num)
		{
			document.myForm.player".$d.".selectedIndex = 0;
			for(ctr = 0; ctr < key[num].length; ctr++)
			{
				document.myForm.player".$d.".options[ctr] = new Option(key[num][ctr],key[num][ctr]);
			}
			document.myForm.player".$d.".length=key[num].length;
		}";
	}
	 echo "</Script>";
	 ?>

