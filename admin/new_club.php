<?php


session_start();


$title = "New Club listing";

include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

if (!$_SESSION['admin'])

{

	echo '<meta http-equiv=REFRESH CONTENT=1;url=/index.php>';

}


$hasEmailCriteria = false;

if(isset($_GET["email"]))
{

    $_SESSION['email'] = $_GET["email"];
    $hasEmailCriteria = true;
  
}


?>
<title>Club Listing</title>

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

        ]
    });
} );
</script>


<a href="<?php echo $_SERVER['HTTP_REFERER'] ?>">Back</a>
<a href="dashboard.php"> | Dashboard</a>
<table id="example" class="table table-hover table-condensed table-striped table-responsive">
	

    <thead>
	
<tr>
        <th>id<br>ID</th>
        
		<th>中文名字<br>Chinese Name</th>

		<th>英文名字<br>English Name</th>

		<th>代表姓名<br>Rep Name</th>
		
		<th>練習場地<br>Practice Venue</th>
    
        <th>練習時間<br>Practice Time</th>
    
		<th>遞交日期<br>Submitted Date</th>
		
		<th>代表電話<br>Rep Phone</th>

		<th>代表電郵<br>Rep Email</th>
        
        <th>代表地址<br>Rep Address</th>

		<th>商業登記/社團註冊<br>BR</th>
		
</tr>

</thead>

<tbody>
   
<?php
$queryMain="select id,ref_id , name_chi, name, rep_name, rep_address,rep_phone,date, rep_email,type, address, username, code, br, practice_place,  practice_time from club where  category  = ? order by id desc";
$emailCriteria=" and rep_email=?";

$myQuery= $queryMain;
if ($hasEmailCriteria) {
    $myQuery= $myQuery.$emailCriteria;

}


//Select club details
$stmt = $pdo->prepare($myQuery);

if ($hasEmailCriteria) {
    #echo "DEBUG::::::::::::::::::::in Email criteria";
    $stmt->execute(["",$_SESSION['email']]);
} else  {
    #echo "DEBUG::::::::::::::::::::in else";
    $stmt->execute(['']);
}


while ($comp = $stmt->fetch(PDO::FETCH_ASSOC)) {


?>

<tr>
    <td><?php	 echo ($comp['id']) ?></td>
    <td><?php echo $comp['name_chi'] ?></td>
    <td><?php echo ($comp['name'])?> </td>
    <td><?php echo ($comp['rep_name']) ?></td>
    <td><?php echo ($comp['practice_place']) ?></td>
    <td><?php echo ($comp['practice_time']) ?></td>
    <td><?php	 echo ($comp['date']) ?></td>
    <td><?php echo ($comp['rep_phone']) ?></td>
    <td><?php echo ($comp['rep_email']) ?></td>
    <td><?php	 echo ($comp['address']) ?></td>
    <td><?php	 echo ($comp['br']) ?></td>
    
    

</tr>

<?php }?>

        
</tbody>

<tfoot>

    <tr>
    
    <th>id<br>ID</th>

    <th>中文名字<br>Chinese Name</th>

    <th>英文名字<br>English Name</th>

    <th>代表姓名<br>Rep Name</th>

    <th>練習場地<br>Practice Venue</th>
    
    <th>練習時間<br>Practice Time</th>
    
	<th>遞交日期<br>Submitted Date</th>
	
    <th>代表電話<br>Rep Phone</th>

    <th>代表電郵<br>Rep Email</th>

    <th>代表地址<br>Rep Address</th>

    <th>商業登記/社團註冊<br>BR</th>
    
    
    
</tr>
</tfoot>
</table>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>

