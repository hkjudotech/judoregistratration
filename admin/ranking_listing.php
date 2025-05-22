<?php


session_start();


$title = "Ranking listing";

include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

if (!$_SESSION['admin'])

{

	echo '<meta http-equiv=REFRESH CONTENT=1;url=/index.php>';

}


?>
<title>Ranking Listing</title>

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
        <th><br>Club</th>
        <th>中文名字<br>Chinese Name</th>
		<th>英文名字<br>English Name</th>
		<th>性別<br>Gender</th>
		<th><br>Birthday</th>
        <th><br>Age</th>
		<th>Address<br>Address</th>
		<th>Email<br>Email</th>
		<th>電話<br>Phone</th>
		<th>Joined Date<br>Joined Date</th>
        <th>ID<br>ID</th>
        

</tr>

</thead>

<tbody>
   
<?php


//construct query
$myQuery="select l.id, l.name_chi, l.name, l.gender, l.birthday, l.address, l.email, l.phone, r.join_date, r.club from ranking r , participants_local l where r.participant_id = l.id";

#debug statement
#echo "DEBUG::::::::::::::::::::".$myQuery;

$stmt = $pdo->prepare($myQuery);
$stmt->execute();

while ($comp = $stmt->fetch(PDO::FETCH_ASSOC)) {


?>

<tr>
    <td><?php echo $comp['club'] ?></td>
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
    <td><?php	 echo ($comp['join_date']) ?></td>
    <td><?php	 echo ($comp['id']) ?></td>
        
</tr>

<?php }?>

        
</tbody>

<tfoot>

<tr>
<th><br>Club</th>
        <th>中文名字<br>Chinese Name</th>
		<th>英文名字<br>English Name</th>
		<th>性別<br>Gender</th>
		<th><br>Birthday</th>
        <th><br>Age</th>
		<th>Address<br>Address</th>
		<th>Email<br>Email</th>
		<th>電話<br>Phone</th>
		<th>Joined Date<br>Joined Date</th>
        <th>ID<br>ID</th>
</tr>
</tfoot>
</table>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>

