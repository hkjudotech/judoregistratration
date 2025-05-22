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
        "order": [[ 0, "desc" ]]
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
    <th>識別號碼<br>ID</th>
    <th>付款日期<br>Payment Date</th>
    <th>付款人電郵<br>Payer Email</th>
    <th>系統電子郵件<br>Email in System</th>
	<th>總數<br>Number of Items</th>
	<th>支付總額 (港元)<br>Total Paid (HK$)</th></tr>

</tr>

</thead>

<tbody>
   
<?php


//construct query
$myQuery="SELECT id, date as payment_date, payer_email, num_of_items, total FROM payment ORDER BY id desc";

#debug statement
#echo "DEBUG::::::::::::::::::::".$myQuery;

$stmt = $pdo->prepare($myQuery);
$stmt->execute();

while ($comp = $stmt->fetch(PDO::FETCH_ASSOC)) {
   

?>

<tr>
    <td><?php echo $comp['id'] ?></td>
    <td><?php echo $comp['payment_date'] ?></td>
    <td><?php echo $comp['payer_email'] ?></td>
    <td><?php  echo "<a href=club_listing.php?email=".$comp['payer_email']." target='blank'>"." Member Details</a>" ?></td>
    <td><?php echo ($comp['num_of_items'])?> </td>
    <td><?php echo ($comp['total']) ?></td>
    
</tr>

<?php }?>

        
</tbody>

<tfoot>

<tr>
        <th>識別號碼<br>ID</th>
        <th>付款日期<br>Payment Date</th>
        <th>付款人電郵<br>Payer Email</th>
        <th>系統電子郵件<br>Email in System</th>
		<th>總數<br>Number of Items</th>
		<th>支付總額 (港元)<br>Total Paid (HK$)</th></tr>
</tfoot>
</table>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>

