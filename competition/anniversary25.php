<?php
session_start();


$DEBUG=false;

/////// set up session var from initial log in and clear out rest /////
$username=$_SESSION['username'];
$category=$_SESSION['category'];
$admin=$_SESSION['admin'];
$log=$_SESSION['log'];
$_SESSION = [];  //clearout session
$_SESSION['username']=$username;
$_SESSION['category']=$category;
$_SESSION['admin']=$admin;
$_SESSION['log']=$log;




include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

try{

if ($DEBUG){
    echo "**********DEBUG MSG::::";
    foreach ($_SESSION as $key=>$val)
    echo $key . "=>" . $val." ";
    echo "<br>";
}

$title = '柔道總會55週年香港柔道錦標賽';

//Number of Columns
$column = 10;
//Year
$year = 2025;
// Change
$_SESSION['competition_chi'] = "柔道總會55週年香港柔道錦標賽";
$_SESSION['competition_eng'] = "55th Anniversary Hong Kong Judo Championships 2025";
$_SESSION['short'] = "anniversary25";
$_SESSION['item_name'] = "柔道總會55週年香港柔道錦標賽報名費";
$_SESSION['ref'] = 0;

 // Get competition details
    $stmt = $pdo->prepare('SELECT name, name_eng, date FROM competition WHERE short = ?');
    $stmt->execute([$_SESSION['short']]);
    $comp3 = $stmt->fetch();
    $date = $comp3['date'];


//competition fee for the tournament
$fee = 60;
$memberfee = 30;

   // Get club details
    $stmt = $pdo->prepare('SELECT name, name_chi, code FROM club WHERE username = ?');
    $stmt->execute([$username]);
    $name3 = $stmt->fetch();
    
echo '</a></h3><div class = "row row-block"><p>';

  // Get participants data
    $stmt = $pdo->prepare('SELECT id, name, name_chi, birthday, gender, active_member 
                          FROM participants_local 
                          WHERE club = ? AND category = "Athlete" 
                          ORDER BY name');
    $stmt->execute([$username]);
    
$i = 0;
$first = 0;
	
echo "<script type='text/javascript'>";
echo "temp = new Array();";
  while ($play3 = $stmt->fetch()) {
        echo "temp[".$i."] = new Array(7);";
        echo "temp[".$i."][0] = '".htmlspecialchars((string)$play3['id'])."';";
        echo "temp[".$i."][1] = '".htmlspecialchars($play3['name'])."/".htmlspecialchars($play3['name_chi'])."';";
        echo "temp[".$i."][2] = '".htmlspecialchars($play3['name'])."';";
        echo "temp[".$i."][3] = '".htmlspecialchars($play3['name_chi'])."';";
        echo "temp[".$i."][4] = '".yearAge($play3['birthday'], $year)."';";
        echo "temp[".$i."][5] = '".htmlspecialchars($play3['gender'])."';";
        echo "temp[".$i."][6] = '".htmlspecialchars($play3['active_member'])."';";
	
	$i++;	
	// First group
	if ($play3['gender'] == "M" && yearAge($play3['birthday'], $year) > 14)
	{
		$firstColumn[$first] = $play3['name']."/".$play3['name_chi'];
		$first++;
	}
}
//testing
/*for ($j=0; $j<6;$j++)
{
	for ($k=0;$k<6; $k++)
	{
		echo "document.write(temp[".$j."][".$k."]);";
		echo "document.write('<br>');";
	}
}*/
echo "</script>";
?>
						 
<form name="myForm" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<div class = "row text-center">
		<?php
		 echo $_SESSION['competition_chi'];
		 echo '<br>';
		 echo $_SESSION['competition_eng'];
		?>
	</div>


		   <div class = "row text-center">
        <br>請<a href="comp_quota.php?short=<?php echo $_SESSION['short']?>" target="_blank">按此</a>確認每個組別的報名人數，謝謝。
        <p>Please <a href="comp_quota.php?short=<?php echo $_SESSION['short']?>" target="_blank">press here</a> to view the no. of participants per category . Thank you.</p>
  	</div>
  
	<div class = "row mt2">
		<div class = "col-md-2"></div>
		<div class = "col-md-1">性別 Gender</div>
		<div class = "col-md-2">組別 Group</div>
		<div class = "col-md-3">體重級別 Weight Category</div>
		<div class = "col-md-3">參賽者 Participants</div>
		<div class = "col-md-1">現任個人會員 Current Individual Member </div>
	
	</div>
	
	<?php
	 for ($f = 1; $f < $column + 1; $f++)
	 {
		 echo
		 '<div class = "row mt1">
			<div class = "col-md-1 col-md-offset-1">'.$f.'</div>
			<div class = "col-md-1">
				<select name=gender'.$f.' OnChange="Buildkey'.$f.'0(this.selectedIndex);">
					<option value = "Male">Male</option>
					<option value = "Female">Female</option>
				</select>
			</div>
			<div class = "col-md-2">
				<select name=group'.$f.' OnChange="Buildkey'.$f.'1(this.selectedIndex);Buildkey'.$f.'2(this.selectedIndex);">
					 <option value = "Senior">Senior</option>
					 <option value = "Junior">Junior</option>
				</select>
			</div>
			<div class = "col-md-3">
				<select name=weight'.$f.' style="font-family: Arial" size="1">
					
					<option value = "-60 kg">-60 kg</option>
					<option value = "-66 kg">-66 kg</option>
					<option value = "-73 kg">-73 kg</option>
					<option value = "-81 kg">-81 kg</option>
					<option value = "-90 kg">-90 kg</option>
					<option value = "-100 kg">-100 kg</option>
					<option value = "+100 kg">+100 kg</option>
				</select>
			</div>
			<div class = "col-md-3"> 
				<select name=player'.$f.' onchange="getMemberStatus'.$f.'();" style="font-family: Arial" size="1">
				<option></option>';
				for($a = 0; $a < sizeof($firstColumn); $a++){
					echo "<option>";
					echo $firstColumn[$a];
					echo "</option>";
				}
				echo '</select>
				</div>
				  <div class = "col-md-1">
					<input name=status'.$f.' value = "N" style="font-family: Arial" size="1" readonly="true">
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
				$_SESSION['gender'.$store] = $_POST['gender'.$p];
				$_SESSION['group'.$store] = $_POST['group'.$p];
				$_SESSION['weight'.$store] = $_POST['weight'.$p];
				$split = explode("/",$_POST['player'.$p]);
				$_SESSION['player'.$store] = $_POST['player'.$p];
				$_SESSION['name'.$store] = $split[0];
				$_SESSION['name_chi'.$store] = $split[1];
				$_SESSION['active_member'.$store] = $_POST['status'.$p];
				$_SESSION['insert'.$store] = '"'.$_SESSION['short'].'","'.$name3['code'].'","'.$name3['name'].'","'.$split[0].'","'.$split[1].'","'.$_POST["gender".$p].'","'.$_POST["group".$p].'","'.$_POST["weight".$p].'","Athlete","'.date("F j, Y, g:i a").'"';
				$store++;
			}
		}
		$_SESSION['store'] = $store;
		$_SESSION['fee'] = $fee;
		$_SESSION['memberfee'] = $memberfee;
		$_SESSION['pay'] = $store - 1;
		$_SESSION['category'] = "local";
		//echo '<meta http-equiv=REFRESH CONTENT=1;url=confirm_join.php>';
		echo '<meta http-equiv=REFRESH CONTENT=1;url=confirm_join_member.php>';
	}
	
} catch(PDOException $e) {
    if($DEBUG) {
        die('Error: ' . $e->getMessage());
    } else {
        die('Database error occurred. Please try again later.');
    }
}
?>
	
	</p>
</div>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>

 <script type="text/javascript">

     $(document).ready(function () {

         $("#accordian").accordion();
         $("#accordian-description").accordion();
     });
	// First Layer
	key = new Array(2);
	key[0]=new Array(2);
	key[1]=new Array(2);
	// Second Layer
	key1 = new Array(2);
	key1[0]=new Array(2);
	key1[1]=new Array(2);
	// Second Layer 2
	key2 = new Array(2);
	key2[0]=new Array(2);
	key2[1]=new Array(2);
	// M 
	key[0][0]="Senior";
	key[0][1]="Junior";
	// F
	key[1][0]="Senior";
	key[1][1]="Junior";
	// Second Layer
	key1[0][0]=new Array(7);
	key1[0][1]=new Array(7);
	key1[1][0]=new Array(7);
	key1[1][1]=new Array(7);
	// Second Layer 2
	key2[0][0]=new Array();
	key2[0][1]=new Array();
	key2[1][0]=new Array();
	key2[1][1]=new Array();
	
	// M 
	key1[0][0][0]="-60 kg";
	key1[0][0][1]="-66 kg";
	key1[0][0][2]="-73 kg";
	key1[0][0][3]="-81 kg";
	key1[0][0][4]="-90 kg";
	key1[0][0][5]="-100 kg";
	key1[0][0][6]="+100 kg";
	
	key1[0][1][0]="-55 kg";
	key1[0][1][1]="-60 kg";
	key1[0][1][2]="-66 kg";
	key1[0][1][3]="-73 kg";
	key1[0][1][4]="-81 kg";
	key1[0][1][5]="-90 kg";
	key1[0][1][6]="+90 kg";
	
	// F
	key1[1][0][0]="-48 kg";
	key1[1][0][1]="-52 kg";
	key1[1][0][2]="-57 kg";
	key1[1][0][3]="-63 kg";
	key1[1][0][4]="-70 kg";
	key1[1][0][5]="-78 kg";
	key1[1][0][6]="+78 kg";
	
	key1[1][1][0]="-44 kg";
	key1[1][1][1]="-48 kg";
	key1[1][1][2]="-52 kg";
	key1[1][1][3]="-57 kg";
	key1[1][1][4]="-63 kg";
	key1[1][1][5]="-70 kg";
	key1[1][1][6]="+70 kg";
	
	
	
	// M (Key2) 
	//Age 15nup Male
	key2[0][0][0] = "";
	for(i=0, j=1; i < temp.length; i++)
	{
		if(temp[i][4] > 14 && temp[i][5] === "M")
		{
			key2[0][0][j] = temp[i][1];
			j++;
		}
	}
	//Age 15 - 20 Male
	key2[0][1][0] = "";
	for(i=0, j=1; i < temp.length; i++)
	{
		if(temp[i][4] > 14 && temp[i][4] < 21 && temp[i][5] === "M")
		{
			key2[0][1][j] = temp[i][1];
			j++;
		}
	}
	//Age 15nup Female
	key2[1][0][0] = "";
	for(i=0, j=1; i < temp.length; i++)
	{
		if(temp[i][4] > 14 && temp[i][5] === "F")
		{
			key2[1][0][j] = temp[i][1];
			j++;
		}
	}
	//Age 16 - 20 Female
	key2[1][1][0] = "";
	for(i=0, j=1; i < temp.length; i++)
	{
		if(temp[i][4] > 14 && temp[i][4] < 21 && temp[i][5] === "F")
		{
			key2[1][1][j] = temp[i][1];
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
			Buildkey".$d."1(0);
			Buildkey".$d."2(0);
			document.myForm.group".$d.".selectedIndex = 0;
			for(ctr = 0; ctr < key[num].length; ctr++)
			{
				document.myForm.group".$d.".options[ctr] = new Option(key[num][ctr],key[num][ctr]);
			}
			document.myForm.group".$d.".length=key[num].length;
		} 

		function Buildkey".$d."1(num)
		{
			document.myForm.weight".$d.".selectedIndex = 0;
			for(ctr = 0; ctr < key1[document.myForm.gender".$d.".selectedIndex][num].length; ctr++)
			{
				document.myForm.weight".$d.".options[ctr] = new Option(key1[document.myForm.gender".$d.".selectedIndex][num][ctr],key1[document.myForm.gender".$d.".selectedIndex][num][ctr]);
			}
			document.myForm.weight".$d.".length=key1[document.myForm.gender".$d.".selectedIndex][num].length;
		}	 
		function Buildkey".$d."2(num)
		{
			document.myForm.player".$d.".selectedIndex = 0;
			for(ctr = 0; ctr < key2[document.myForm.gender".$d.".selectedIndex][num].length; ctr++)
			{
				document.myForm.player".$d.".options[ctr] = new Option(key2[document.myForm.gender".$d.".selectedIndex][num][ctr],key2[document.myForm.gender".$d.".selectedIndex][num][ctr]);
			}
			document.myForm.player".$d.".length=key2[document.myForm.gender".$d.".selectedIndex][num].length;
		}
		
		function getMemberStatus".$d."()
		{
		    for (k=0;k<temp.length; k++){
		       
		       
		        if (document.myForm.player".$d.".value == temp[k][1])
		        {
		              // debug alert(k); 
		              document.myForm.status".$d.".value=temp[k][6];
		             
		        }
		        
		    }
		    
		}
		
		";
	}
	 echo "</Script>";
	 ?>