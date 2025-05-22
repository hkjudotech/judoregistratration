<?php
	include_once($_SERVER['DOCUMENT_ROOT']."/common/function.php");
?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $title;?></title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link href="/css/jquery-ui-1.8.11.custom.css" type="text/css" rel="Stylesheet" />	
    <link href="/css/main.css" type="text/css" rel="Stylesheet" />   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
	<script src="http://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
	<script src="js/signupForm.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</head>
<body>
	<div id="content">
		<?php
		//Connect to database
		try {
        $pdo = new PDO(
        'mysql:host=localhost;dbname=judonorg_judo;charset=utf8',
        'judonorg_reg',
        '1024judo',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
            ]
            );
        } catch (PDOException $e) {
            die('Error! login: ' . $e->getMessage());
        }

        $username = $_SESSION['username'];
		?>
