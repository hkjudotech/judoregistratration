<?php


session_start();


$title = "School listing";

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
           <th>學校<br>ID</th>
     
		<th>英文名字<br>English Name</th>
     
           <th>中文名字<br>Chinese Name</th>

</tr>

</thead>

<tbody>
   
<?php


//construct query
$myQuery="select s.code, s.chinese_name, s.english_name from school s order by english_name";

#debug statement
#echo "DEBUG::::::::::::::::::::".$myQuery;

$stmt = $pdo->prepare($myQuery);
$stmt->execute();

while ($comp = $stmt->fetch(PDO::FETCH_ASSOC)) {


?>

<tr>
    <td><?php echo $comp['code'] ?></td>
        <td><?php echo ($comp['english_name'])?> </td>
    <td style=' width="40"'><? echo $comp['chinese_name'] ?></td>

        
</tr>

<?php }?>

        
</tbody>

<tfoot>

<tr>

        <th>學校<br>Code</th>
      
		<th>英文名字<br>English Name</th>
	
      <th>中文名字<br>Chinese Name</th>
</tr>
</tfoot>
</table>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>

