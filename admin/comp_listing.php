<?php

session_start();

$debug="N";

$title = "Event Participants";

include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

if (!$_SESSION['admin'])
{
	echo '<meta http-equiv=REFRESH CONTENT=1;url=/index.php>';
}

if(isset($_GET["short"]))
{
	$_SESSION['short'] = $_GET["short"];
}

#debug statement

if ($debug=='Y'){
echo "DEBUG::::::::::::::::::::".$_SESSION['short'] ;
}
?>
<title>Event Participants</title>

<script type='text/javascript'>


$(document).ready(function() {
    $('#example').DataTable({
      
        "lengthMenu": [ [ 50,100,200, -1], [50,100,200, "All"] ],
        colReorder: true,
        responsive: true,
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                title: 'Data export'
            },
            {
                extend: 'csvHtml5',
                title: 'Data export'
            },
            {
                extend: 'excelHtml5',
                title: 'Data export'
            },
            /*{
                extend: 'pdfHtml5',
                title: 'Data export'
            }*/
        ]
    });
} );
</script>

<?php

//Select event details
$stmt = $pdo->prepare('select name, name_eng, date, deadline from competition where short = ?');
$stmt->execute([$_SESSION['short']]);
$comp = $stmt->fetch(PDO::FETCH_ASSOC);



echo "<h5>比賽: ".$comp['name']." ".$comp['name_eng']."</h5>";
echo "<h5>比賽日期: ".$comp['date']."</h5>";
echo "<h5>截止日期: ".$comp['deadline']."</h5>";

//get all club info
$stmt = $pdo->prepare('select code, name, name_chi, rep_name, rep_phone, rep_email from club where category="local"');
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    
    
    if ($debug=='Y'){
        echo "DEBUG::::::::::::::::::::In Club Details:::::::".$row;
    }
    
    
    $club[$row['code']]  =$row;
}

//get all school info
$stmt = $pdo->prepare('select code, english_name, chinese_name from school');
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    
    
    if ($debug=='Y'){
        echo "DEBUG::::::::::::::::::::In School Details:::::::".$row['chinese_name'];
    }
    
    
    $school[$row['code']]  =$row;
}


?>
<a href="<?php echo $_SERVER['HTTP_REFERER'] ?>">Back</a>
<a href="dashboard.php"> | Dashboard</a>
<table id="example" class="table table-hover table-condensed table-striped table-responsive">



    <thead>
	
<tr>
		<th>日期<br>Registered Date</th>

		<th>屬會Code<br>Code</th>

		<th>屬會名稱<br>Club Name</th>

		<th>英文名<br>Name in English</th>

		<th>中文名<br>Name in Chinese</th>

		<th>性別<br>Gender</th>

		<th>組別<br>Division</th>

		<!-- to be added in table below -->
		<th>體重<br>Weight</th>
		
		<th>學校<br>School</th>

		<th>身份<br>Role</th>

		<th>付費?<br>Paid</th>

		<!-- th>報名者電郵<br>Applicant Email</th>

		<th>報名者電話<br>Applicant Phone</th-->
		
		<th>負責人姓名<br>Club Rep Name</th>

		<th>負責人電郵<br>Club Rep Email</th>

		<th>負責人電話<br>Club Rep Phone</th>
		
</tr>


<tbody>
   
<?php

//Select participants and their details from local (local events) and participants_local (member) table
/*$stmt = $pdo->prepare('SELECT distinct event_participant.competition, event_participant.code, event_participant.country, event_participant.name, event_participant.name_chi, 
                        event_participant.gender, event_participant.division, event_participant.weight, event_participant.identity, event_participant.date, event_participant.payment,
                        member.email, member.phone  FROM local event_participant, participants_local member 
                        WHERE event_participant.name = member.name 
                        AND event_participant.country = member.club_name
                        and event_participant.competition = ? ORDER BY event_participant.id');*/

$stmt = $pdo->prepare('SELECT distinct competition, code, country, name, name_chi, gender, division, weight, identity, date, payment, school FROM local  WHERE competition = ?');
$stmt->execute([$_SESSION['short']]);

if ($debug=='Y'){
    echo "DEBUG:::::::::::::::::::::::::::".$_SESSION['short'];
}


while ($comp = $stmt->fetch(PDO::FETCH_ASSOC)) {
   
    if ($debug=='Y'){
        echo "DEBUG::::::::::::::::::::In comp Details:::::::".$comp;
    }

?>

 <tr>

<td><?php 
echo $comp['date']

?></td>

<td><?php echo ($comp['code'])?> </td>

<td>

<?php echo ($comp['country']) ?>

</td>

<td>

<?php echo ($comp['name']) ?>

</td>

<td>

<?php echo ($comp['name_chi']) ?>
</td>

<td>

<?php	 echo ($comp['gender']) ?>

</td>

<td>

<?php	 echo ($comp['division']) ?>

</td>


<td>

<?php	 echo ($comp['weight']) ?>

</td>


<td>

<?php	 if ($comp['identity']=="Athlete") {
    echo "[".$comp['school']."]".($school[$comp['school']]['english_name']) ;
    }
?>

</td>

<td>

<?php	 echo ($comp['identity']) ?>

</td>


<td>

<?php	 echo ($comp['payment']) ?>

</td>


<!--td>

<?php	// echo ($comp['email']) ?>

</td>


<td>

<?php//	 echo ($comp['phone']) ?>

</td-->

<td>

<?php	 echo ($club[$comp['code']]['rep_name']) ?>

</td>


<td>

<?php	 echo ($club[$comp['code']]['rep_email'])  ?>

</td>


<td>

<?php	echo ($club[$comp['code']]['rep_phone']) ?>

</td>


</tr>

<?php } ?>

        
</tbody>

<tfoot>

    <tr>
        <th>日期<br>Event Date</th>
        <th>屬會Code<br>Code</th>

        <th>屬會名稱<br>Club Name</th>

        <th>英文名<br>Name in English</th>

        <th>中文名<br>Name in Chinese</th>

        <th>性別<br>Gender</th>

        <th>組別<br>Division</th>

        <!-- to be added in table below -->
        <th>體重<br>Weight</th>
        
        <th>學校<br>School</th>

        <th>身份<br>Role</th>

        <th>付費?<br>Paid</th>

        <!--th>Email<br>Email</th>

        <th>Phone<br>Phone</th-->
        
		<th>負責人姓名<br>Club Rep Name</th>

		<th>負責人電郵<br>Club Rep Email</th>

		<th>負責人電話<br>Club Rep Phone</th>
    </tr>
</tfoot>
</table>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>

