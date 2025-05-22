<style>
.alert {
  padding: 20px;
  background-color: #f44336;
  color: white;
}
</style>

<?php
//declare(strict_types=1); 
ini_set('session.cache_limiter','public');
session_cache_limiter(false);
session_start();

$DEBUG=false;


//Change
$YEAR = 2025; //please search for 2025 in the file for age calculation
$FEE = 300;  //competition fee for the tournament
$TEAM = 3; //Number of teams 
$COLUMN = 5; //Number of Columns
$MAX_MALE_PLAYER=5;  //how many in mens team
$MAX_FEMALE_PLAYER=5;  //how many in womens team
$MAX_MASTER_PLAYER=3;  //how many in master team
$TOTALPLAYER=$MAX_MALE_PLAYER+$MAX_FEMALE_PLAYER+$MAX_MASTER_PLAYER; //total number of players
$MINAGE=13;
$MASTER_FEMALE_AGE=35;
$MASTER_MALE_AGE=45;
$title = $YEAR."年香港柔道隊際錦標賽";
//underage waiver check
$oktosubmit = true;

include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

try {
$_SESSION['competition_chi'] = $YEAR." 年香港柔道隊際錦標賽";
$_SESSION['competition_eng'] = "Hong Kong Judo Team Championships ".$YEAR;
$_SESSION['competition_name'] = "team25";
$_SESSION['item_name'] = $YEAR." 年香港柔道隊際錦標賽報名費";

// Get club details
    $stmt = $pdo->prepare('SELECT code, name, name_chi FROM club WHERE username = ?');
    $stmt->execute([$username]);
    $clubname = $stmt->fetch();




function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function writeMsg($name) {
  echo "Hello $name!";
}

// Get players
    $players = [];
    $stmt = $pdo->prepare('SELECT id, name, name_chi, birthday, gender, active_member 
                          FROM participants_local 
                          WHERE club = ? AND category = "Athlete" 
                          ORDER BY name');
    $stmt->execute([$username]);
    
    while ($row = $stmt->fetch()) {
        $players[$row['id']] = $row;
    }
//debug printout
    if ($DEBUG) {
        foreach($players as $value) {
            printf("ID: %s Name: %s Member: %s Age: %s :::::", 
                   $value["id"], $value["name"], 
                   $value["active_member"], 
                   yearAge($value["birthday"], $YEAR));
        }
        printf(">>>>>>>>>>>>>>>>>>>>");
    }

?>


<div class="row text-center mt2 ">*****各團體限報男，女子組各一隊作賽, 先進組不限。*****</div>
<div class="row text-center mt2 ">
<p>運動員必須穿著正確的藍色或白色柔道服出場作賽, 賽會不設後備柔道服, 如選手/隊伍未能穿著適合的柔道服作賽, 將被取消參賽資格。
<br>BOTH Blue and white judogi will be required. Athletes must wear the correct blue or white Judogi for their match. No reserve judogi will be provided.
Players/Teams wearing unsuitable judogi will be disqualified.</div>
<!--男子組及女子組名額已滿, 謝謝。 先進組不限, 先到先得.
<p>
The limit for the number of entries has been reached. Thank you</p-->




<div class = "row row-block">

  <form name="myForm" method="POST" autocomplete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

    <div class = "row text-center">

      <?php

       echo $_SESSION['competition_chi'];

       echo '<br>';

       echo $_SESSION['competition_eng'];

      ?>

    </div>



    <div class = "row mt2">

        <div class = "col-md-1"></div>

        <div class = "col-md-2">性別 Gender</div>

  	    <div class = "col-md-3">體重# Weight# </div>

        <div class = "col-md-3">參賽者 Participants</div>
        
        <div class = "col-md-1">現任個人會員 <br>Current Individual Member</div>

        <div class = "col-md-2">已獲家長或監護人同意* <br>Parent/Guardian Consent* </div>

    </div>

<!-----------------Male team -------------------->
    <div class="row mt2">
      
        <div class="col-md-1 "></div>

        <div class="col-md-2" name=group1 value="Male">男子組 Male </div>

        <div class="col-md-3">
            <input type=text name=weight1 size="10"> kg</div>

        <div class="col-md-3">
            <select name=player1 style="font-family: Arial  width: 50px" size=1 onchange="setMemberStatus(this.value, 'status1')">
            <option></option>

        <?php  
            foreach($players as $value){
                if (($value["gender"]=="M")&& yearAge($value['birthday'], $YEAR) > $MINAGE) {
                    echo '<option value='.$value["id"].'>'.$value['name']."/".$value['name_chi'].'</option>';
                }
            }

         ?>

            </select>
        </div>
        
        <div class = "col-md-1">
		    <input id=status1 name=status1 value = "N" style="font-family: Arial" size="1" readonly="true">
		</div>
        
        <div class="col-md-2"> <input type=checkbox name=underage_waiver1 value="isChecked"> </div>

    </div>


 <?php

    for ($t = 2; $t <= $MAX_MALE_PLAYER; $t++) {

    ?>
    <div class="row mt1">
        <div class="col-md-3"></div>
        <div class="col-md-3">
    
    <?php      echo '<input type="text" name="weight'.$t.'" size="10"> kg
        </div>

    <div class="col-md-3">
        <select name=player'.$t.' style="font-family: Arial  width: 50px" size="1" onchange="setMemberStatus(this.value, \'status'.$t.'\')">
        <option></option>';

            foreach($players as $value){
                if (($value["gender"]=="M")&& yearAge($value['birthday'], $YEAR) > $MINAGE) {
                    echo '<option value='.$value["id"].'>'.$value['name']."/".$value['name_chi'].'</option>';
                }
            }

        ?>

        </select>
        
    </div>
        
               
        <div class = "col-md-1">
		    <?php echo '<input id=status'.$t.' name=status'.$t.' value = "N" style="font-family: Arial" size="1" readonly="true">'; ?>
		</div>
        
    <?php
        echo '
        <div class="col-md-2"> <input type=checkbox name=underage_waiver'.$t.'    value="isChecked"> </div>

    </div>';
    
    } //end for

?>


    <!-----------------female team -------------------->
  <div class="row mt2">
      
        <div class="col-md-1 "></div>

        <div class="col-md-2" name=group2 value="Female">女子組 Female </div>

        <div class="col-md-3">
            <input type=text name=weight6 size="10"> kg</div>

        <div class="col-md-3">
            <select name=player6 style="font-family: Arial  width: 50px" size=1 onchange="setMemberStatus(this.value, 'status6')">
            <option></option>

        <?php  
        
            foreach($players as $value){
                if (($value["gender"]=="F")&& yearAge($value['birthday'], $YEAR) > $MINAGE) {
                    echo '<option value='.$value["id"].'>'.$value['name']."/".$value['name_chi'].'</option>';
                }
            }

        ?>

            </select>
        </div>
        
               
        <div class = "col-md-1">
		    <input id=status6 name=status6 value = "N" style="font-family: Arial" size="1" readonly="true">
		</div>
        
        
        <div class="col-md-2"> <input type=checkbox name=underage_waiver6 value="isChecked"> </div>

    </div>

 <?php

    for ($t = 7; $t <= $MAX_MALE_PLAYER+$MAX_FEMALE_PLAYER; $t++) {

    ?>
    <div class="row mt1">
        <div class="col-md-3"></div>
        <div class="col-md-3">
    
    <?php      echo '<input type="text" name="weight'.$t.'" size="10"> kg
        </div>

    <div class="col-md-3">
        <select name=player'.$t.' style="font-family: Arial  width: 50px" size="1" onchange="setMemberStatus(this.value, \'status'.$t.'\')">
        <option></option>';

            foreach($players as $value){
                if (($value["gender"]=="F")&& yearAge($value['birthday'], $YEAR) > $MINAGE) {
                    echo '<option value='.$value["id"].'>'.$value['name']."/".$value['name_chi'].'</option>';
                }
            }

        ?>

        </select>
        
    </div>
    
           
        <div class = "col-md-1">
		    <?php echo '<input id=status'.$t.' name=status'.$t.' value = "N" style="font-family: Arial" size="1" readonly="true">'; ?>
		</div>
        
        
    <?php
        echo '
        <div class="col-md-2"> <input type=checkbox name=underage_waiver'.$t.'    value="isChecked"> </div>

    </div>';
    
    } //end for

?>

    
    <!-----------------master team -------------------->

<div class="row mt2">
      
        <div class="col-md-1 "></div>

        <div class="col-md-2" name=group3 value="Master">先進組 Master </div>

        <div class="col-md-3">
            <input type=text name=weight11 size="10"> kg</div>

        <div class="col-md-3">
            <select name=player11 style="font-family: Arial width: 50px" size=1 onchange="setMemberStatus(this.value, 'status11')">
            <option></option>

        <?php
        
        foreach($players as $value){
             if ((($value["gender"]=="F")&& yearAge($value['birthday'], $YEAR) >= $MASTER_FEMALE_AGE) || (($value["gender"]=="M")&& yearAge($value['birthday'], $YEAR) >= $MASTER_MALE_AGE)) {
                    echo '<option value='.$value["id"].'>'.$value['name']."/".$value['name_chi'].'</option>';
                }
            }

          ?>

            </select>
        </div>
        
               
        <div class = "col-md-1">
		    <input id=status11 name=status11 value = "N" style="font-family: Arial" size="1" readonly="true">
		</div>
        
        <div class="col-md-1">  </div>

    </div>

 <?php

    for ($t = 12; $t <= $TOTALPLAYER; $t++) {

    ?>
    <div class="row mt1">
        <div class="col-md-3"></div>
        <div class="col-md-3">
    
    <?php      echo '<input type="text" name="weight'.$t.'" size="10"> kg
        </div>

    <div class="col-md-3">
        <select name=player'.$t.' style="font-family: Arial  width: 50px" size="1" onchange="setMemberStatus(this.value, \'status'.$t.'\')">
        <option></option>';

      foreach($players as $value){
             if ((($value["gender"]=="F")&& yearAge($value['birthday'], $YEAR) >= $MASTER_FEMALE_AGE) || (($value["gender"]=="M")&& yearAge($value['birthday'], $YEAR) >= $MASTER_MALE_AGE)) {
                    echo '<option value='.$value["id"].'>'.$value['name']."/".$value['name_chi'].'</option>';
                }
            }


        ?>

        </select>
        
    </div>
        
                       
        <div class = "col-md-1">
		    <?php echo '<input id=status'.$t.' name=status'.$t.' value = "N" style="font-family: Arial" size="1" readonly="true">'; ?>
		</div>
		
        <div class="col-md-1"></div>

    </div>
    <?php
    } //end for

?>
    <!-------------------------------------------------------->
  	<div class = "row text-center mt2">

		  <input type="submit" name="submit" value="提交 Submit">

  	</div>
<br><p> * 如年齡未滿十八歲者須家長或監護人同意
<br> * Parent/Guardian Consent required for player under 18 years old
<p> # 如未能提供體重，隊伍有可能會被取消資格
<br> # If weight of the players were not provided during registration, your team could be disqualified
</form>

</div>








<?php

if(isset($_POST['submit'])) { 

  $store = 1;
  $maleplayer=0;
  $femaleplayer=0;
  $masterplayer=0;
  $oktosubmit = true;
  
  
  for($p = 1; $p <= $TOTALPLAYER;$p++)

  {
    if ($_POST['player'.$p] != NULL) {
        
      
       if ($DEBUG){
           writeMsg("in submit, player".$p." is not null"); // call the function

          printf(yearAge($players[$_POST['player'.$p]]['birthday'], $YEAR));
          printf($players[$_POST['player'.$p]]['weight']);
        }
      
      //if player < 18 and underage waiver checkbox not checked, set oktosubmit = false
        if ((yearAge($players[$_POST['player'.$p]]['birthday'], $YEAR) < 18) && (!isset($_POST['underage_waiver'.$p])) )
        {
            echo '<div class="alert">['.$players[$_POST['player'.$p]]['name'].'] 年齡未滿十八歲者須家長或監護人同意 Underage player without consent found. </div>';
            
            $oktosubmit=false;
        }
        
       /* if ($players[$_POST['player'.$p]]['weight'] == NULL)
        {
            $oktosubmit=false;
        
        }
    */
        if($oktosubmit==true)
        {
            if ($DEBUG){ 
                printf("setting up session variable:");
                echo "<br>";
                printf($_POST['group1']);
            }
    

        
            if ($p <=$MAX_MALE_PLAYER){
                $group ="Male";
                $maleplayer++;
            }
            else if ($p <=$MAX_MALE_PLAYER+$MAX_FEMALE_PLAYER){
                $group ="Female";
                $femaleplayer++;
            }
            else {
                $group ="Master";
                $masterplayer++;
            }
                
            $_SESSION['group'.$store] =$group;

            $_SESSION['gender'.$store] =$group;

            $_SESSION['weight'.$store] = $_POST['weight'.$p];

            $_SESSION['player'.$store] = $players[$_POST['player'.$p]]['name']."/".$players[$_POST['player'.$p]]['name_chi'];;

            $_SESSION['name'.$store] = $players[$_POST['player'.$p]]['name'];

            $_SESSION['name_chi'.$store] = $players[$_POST['player'.$p]]['name_chi'];
            
            $_SESSION['active_member'.$store] = $_POST['status'.$p];

            $_SESSION['insert'.$store] = '"'.$_SESSION['competition_name'].'","'.$clubname['code'].'","'.$clubname['name'].'","'.$players[$_POST['player'.$p]]['name'].'","'.$players[$_POST['player'.$p]]['name_chi'].'","'.$group.'","","'.$_POST["weight".$p].'","Athlete","'.date("F j, Y, g:i a").'"';
            
            $store++;
      }

      } //end if null check

  }  //end for loop
    
    if ($store==1){
        //no player selected
        echo '<div class="alert">請選擇有效的參賽者  No valid player selected</div>';
           
    }
    else if (($maleplayer>0) &&($maleplayer<5))
    {
        //not enough male team player
         echo '<div class="alert">[男子組] 請選擇5位有效的參賽者 Not enough valid players selected for the Male team. 5 valid players are needed</div>';
    }
    else if (($femaleplayer>0) &&($femaleplayer<5))
    {
        //not enough female team player
         echo '<div class="alert">[女子組] 請選擇5位有效的參賽者 Not enough players selected for the Female team. 5 valid players are needed</div>';
    }
    else if (($masterplayer>0) &&($masterplayer<3))
    {
        //not enough female team player
         echo '<div class="alert">[先進組] 請選擇3位有效的參賽者Not enough players selected for the Masters team. 3 valid players are needed</div>';
    }
    else if ($oktosubmit==true) {
        $_SESSION['store'] = $store;

        $_SESSION['pay'] = ceil($maleplayer/$MAX_MALE_PLAYER) + ceil($femaleplayer/$MAX_FEMALE_PLAYER) + ceil($masterplayer/$MAX_MASTER_PLAYER);

        $_SESSION['fee'] = $FEE;

        $_SESSION['category'] = "local";


        if ($DEBUG) {
		    for ($a = 1; $a < $_SESSION['store']; $a++)
    		{
    		    echo "<br>";
	    		echo $_SESSION['player'.$a].":".$_SESSION['name'.$a].":".$_SESSION['group'.$a].":".$_SESSION['gender'.$a].":".$_SESSION['weight'.$a];
		    	echo "<br>";
		    }
		    
		     echo "<br>SESSION Pay:";
		     echo $_SESSION['pay'];
		}
		

        echo '<meta http-equiv=REFRESH CONTENT=1;url=confirm_join_member.php>';
    }
}

} catch(PDOException $e) {
    if($DEBUG) {
        die('Error: ' . $e->getMessage());
    } else {
        die('Database error occurred. Please try again later.');
    }
}


?>


 <script type="text/javascript">
 
 function setMemberStatus(chosen,id) {
    
    
    var js_array = <?php echo json_encode($players); ?>;

    var playerObject =  js_array[chosen];
    
    //**************** Debug pop ups ***************
    //alert(playerObject.name);
    //alert(playerObject.active_member);
   

    document.getElementById(id).value = playerObject.active_member;
}
</script>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>



 
