<?php

session_start();

$title = "Member listing";

include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

if (!$_SESSION['admin'])
{
	echo '<meta http-equiv=REFRESH CONTENT=1;url=/index.php>';
}

$hasClubCriteria = false;
$hasRoleCriteria = false;

if(isset($_GET["clubcode"]))
{
    $_SESSION['clubcode'] = $_GET["clubcode"];
    $hasClubCriteria = true;
}

if(isset($_GET["role"]))
{
    $_SESSION['role'] = $_GET["role"];
    $hasRoleCriteria = true;
}


?>
<title>Member Listing</title>

<script type='text/javascript'>


$(document).ready(function() {
    $('#example').DataTable({
        "lengthMenu": [ [ 20,50,100,200, -1], [20,50,100,200, "All"] ],
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
            {
                extend: 'excelHtml5',
                title: 'Data export',
                text: "Excel for Draw"
            },



        ]
    });
} );
</script>


<a href="<?php echo $_SERVER['HTTP_REFERER'] ?>">Back</a>
<a href="dashboard.php"> | Dashboard</a>
<table id="example" class="table table-hover table-condensed table-striped table-responsive display responsive nowrap" style="width:100%">
	

    <thead>
	
<tr>
        <th>會<br>Club</th>
        <th>中文名字<br>Chinese Name</th>
		<th>英文名字<br>English Name</th>
		<th>性別<br>Gender</th>
		<th>出生日期<br>Birthday</th>
        <th>年齡<br>Age</th>
		<th>地址<br>Address</th>
		<th>電郵<br>Email</th>
		<th>電話<br>Phone</th>
		<th>Role<br>Role</th>
		<th>更新日期<br>Joined_date</th>
        <th>個人會員<br>Active Member</th>

</tr>

</thead>

<tbody>
   
<?php


//Construct query with criteria
//$queryMain="select club_name, name_chi, name, gender, birthday, address, email, phone, category, date as joined_date, (CASE WHEN EXISTS(SELECT NULL FROM ranking WHERE participants_local.id=ranking.participant_id) THEN 'Y' ELSE 'N' END )AS ranking from participants_local where 1=1";
$queryMain="select club_name, name_chi, name, gender, birthday, address, email, phone, category, date as joined_date, active_member from participants_local where 1=1";
$clubCriteria=" and club=?";
$roleCriteria=" and category=?";
$orderClause=" order by name_chi";

$myQuery= $queryMain;
if ($hasClubCriteria) {
    $myQuery= $myQuery.$clubCriteria;

}
if ($hasRoleCriteria) {
    $myQuery= $myQuery.$roleCriteria;

}

//add in sorting order
$myQuery= $myQuery.$orderClause;

#debug statement
#echo "DEBUG::::::::::::::::::::".$myQuery;

$stmt = $pdo->prepare($myQuery);

if (($hasClubCriteria) && ($hasRoleCriteria)) {
    #echo "DEBUG::::::::::::::::::::in both criteria";
    $stmt->execute([$_SESSION['clubcode'],$_SESSION['role']]);
} else if ($hasRoleCriteria) {
    #echo "DEBUG::::::::::::::::::::in role criteria";
    $stmt->execute([$_SESSION['role']]);
} else if ($hasClubCriteria) {
    #echo "DEBUG::::::::::::::::::::in role criteria";
    $stmt->execute([$_SESSION['clubcode']]);
} else {
    $stmt->execute();
}


while ($comp = $stmt->fetch(PDO::FETCH_ASSOC)) {


?>

<tr>
    <td><?php echo $comp['club_name'] ?></td>
    <td><?php echo $comp['name_chi'] ?></td>
    <td><?php echo ($comp['name'])?> </td>
    <td><?php echo ($comp['gender']) ?></td>
    <td><?php echo ($comp['birthday']) ?></td>

    <?php
    $age = date_diff(date_create($comp['birthday']), date_create('now'))->y; ?>

    <td><?php echo $age ?> </td>
    <td><?php	 echo ($comp['address']) ?></td>
    <td><?php echo ($comp['email']) ?></td>
    <td><?php echo ($comp['phone']) ?></td>
    <td><?php	 echo ($comp['category']) ?></td>
    <td><?php	 echo ($comp['joined_date']) ?></td>
    <td><?php	 echo ($comp['active_member']) ?></td>

</tr>

<?php } ?>

        
</tbody>

<tfoot>

<tr>
        <th>會<br>Club</th>
		<th>中文名字<br>Chinese Name</th>
		<th>英文名字<br>English Name</th>
		<th>性別<br>Gender</th>
		<th>出生日期<br>Birthday</th>
        <th>年齡<br>Age</th>
		<th>地址<br>Address</th>
		<th>電郵<br>Email</th>
		<th>電話<br>Phone</th>
		<th>Role<br>Role</th>
		<th>更新日期<br>Joined_date</th>
        <th>個人會員<br>Active Member</th>
</tr>
</tfoot>
</table>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>

