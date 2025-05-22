<?php
session_start();
$title = "2025年香港柔道教練研討課程";
$DEBUG=false;
include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

//Number of rows
$column = 5;
//Change
$_SESSION['competition_chi'] = "2025年香港柔道教練研討課程";
$_SESSION['competition_eng'] = "Hong Kong Judo Coaching Seminar 2025";
$_SESSION['competition_name'] = "coach_seminar25";
echo '</a></h3>';

//fee
$memberfee = 30;
$regA=450;
$regB=350;
$regC=250;
$seminar=850;
//$examfee=300;
$mailfee=60;


//Select data from participants
$play = 'SELECT id, name, name_chi, birthday, gender, category, active_member FROM participants_local WHERE club = "'.$username.'" ORDER BY name';
$play2 = mysql_query($play) or die('Error! ' . mysql_error());
$i = 0;
$first = 0;

echo "<script type='text/javascript'>";

echo "temp = new Array();";

while ($play3 = mysql_fetch_array($play2))

{

	echo "temp[".$i."] = new Array(7);";

	echo "temp[".$i."][0] = '".$play3['id']."';";

	echo "temp[".$i."][1] = '".$play3['name']."/".$play3['name_chi']."';";

	echo "temp[".$i."][2] = '".$play3['name']."';";

	echo "temp[".$i."][3] = '".$play3['name_chi']."';";

	echo "temp[".$i."][4] = '".$play3['category']."';";
    echo "temp[".$i."][5] = '".$play3['gender']."';";
	echo "temp[".$i."][6] = '".$play3['active_member']."';";

	$i++;

	if (yearAge($play3['birthday'],2025) > 17)

	{

		$firstColumn[$first] = $play3['name']."/".$play3['name_chi'];

		$first++;

	}

}

//testing

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



<div class="row row-block">

	<form name="myForm" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">

		<div class = "row text-center">

			<?php

				echo $_SESSION['competition_chi'];

				echo '<br>';

				echo $_SESSION['competition_eng'];

			?>

		</div>

		<div class = "row mt2">

			<div class = "col-md-1 col-md-offset-1"></div>

			<div class = "col-md-2">參加者<br>Participants</div>

			<div class = "col-md-2">考核級別<br>Registration Grade</div>

			<div class = "col-md-2">證書掛號<br>Registered Mail</div>

			<div class = "col-md-1">費用<br>Fee</div>
            <div class = "col-md-2">現任個人會員 Current Individual Member </div>
		</div>



		 <?php

		 for ($f = 1; $f < $column + 1; $f++)

		 {

			echo

			'<div class = "row mt1">

				<div class = "col-md-1 col-md-offset-1">'.$f.'</div>

				<div class = "col-md-2">

					<!--select name=player'.$f.' onchange="calculate'.$f.'();" style="font-family: Arial" size="1"-->
						<select name=player'.$f.' onchange="getMemberStatus'.$f.'();" style="font-family: Arial" size="1"> 
		

					<option></option>';

					for($a = 0; $a < sizeof($firstColumn); $a++){

						echo "<option>";

						echo $firstColumn[$a];

						echo "</option>";

					}

			echo 	'</select>

				</div>

				<div class = "col-md-2">

					<select name=level'.$f.' onchange="calculate'.$f.'();" style="font-family: Arial" size="1">

		     			 <option value = "">請選擇重新註冊教練級別</option>
        	
						<option value = "Seminar-Only"> N/A - 只參加教練課程</option>

						<option value = "A">A</option>

						<option value = "B">B</option>

						<option value = "C">C</option>

					</select>

				</div>

				<div class = "col-md-2">

					<select name=direct'.$f.' onchange="calculate'.$f.'();" style="font-family: Arial" size="1">

						<option value = "">No</option>

						<option value = "Y">Yes</option>

					</select>

				</div>

				<div class = "col-md-1">
					<input name=fee'.$f.' value = 0 style="font-family: Arial" size="4" readonly="true">
				</div>
				
					 <div class = "col-md-1">
					<input name=status'.$f.' value = "N" style="font-family: Arial" size="1" readonly="true">
				</div>
			

			 </div>';

		}

		?>

		<div class = "row">

			<div class = "col-md-2"></div>

			<div class = "col-md-8 text-center"> 

				<div class = "row mt2">

					<div class = "col-md-6 border-full">個人會員年費 (如尚未成為新年度2025會員)</div>

					<div class = "col-md-6 border-full">$30</div>

				</div>
				<div class = "row">

					<div class = "col-md-6 border-full">郵件掛號費 (（自取者豁免）</div>

					<div class = "col-md-6 border-full">$60</div>

				</div>

				
				<div class = "row mt2">

					<div class = "col-md-6 border-full">課程報名費</div>

                    <!--450 old -->
					<div class = "col-md-6 border-full">$850</div>

				</div>

				<!--div class = "row mt2">

					<div class = "col-md-6 border-full">考試費 （只適用於參加考試者）</div>
                    <div class = "col-md-6 border-full">$300</div>

				</div-->

				<div class = "row">

					<div class = "col-md-6 border-full">A級(只適用於重新註冊者)</div>

					<div class = "col-md-6 border-full">$450</div>

				</div>

        		<div class = "row">

					<div class = "col-md-6 border-full">B級(只適用於重新註冊者)</div>

					<div class = "col-md-6 border-full">$350</div>

				</div>

            	<div class = "row">

					<div class = "col-md-6 border-full">C級(只適用於重新註冊者)</div>

					<div class = "col-md-6 border-full">$250</div>

				</div>


				<div class = "row text-center mt2">

					<input type="submit" name="submit" value="提交 Submit">

				</div>

			</div>

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

			$_SESSION['level'.$store] = $_POST['level'.$p];

			$_SESSION['direct'.$store] = $_POST['direct'.$p];

			$split = explode("/",$_POST['player'.$p]);

			$_SESSION['player'.$store] = $_POST['player'.$p];

			$_SESSION['name'.$store] = $split[0];

			$_SESSION['name_chi'.$store] = $split[1];
			
			$_SESSION['active_member'.$store] = $_POST['status'.$p];

			$_SESSION['fee'.$store] = $_POST['fee'.$p];

			$_SESSION['insert'.$store] = '"'.$_SESSION['competition_name'].'","'.$name3['code'].'","'.$name3['name'].'","'.$split[0].'","'.$split[1].'","","'.$_POST["level".$p].'","'.$_POST["direct".$p].'","Participant","'.date("F j, Y, g:i a").'"';
            
            if ($DEBUG) {
		    	echo $_SESSION['player'.$store].":".$_SESSION['name'.$store].":".$_SESSION['group'.$store].":".$_SESSION['weight'.$store].":".$_SESSION['active_member'.$store];
		    	echo "<br>";
		    }

			$store++;

		}

	}

	$_SESSION['store'] = $store;
	$_SESSION['memberfee'] = $memberfee;

    $_SESSION['pay'] = $store - 1;
  	$_SESSION['category'] = "local";

	echo '<meta http-equiv=REFRESH CONTENT=1;url=coach_seminar25_confirm.php>';

}



?>

</p>

</div>



<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>

<?php

echo "<script type='text/javascript'>";

for ($d = 1; $d < $column + 1; $d++)

{

echo "


function calculate".$d."()
{
	fee = 0;
	if (document.myForm.player".$d.".value != '')
	{
		switch (document.myForm.level".$d.".value)
		{
			case '': fee = 0;
			break;
			case 'A': 
			    fee = 850+450;
			    break;
			case 'B': 
			    fee = 850+350;
			    break;
			case 'C': fee = 850+250;
			    break;
			case 'Seminar-Only': fee = 850;
			    break;
		}
		
	    if ((fee > 0) && (document.myForm.status".$d.".value == 'N')){
		    fee += 30;
		}
		
		if (document.myForm.direct".$d.".value == 'Y')
		{
			fee += 60;
		}
	}
	document.myForm.fee".$d.".value = fee;

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
    
    calculate".$d."();
    
		    
} //end getMemberStatus


";

}

echo "</Script>";

?>

