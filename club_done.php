<?php
session_start();
?>
<html>
<head>
					
    <title>登記完畢</title>
		<meta http-equiv="Content-Type" content="text/html; charset=BIG5" />
    <link href="css/jquery-ui-1.8.11.custom.css" type="text/css" rel="Stylesheet" />
    <style type="text/css">
    
    body 
    {
        background-image: url(images/page-bg-gradient-tile.jpg);
        background-repeat:repeat-x;
        margin: 0 0 2em;
        padding: 0;
        font: 70%/130% Tahoma,Arial,Helvetica,sans-serif;
        color: black; 
    }
    
    #id 
    {
        background-image:url(images/page-bg.jpg);
        background-position:50% 0%;
        background-repeat:no-repeat;
        width:100%
    }
    
    #main
    {
        width:969px;
        position:relative;
        margin: 0 auto;
        
        
    }
    
    #content
    {
        background-color:White;
        border-radius:3px;
        height:200px;
        width:100%;
        margin-top:7px;
    }
    
    #navi
    {
        width:100%;
        padding-top:10px;
        height:30px;
    }
    
    ul
    {
        list-style:none;    
    }
    
    #menu 
    {
        list-style: none;
        padding: 0 0 0 7px;
        display:block;
        overflow: hidden;
    }
    
    #menu li
    {
        margin-left:5px;
        float:left;
        text-align:center;
              
    }
    
    a
    {
        text-decoration:none;
    }
    
    #menu li a
    {
        display:inline-block;
        width:200px;
        padding: 7px 0 5px 0;
        cursor:pointer;
        color:#09376B;
        background-image:url(images/tab.png);
        text-decoration:none;
    }
    
    #menu li a:hover
    {
         background-image:url(images/tab_act_hover.gif);
    }
    
    #accordian li a
    {
        display:inline-block;
        padding: 2px 5px;
        border-radius:2px;
        font-weight:bold;
    }
    
    #accordian ul
    {
        padding: 0;
        margin-left: 0;
    }
    
    #accordian li a:hover
    {
        background-color:#85EDC3;
    }
    
    #accordian-description
    {
        width:850px;
        margin-left:20px;
		charset:utf-8
		height: 250px;
    }
    
    #accordian-description h3
    {
        font-size:15px;
        font-weight:bold;
		
    }
    
    #content-table td
    {
        vertical-align:top;
    }
    </style>
</head>
<body>
<br><br><br><br><br><br><br><br><br>
  <div id="main" >
    <div id="content">
        <table id="content-table" border="0" style="margin:0px 40px;padding: 20px 0px;">
                 <tr>
                    <td>
                    <div id="accordian-description">
                        <h3>
                            <a href="#">
							</a></h3><div align="center"><p>	<br>
							<b>閣下之登記已經完成，本會將盡快透過電郵與閣下聯絡。</b>
							<p><b>Your registration request has been received. Our association will contact you via email shortly</b>
						</p>
                       </p>
                        </div>
                        
                    </div>
                </td>
           </tr>
        </table>
    </div>
 </div>
 <meta http-equiv=REFRESH CONTENT=5;url=front.php>
 <script src="js/js/jquery-1.5.1.min.js" type="text/javascript"></script>
 <script src="js/js/jquery-ui-1.8.11.custom.min.js" type="text/javascript"></script>
 <script type="text/javascript">

     $(document).ready(function () {

         $("#accordian").accordion();
         $("#accordian-description").accordion();
     });

</script>
</body>
</html>
