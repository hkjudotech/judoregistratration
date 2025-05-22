<?php
session_start();
$title = '2025年香港柔道公開賽';
$DEBUG=true;

include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

try{
//Number of Columns
$column = 10;
// Change
$_SESSION['competition_chi'] = "2025香港柔道公開賽";
$_SESSION['competition_eng'] = "Hong Kong Judo Championships 2025";
$_SESSION['short'] = "judo_open25";
$_SESSION['item_name'] = "2025年香港柔道公開賽報名費";
$_SESSION['ref'] = 0;

// Get competition details
    $stmt = $pdo->prepare('SELECT name, name_eng, date FROM competition WHERE short = ?');
    $stmt->execute([$_SESSION['short']]);
    $comp3 = $stmt->fetch();
    $date = 2025;


//competition fee for the tournament
$fee = 60;
$memberfee = 30;

  // Get club details
    $stmt = $pdo->prepare('SELECT name, name_chi, code FROM club WHERE username = ?');
    $stmt->execute([$username]);
    $name3 = $stmt->fetch();
    
echo '</a></h3><div class = "row row-block"><p>';

  //Select data from participants
    $stmt = $pdo->prepare('SELECT id, name, name_chi, birthday, gender, active_member 
                          FROM participants_local 
                          WHERE club = ? 
                          ORDER BY name');
    $stmt->execute([$username]);
    
$i = 0;
$first = 0;
    $firstColumn = [];
    
echo "<script type='text/javascript'>";
echo "temp = new Array();";

  while ($play3 = $stmt->fetch()) {
        echo "temp[".$i."] = new Array(7);";
        echo "temp[".$i."][0] = '".htmlspecialchars((string)$play3['id'])."';";
        echo "temp[".$i."][1] = '".htmlspecialchars($play3['name'])."/".htmlspecialchars($play3['name_chi'])."';";
        echo "temp[".$i."][2] = '".htmlspecialchars($play3['name'])."';";
        echo "temp[".$i."][3] = '".htmlspecialchars($play3['name_chi'])."';";
        echo "temp[".$i."][4] = '".yearAge($play3['birthday'],$date)."';";
        echo "temp[".$i."][5] = '".htmlspecialchars($play3['gender'])."';";
        echo "temp[".$i."][6] = '".htmlspecialchars($play3['active_member'])."';";
        $i++;    
        
        if ($play3['gender'] == "M" && yearAge($play3['birthday'],$date) > 13) {
            $firstColumn[$first] = htmlspecialchars($play3['name'])."/".htmlspecialchars($play3['name_chi']);
            $first++;
        }
    }

if ($DEBUG){
    for ($j=0; $j<4;$j++)
    {
        for ($k=0;$k<7; $k++)
	    {
    	    echo "document.write(temp[".$j."][".$k."]);";
	        echo "document.write('<br>');";
        }
    }
}
echo "</script>";
?>

<form name="myForm" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<div class = "row text-center"><h4>
		<?php
		 echo $_SESSION['competition_chi'];
		 echo '<br>';
		 echo $_SESSION['competition_eng'];
		?>
	</h4></div>
	
	
	   <div class = "row text-center">
        <br>請<a href="comp_quota.php?short=<?php echo $_SESSION['short']?>" target="_blank">按此</a>確認每個組別的報名人數，謝謝。
        <p>Please <a href="comp_quota.php?short=<?php echo $_SESSION['short']?>" target="_blank">press here</a> to view the no. of participants per category . Thank you.</p>
  	</div>
  
	<div class = "row mt2">
		<div class = "col-md-2"></div>
		<div class = "col-md-2">性別 Gender</div>
		<div class = "col-md-3">體重級別 Weight Category</div>
		<div class = "col-md-4">參賽者 Participants</div>
		<div class = "col-md-1">現任個人會員 Current Individual Member </div>
	
	</div>
	
	<?php
	for ($f = 1; $f < $column + 1; $f++)
	{
		echo
		'<div class = "row mt1">
      <div class = "col-md-1 col-md-offset-1">'.$f.'</div>
		  <div class = "col-md-2">
		  	<select name=gender'.$f.' OnChange="Buildkey'.$f.'0(this.selectedIndex);">
      		<option value = "Male">Male</option>
      		<option value = "Female">Female</option>
		    </select>
		  </div>
		  <div class = "col-md-3">
		    <select name=weight'.$f.' style="font-family: Arial" size="1">
      		
      		<option value = "Male -60 kg">Male -60 kg</option>
      		<option value = "Male -66 kg">Male -66 kg</option>
      		<option value = "Male -73 kg">Male -73 kg</option>
      		<option value = "Male -81 kg">Male -81 kg</option>
      		<option value = "Male -90 kg">Male -90 kg</option>
      		<option value = "Male -100 kg">Male -100 kg</option>
      		<option value = "Male +100 kg">Male +100 kg</option>
		    </select>
		  </div>
		    <div class = "col-md-4">
		  	<select name=player'.$f.' onchange="getMemberStatus'.$f.'();" style="font-family: Arial" size="1">
		    <option></option>';
		    
		    for($a = 0; $a < sizeof($firstColumn); $a++)
    		{
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



if ($DEBUG){
    for ($j=0; $j<4;$j++)
    {
        for ($k=0;$k<7; $k++)
	    {
    	    echo $temp[".$j."][".$k."];
	        
        }
    }
}

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
			
			
			switch ($_POST['weight'.$p]){
  			    
  				case "Male -60 kg":     $_SESSION['internal_group'.$store] = "M1";break;
  				case "Male -66 kg":     $_SESSION['internal_group'.$store] = "M2";break;
  				case "Male -73 kg":     $_SESSION['internal_group'.$store] = "M3";break;
  				case "Male -81 kg":     $_SESSION['internal_group'.$store] = "M4";break;
  				case "Male -90 kg":     $_SESSION['internal_group'.$store] = "M5";break;
  				case "Male -100 kg":     $_SESSION['internal_group'.$store] = "M6";break;
  				case "Male +100 kg":     $_SESSION['internal_group'.$store] = "M7";break;
  				
  				case "Female -48 kg":     $_SESSION['internal_group'.$store] = "W1";break;
  				case "Female -52 kg":     $_SESSION['internal_group'.$store] = "W2";break;
  				case "Female -57 kg":     $_SESSION['internal_group'.$store] = "W3";break;
  				case "Female -63 kg":     $_SESSION['internal_group'.$store] = "W4";break;
  				case "Female -70 kg":     $_SESSION['internal_group'.$store] = "W5";break;
  				case "Female -78 kg":     $_SESSION['internal_group'.$store] = "W6";break;
  				case "Female +78 kg":     $_SESSION['internal_group'.$store] = "W7";break;
  				default: $_SESSION['internal_group'.$store] = "XX"; 
  			}
  			
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

<?php  include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>
 <script type="text/javascript">
	// First Layer
	key = new Array(2);
	key[0]=new Array(7);
	key[1]=new Array(7);
	// Second Layer
	key1 = new Array(2);
	key1[0]=new Array();
	key1[1]=new Array();
	// M 
	
	key[0][0]="Male -60 kg";
	key[0][1]="Male -66 kg";
	key[0][2]="Male -73 kg";
	key[0][3]="Male -81 kg";
	key[0][4]="Male -90 kg";
	key[0][5]="Male -100 kg";
	key[0][6]="Male +100 kg";
	// F
	
	key[1][0]="Female -48 kg";
	key[1][1]="Female -52 kg";
	key[1][2]="Female -57 kg";
	key[1][3]="Female -63 kg";
	key[1][4]="Female -70 kg";
	key[1][5]="Female -78 kg";
	key[1][6]="Female +78 kg";
	
	// M (Key2) 
	//Male
	key1[0][0] = "";
	for(i=0, j=1; i < temp.length; i++)
	{
		if(temp[i][4] > 13 && temp[i][5] == "M")
		{
			key1[0][j] = temp[i][1];
			j++;
		}
	}
	//Female
	key1[1][0] = "";
	for(i=0, j=1; i < temp.length; i++)
	{
		if(temp[i][4] > 13 && temp[i][5] == "F")
		{
			key1[1][j] = temp[i][1];
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
			Buildkey".$d."1(num);
			document.myForm.weight".$d.".selectedIndex = 0;
			for(ctr = 0; ctr < key[num].length; ctr++)
			{
				document.myForm.weight".$d.".options[ctr] = new Option(key[num][ctr],key[num][ctr]);
			}
			document.myForm.weight".$d.".length=key[num].length;
			
		}
		function Buildkey".$d."1(num)
		{
			document.myForm.player".$d.".selectedIndex = 0;
			for(ctr = 0; ctr < key1[document.myForm.gender".$d.".selectedIndex].length; ctr++)
			{
				document.myForm.player".$d.".options[ctr] = new Option(key1[document.myForm.gender".$d.".selectedIndex][ctr],key1[document.myForm.gender".$d.".selectedIndex][ctr]);
			}
			document.myForm.player".$d.".length=key1[num].length;
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
</body>
</html>
