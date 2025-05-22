<?php

session_start();


$title = "Competition Status";

include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

if(isset($_GET["short"]))
{
	$_SESSION['short'] = $_GET["short"];
}


if(isset($_GET["competition_chi"]))
{
	$_SESSION['competition_chi'] = $_GET["competition_chi"];
}

if(isset($_GET["competition_eng"]))
{
	$_SESSION['competition_eng'] = $_GET["competition_eng"];
}

#debug statement
//echo "DEBUG::::::::::::::::::::".$_SESSION['competition_eng'];


?>
<title>Competition Status</title>

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


	<div class ="row text-center" >
	    <h3>
	         <?php
	            echo $_SESSION['competition_chi'];
	            echo "<br>";
	            echo $_SESSION['competition_eng']; 
	         
	         ?> 
	        <p>最新情況
		Competition Entries Status</h3>[<span id="datetime"></span>]

		</div>
		
		

	<div class ="row text-center" >
	    <h6> 如級別不在以下列表，則暫尚未有運動員報名此級別<br>
If the category is not found below, the number of entry is 0
	    </h6>
		</div>
		
<a href="<?php echo $_SERVER['HTTP_REFERER'] ?>">Back</a>
<!--a href="dashboard.php"> | Dashboard</a-->
<table id="example" class="table table-hover table-condensed table-striped table-responsive">
	

    <thead>
	
<tr>
    <th>性別<br>Gender</th>
    
    <th>級別<br>Category</th>
    
    <th>體重<br>Weight</th>

    <th>已報名人數<br>No. of Participants</th>	

</tr>

</thead>

<tbody>
   
<?php


$sql = 'SELECT 
    gender, 
    division, 
    weight, 
    COUNT(DISTINCT name) as quota 
FROM local 
WHERE identity = ? 
    AND competition = ?
GROUP BY gender, division, weight 
ORDER BY gender, division, weight';



$stmt = $pdo->prepare($sql);
$stmt->execute(['Athlete', $_SESSION['short']]);


#debug statement
//echo "DEBUG::::::::::::::::::::".[$_SESSION['competition_chi']];
//echo "SQL Query: " . $sql . "<br>";
//echo "Parameters: identity = 'Athlete', competition = '" . $_SESSION['short'] . "'<br>";

// Fetch and display results
while ($comp = $stmt->fetch(PDO::FETCH_ASSOC)) {


?>

<tr>
    <td><?php 
    
    echo ($comp['gender'])?></td>
    
    
    <td><?php 
    echo ($comp['division'])?></td>
    
    <td><?php 
    echo ($comp['weight'])?></td>
    <td><?php 
    echo $comp['quota'] ?></td>

    
</tr>

<?php

}

?>

        
</tbody>


</table>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>

<script>
    var dt = new Date();
    document.getElementById("datetime").innerHTML = dt.toLocaleString();
</script>
