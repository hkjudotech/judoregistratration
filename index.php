<?php
session_start();
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>登入 Login</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link href="css/jquery-ui-1.8.11.custom.css" type="text/css" rel="Stylesheet" />
	<link rel="stylesheet" href="css/main.css" type="text/css">

    <style type="text/css">
	body
	{
		background-image: url(images/page-bg-gradient-tile-long.jpg);
		color: #FFF;
		padding-top: 15%;
    }     
    #main
    {
        width:969px;
    }
    #content
    {
        height:90%;
        width:100%;
    }
    </style>
</head>
<body>
<h3 align="center"><img src="logo_long.png" align=center"></img></h3>

<div align="center">
  <table border="0">
    <form method="POST" action="connect.php">
		
      <tr><td>登入名稱 Login Name</td><td>:</td><td><input type="text" class="form-control" name="username" size="20"></td></tr>
	  <tr height = "12px"></tr>
      <tr><td>登入密碼 Login Password</td><td>:</td><td><input type="password" class="form-control" name="password" size="20"></td></tr>
	  <tr height = "12px"></tr>
      <tr><td>&nbsp;</td><td>&nbsp;</td><td><input type="submit" class="btn btn-primary" value="Login"></td></tr>
	  </form>
  </table>

</div>
<!--div align="center" ><p>
<br>
<a href="/competition/student23.php" style="color:yellow;font-size:18px;">Register for Hong Kong Student Judo Championships 2023 | 報名 2023年度香港校際柔道錦標賽</div-->

<!--div align="center"><p>
<br>
<a href="competition/independent.php">Competition Registration for Associated Members | 獨立會員比賽報名</div>
-->

<!--div align="center"><p>
<br>
<h3>由於 PayPal付款功能仍在搶修中，比賽報名功能目前暫停使用。不便之處，敬請見諒。請您於數日後再嘗試報名，感謝您的耐心與支持
<p><br>
Due to ongoing repairs to the PayPal payment feature, the competition registration function is currently suspended. <br>We apologize for any inconvenience caused. Please try registering again in a few days. Thank you for your patience and support.
</h3>
</div-->
</body>
</html>

