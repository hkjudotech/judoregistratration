<?php
session_start();
$title = "現有參加者 Current Participants";
include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

$DEBUG=false;

if ($DEBUG){
    var_dump($item);
}
//funciton to change
function change($ch, $id)
{
  global $pdo;
    if($_POST['name'.$ch] != "" && $_POST['birthday'.$ch] != "" && $_POST['hkid'.$ch] != "") {
        $sql = "UPDATE participants_".$_SESSION['category']." SET 
                name = ?, name_chi = ?, gender = ?, birthday = ?, 
                hkid = ?, email = ?, phone = ?, category = ? 
                WHERE id = ?";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_POST['name'.$ch],
                $_POST['name_chi'.$ch],
                $_POST['gender'.$ch],
                $_POST['birthday'.$ch],
                $_POST['hkid'.$ch],
                $_POST['email'.$ch],
                $_POST['phone'.$ch],
                $_POST['cat'.$ch],
                $id
            ]);
            echo "Data changed successfully";
            echo '<meta http-equiv=REFRESH CONTENT=1;url=check.php>';
        } catch(PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    } else {
        echo "Please fill in all the necessary fields";
    }
}

//function to delete
function delete($del)
{
  global $pdo;
    try {
        $sql = "DELETE FROM participants_".$_SESSION['category']." WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$del]);
        echo " Data deleted";
        echo '<meta http-equiv=REFRESH CONTENT=1;url=check.php>';
    } catch(PDOException $e) {
        die('Error: ' . $e->getMessage());
    }
}

//Select data from participants
try {
    $sql = 'SELECT id, name, name_chi, gender, birthday, address, hkid, 
            email, phone, category, active_member 
            FROM participants_'.$_SESSION['category'].' 
            WHERE club = ? ORDER BY name';
    $participants = $pdo->prepare($sql);
    $participants->execute([$username]);


        if ($DEBUG){
            echo "Debug:".$sql." ".$username;
      
    
        }
include_once($_SERVER['DOCUMENT_ROOT']."/common/count.php");


?>

<div class = "row row-block timetable">
	<form class="form-horizontal" id="changeForm" method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ; ?>">
		<div class = "row">
			<div class = "col-xs-12 col-md-6">
				<div class = "row-header col-xs-3">Name</div>
				<div class = "row-header col-xs-2">Chinese Name</div>
				<div class = "row-header col-xs-2">Gender</div>
				<div class = "row-header col-xs-3">Birthday</div>
				<div class = "row-header col-xs-2">HKID</div>
			</div>
			<div class = "col-xs-12 col-md-6">
				<div class = "row-header col-xs-3">Email</div>
				<div class = "row-header col-xs-2">Phone</div>
				<div class = "row-header col-xs-3">Position</div>
				<div class = "row-header col-xs-1">Active Annual Member</div>
				<div class = "row-header col-xs-2">Change</div>
				<div class = "row-header col-xs-1">Delete</div>
				
			</div>
		</div>
	<?php
	
	
	$count = 1;
	

	 while ($part3 = $participants->fetch(PDO::FETCH_ASSOC)) {
       if ($DEBUG){
            echo "Debug:";

    
        }
        $p_id[$count] = $part3['id']; 
        
     
        
        
        ?>

		<div class = "row">
			<div class = "col-xs-12 col-md-6">
				<div class = "row-content col-xs-3">
					<input class = "form-control" type="text" name="name<?php echo $count; ?>" value="<?php echo $part3['name']; ?>">
				</div>
				<div class = "row-content col-xs-2">
					<input class = "form-control" type="text" name="name_chi<?php  echo $count; ?>" value="<?php echo $part3['name_chi']; ?>">
				</div>
				<div class = "row-content col-xs-2">
					<select class = "form-control" type = "text" name = "gender<?php echo $count; ?>">
						<option value = 'M'
						<?php
						if($part3['gender'] == "M") 
						{
							echo "Selected";
						} 
						?> >M</option>
						<option value = 'F'
						<?php
						if($part3['gender'] == "F")
						{
							echo "Selected";
						}
						?>  
						>F</option>
					</select>
				</div>
				<div class = "row-content col-xs-3">
					<input class = "form-control" type="text" name="birthday<?php echo $count; ?>" value="<?php echo $part3['birthday']; ?>">
				</div>
				<div class = "row-content col-xs-2">
					<input class = "form-control" type="text" name="hkid<?php echo $count; ?>" value="<?php echo $part3['hkid']; ?>">
				</div>
			</div>
			<div class = "col-xs-12 col-md-6">
				<div class = "row-content col-xs-3">
					<input class = "form-control" type="text" name="email<?php echo $count; ?>" value="<?php echo $part3['email']; ?>">
				</div>
				<div class = "row-content col-xs-2">
					<input class = "form-control" type="text" name="phone<?php echo $count; ?>" value="<?php echo $part3['phone']; ?>">
				</div>
				<div class = "row-content col-xs-3">
					<select class = "form-control" type = "text" name = "cat<?php echo $count; ?>">
						<option value = 'Athlete'
						<?php
						if($part3['category'] == 'Athlete')
						{
							echo " Selected";
						}
						?>
						>運動員 Athlete</option>
						<option value = 'Coach'
						<?php
						if($part3['category'] == 'Coach')
						{
							echo " Selected";
						}
						?>
						>教練 Coach</option>
						<option value = 'Referee'
						<?php
						if($part3['category'] == 'Referee')
						{
							echo " Selected";
						}
						?>
						>裁判 Referee</option>
						<option value = 'Official'
						<?php
						if($part3['category'] == 'Official')
						{
							echo " Selected";
						}
						?> 
						>工作人員 Official</option>
					</select>
				</div>
					<div class = "row-content col-xs-1">
					<input class = "form-control" type="text" readonly name="active_member<?php echo $count; ?>" value="<?php echo $part3['active_member']; ?>">
				</div>
				<div class = "row-content col-xs-2">
					<input class = "btn btn-primary" type="submit" name="change<?php echo $count; ?>" value="Change">
				</div>
				<div class = "row-content col-xs-1">
					<input class = "btn btn-primary" type="submit" name="delete<?php echo $count; ?>" value="Del">
				</div>
			</div>
		</div>
		<?php 
            $count++; 
	    }
    } catch(PDOException $e) {
        die('Error: ' . $e->getMessage());
    }    
    
    ?>
	</form>
</div>

<?php

// Change
for($i = 1; $i < $count + 1; $i++)
{
	if(isset($_POST['change'.$i]))
	{
		change($i, $p_id[$i]);
	}
}

// Delete
for($i = 1; $i < $count + 1; $i++)
{
	if(isset($_POST['delete'.$i]))
	{
		delete($p_id[$i]);
	}
}

?>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>
