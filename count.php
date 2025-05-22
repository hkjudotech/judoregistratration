<?php


// First query
$query = "SELECT COUNT(*) FROM participants_" . $_SESSION['category'] . " WHERE club = '" . mysqli_real_escape_string($conn, $username) . "'";
$result = mysqli_query($conn, $query) or die('Error! ' . mysqli_error($conn));
$row = mysqli_fetch_array($result);

?>
<div class = "row row-block">
	<h4>
		<?php echo "已註冊會員:".$row['COUNT(*)']; ?><br>
		<?php echo "Current registered participants: ".$row['COUNT(*)'];?><br><br>
	</h4>
</div>