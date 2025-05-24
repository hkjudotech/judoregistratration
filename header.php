<?php
// At the top of header.php
include_once($_SERVER['DOCUMENT_ROOT']."/common/function.php");
require_once($_SERVER['DOCUMENT_ROOT']."/common/PhpMailer/Exception.php");
require_once($_SERVER['DOCUMENT_ROOT']."/common/PhpMailer/PHPMailer.php");
require_once($_SERVER['DOCUMENT_ROOT']."/common/PhpMailer/SMTP.php");

// Database connection - Move this before any SQL queries
$dbuser = "judonorg_reg";
$dbpass = "1024judo";
$dbname = "judonorg_judo";
$dbhost = "localhost";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Set character encoding
mysqli_set_charset($conn, 'utf8mb4');
// Or alternatively:
/*
mysqli_query($conn, "SET NAMES 'UTF8'");
mysqli_query($conn, "SET CHARACTER_SET_CLIENT='utf8'");
mysqli_query($conn, "SET CHARACTER_SET_RESULTS='utf8'");
*/

// Session check
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('Location: /index.php');
    exit();
}

$username = $_SESSION['username'];

// PayPal configuration
define("USE_SANDBOX", 0);
$paypal_url = USE_SANDBOX ? 
    "https://www.sandbox.paypal.com/cgi-bin/webscr" : 
    "https://www.paypal.com/cgi-bin/webscr";
$ac = USE_SANDBOX ? 
    "hkjudo-facilitator@outlook.com" : 
    "hkjudo@outlook.com";


// Get club information
$stmt = $conn->prepare("SELECT id, name, name_chi, code FROM club WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

// Bind result variables
$stmt->bind_result($id, $name, $name_chi, $code);

// Fetch the result
$stmt->fetch();

// Create associative array to match original structure
$name3 = array(
    'id' => $id,
    'name' => $name,
    'name_chi' => $name_chi,
    'code' => $code
);

$stmt->close();

// Constants
date_default_timezone_set('Asia/Hong_Kong');
$date = date('Y-m-d');
$dateyear = date('Y');
$_SESSION['doclink'] = "http://www.hkjudo.org/local/" . $dateyear . "/";
$_SESSION['dashboard'] = "admin/dashboard.php";
?>

<!DOCTYPE html>
<html>
    
    <body>
		<div class = "row row-block">

			<h3><?php echo $name3['name_chi']; ?> <?php echo $name3['name'];?></h3>

		</div>



<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title><?php echo $title;?></title>

	<!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- jQuery UI CSS -->
    
    <!--link href="/css/jquery-ui-1.8.11.custom.css" type="text/css" rel="Stylesheet" /-->	
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
      
      <!-- Your custom CSS -->
	<link href="/css/main.css" type="text/css" rel="Stylesheet" />   
	
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
	
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap.min.css">
	
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css"/>

	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/colreorder/1.4.1/css/colReorder.dataTables.min.css"/>
	
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/scroller/1.4.4/css/scroller.dataTables.min.css"/>
	
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.5/css/select.dataTables.min.css"/>
	
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css"/>
	
 
	
	<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

	<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>

	<script src="http://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>

	<script src="js/signupForm.js"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

	<script type="text/javascript" charset="utf8mb4" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>

	<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>

	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
	
	<script type="text/javascript" src="https://cdn.datatables.net/colreorder/1.4.1/js/dataTables.colReorder.min.js"></script>
	
	<script type="text/javascript" src="https://cdn.datatables.net/scroller/1.4.4/js/dataTables.scroller.min.js"></script>

	<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js"></script>

	<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>


    

</head>

<body>

	

	 <nav class="navbar navbar-default">

        <div class="container-fluid">

          <div class="navbar-header">

            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">

			  <span class="sr-only">Toggle navigation</span>

			  <span class="icon-bar"></span>

			  <span class="icon-bar"></span>

			  <span class="icon-bar"></span>

            </button>

            <a class="navbar-brand" href="#"><img src="/logo_long.png" height="48px"></img></a>

          </div>

          <div id="navbar" class="navbar-collapse collapse">

            <ul class="nav navbar-nav">

				<li><a href="/front.php">主頁<br>Home</a></li>

				<li><a href="/add.php">註冊參加者<br>Participants Registration</a></li>

				<li><a href="/join.php">比賽報名<br>Entries</a></li>

				<li><a href="/check.php">現有參加者<br>Current participants</a></li>

				<!--li><a href="/ranking_register.php">登記柔道運動員排行榜<br>Ranking System Registration</a></li-->

			<?php 
			
			
if ($_SESSION['admin'])

{

	echo '	<li><a href="/admin/dashboard.php">管理儀表板<br>Admin Dashboard</a></li>';

}


			?>
            

            </ul>
<br><br><font color="white" align="right">  For Tech Issue: Please email <a href="mailto:hkjudotech@gmail.com"  style="color: yellow">support</a></font>
          </div><!--/.nav-collapse -->

        </div><!--/.container-fluid -->

      </nav>





	<div id="content">

		<div class = "row row-block">

			<h3><?php echo $name3['name_chi']; ?> <?echo $name3['name'];?></h3>

		</div>


