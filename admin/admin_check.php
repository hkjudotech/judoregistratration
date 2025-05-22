<?php
session_start();
$title = "現有參加者 Current Participants";
include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");
if (!$_SESSION['admin'])
{
	echo '<meta http-equiv=REFRESH CONTENT=1;url=/index.php>';
}
?>
<?php
//Select data from competition
$com = 'SELECT name, short, deadline,date FROM competition WHERE type = "local" OR type = "check" ORDER BY date DESC';
$com2 = mysql_query($com) or die('Error! '. mysql_error());
$com_count = 0;
echo "<script type='text/javascript'>";
echo "comp = new Array();";
//Passing the data into both Javascript array and PHP array
while($com3 = mysql_fetch_array($com2))
{
	echo "comp[".$com_count."] = new Array(3);";
	echo "comp[".$com_count."][0] = '".$com3['name']."';";
	$comp[$com_count][0] = $com3['name'];
	echo "comp[".$com_count."][1] = '".$com3['short']."';";
	echo "comp[".$com_count."][2] = '".$com3['date']."';";
	echo "comp[".$com_count."][3] = '".$com3['deadline']."';";
	$com_count++;
}

//Collect data from DB participants and store them in Javascript array 'participants'
$part = 'SELECT local.competition, local.code, local.country, local.name, local.name_chi, local.gender, local.division, local.weight, local.identity, local.date, local.payment, participants_local.email, participants_local.phone FROM local INNER JOIN participants_local ON local.name = participants_local.name ORDER BY local.id ';
$part2 = mysql_query($part) or die('Error! ' . mysql_error());
echo "participants = new Array();";
$part_count = 0;
while($part3 = mysql_fetch_array($part2))
{
	echo "participants[".$part_count."] = new Array(11);";
	echo "participants[".$part_count."][0] = '".$part3['competition']."';";
	echo "participants[".$part_count."][1] = '".date("j-m-y", strtotime($part3['date']) + 8*60*60)."';"; //adding 8 hours to make it HK time
	echo "participants[".$part_count."][2] = '".$part3['code']."';";
	echo "participants[".$part_count."][3] = '".$part3['country']."';";
	echo "participants[".$part_count."][4] = '".$part3['name']."';";
	echo "participants[".$part_count."][5] = '".$part3['name_chi']."';";
	echo "participants[".$part_count."][6] = '".$part3['gender']."';";
	echo "participants[".$part_count."][7] = '".$part3['division']."';";
	echo "participants[".$part_count."][8] = '".$part3['weight']."';";
	echo "participants[".$part_count."][9] = '".$part3['identity']."';";
	echo "participants[".$part_count."][10] = '".$part3['payment']."';";
	echo "participants[".$part_count."][11] = '".$part3['email']."';";
	echo "participants[".$part_count."][12] = '".$part3['phone']."';";
	$part_count++;
}

//Collect data from DB payment and store them in Javascript array 'payment'
$pay = 'SELECT payer_email, num_of_items, total, date FROM payment ORDER BY id ';
$pay2 = mysql_query($pay) or die('Error! ' . mysql_error());
echo "payment = new Array();";
$pay_count = 0;
while($pay3 = mysql_fetch_array($pay2))
{
	echo "payment[".$pay_count."] = new Array(4);";
	echo "payment[".$pay_count."][0] = '".date("j-m-y h:ia", strtotime($pay3['date']) + 8*60*60)."';"; //adding 8 hours to make it HK time
	echo "payment[".$pay_count."][1] = '".$pay3['payer_email']."';";
	echo "payment[".$pay_count."][2] = '".$pay3['num_of_items']."';";
	echo "payment[".$pay_count."][3] = '".$pay3['total']."';";
	$pay_count++;
}

//Collect data from all club information and store them in Javascript array 'club'
$club = 'SELECT name_chi, name, rep_name, rep_phone, rep_email, type FROM club WHERE category = "local" ORDER BY id ';
$club2 = mysql_query($club) or die('Error! ' . mysql_error());
echo "club = new Array();";
$club_count = 0;
while($club3 = mysql_fetch_array($club2))
{
	echo 'club['.$club_count.'] = new Array(6);';
	echo 'club['.$club_count.'][0] = "'.$club3["name_chi"].'";';
	echo 'club['.$club_count.'][1] = "'.$club3["name"].'";';
	echo 'club['.$club_count.'][2] = "'.$club3["rep_name"].'";';
	echo 'club['.$club_count.'][3] = "'.$club3["rep_phone"].'";';
	echo 'club['.$club_count.'][4] = "'.$club3["rep_email"].'";';
	echo 'club['.$club_count.'][5] = "'.$club3["type"].'";';
	$club_count++;
}

//Collect data for participants who registered for ranking and store them in Javascript array 'rank'
$rank = 'SELECT name_chi, name, email, club, join_date FROM ranking ORDER BY id ';
$rank2 = mysql_query($rank) or die('Error! ' . mysql_error());
echo "rank = new Array();";
$rank_count = 0;
while($rank3 = mysql_fetch_array($rank2))
{
    echo 'rank['.$rank_count.'] = new Array(5);';
	echo 'rank['.$rank_count.'][0] = "'.$rank3["name_chi"].'";';
	echo 'rank['.$rank_count.'][1] = "'.$rank3["name"].'";';
	echo 'rank['.$rank_count.'][2] = "'.$rank3["email"].'";';
	echo 'rank['.$rank_count.'][3] = "'.$rank3["club"].'";';
	echo 'rank['.$rank_count.'][4] = "'.$rank3["join_date"].'";';
	$rank_count++;
}


echo "</script>";
?>
<table border = 0>
	<form name="compForm">
	<tr>
		<th width = 300>比賽</th>
		<th width = 200>比賽日期</th>
		<th width = 200>截止日期</th>
	</tr>
 	<tr>
		<td><select name=competition onchange="showing()" style="font-family: Arial" size="1">
		<option></option>
		<?php
            for($i = 0; $i < sizeof($comp); $i++)
            {
                echo "<option value='".$i."'>".$comp[$i][0];
                echo "</option>";
            }
            echo "<option value = 'p'>付款一覽</option>";
            echo "<option value = 'c'>會藉一覽</option>";
            echo "<option value = 'r'>已參加排名運動員</option>";
            echo "</td>";
        ?>
		<td><input name=deadline value ="" size="35" onchange="showing()" style="font-family: Arial" size="1" readonly="true"></td>
		<td><input name=compdate value ="" size="35" onchange="showing()" style="font-family: Arial" size="1" readonly="true"></td>
	</tr>
	<tr height = 60></tr>
	<table id='result'>
	<tr height = 800 widthborder = 0></tr>
	</table>
</table>
</p>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>

<script type='text/javascript'>
//Function to show all the information when certain competiton is selected
function showing()
{
	value = document.compForm.competition.value;
	//If value = payment information
	if(value == "p")
 	{
 		showingP();
 	}else if(value =="c")
    {
        showingC();
    }else if(value =="r")
    {
        showingR();
    }
    
    else{
		document.compForm.deadline.value = comp[value][2];
		document.compForm.compdate.value = comp[value][3];
		//setting the heading
		text = "<tr>";
		text += "<th>日期</th>";
		text += "<th>Code</th>";
		text += "<th>屬會名稱</th>";
		text += "<th>英文名</th>";
		text += "<th>中文名</th>";
		text += "<th>性別</th>";
		text += "<th>組別</th>";
		text += "<th>體重</th>";
		text += "<th>身份</th>";
		text += "<th>付費?</th>";
		text += "<th>email</th>";
		text += "<th>phone</th>";
		text += "</tr>";
		for (i = 0; i < participants.length; i++)
		{
				if(participants[i][0] == comp[value][1])
				{
					text += "<tr>";
					for(j = 1; j < participants[0].length; j++)
					{
						text += "<td>" + participants[i][j] + "</td>";
					}
					text += "</tr>"
				}
		}
		document.getElementById('result').innerHTML = text;
	}
}

function showingP()
{
	document.compForm.deadline.value = "NIL";
	document.compForm.compdate.value = "NIL";
	//setting the heading
	text = "<tr>";
	text += "<th>日期</th>";
	text += "<th>付款人電郵</th>";
	text += "<th>付款項目數量</th>";
	text += "<th>價錢</th>";
	text += "</tr>";
	//Two variables to store the total
	total_items = 0;
	total_amount = 0;
	for (i = 0; i < payment.length; i++)
	{
			text += "<tr>";
			for(j = 0; j < payment[0].length; j++)
			{
				text += "<td>" + payment[i][j] + "</td>";
			}
			text += "</tr>";
	}
	text += "<tr>";
	text += "<td colspan='2'>Total</td>";
	text += "<td></td>";
	text += "<td></td>";
	document.getElementById('result').innerHTML = text;
}

function showingR()
{
	document.compForm.deadline.value = "NIL";
	document.compForm.compdate.value = "NIL";
	//setting the heading
	text = "<tr>";
	text += "<th>中文名字</th>";
	text += "<th>英文名字</th>";
	text += "<th>電郵</th>";
	text += "<th>属會</th>";
    text += "<th>加入日期</th>";
	text += "</tr>";
	//Two variables to store the total
	total_items = 0;
	total_amount = 0;
	for (i = 0; i < rank.length; i++)
	{
			text += "<tr>";
			for(j = 0; j < rank[0].length; j++)
			{
				text += "<td>" + rank[i][j] + "</td>";
			}
			text += "</tr>";
	}
	text += "<tr>";
	text += "<td colspan='2'>Total</td>";
	text += "<td></td>";
	text += "<td></td>";
	document.getElementById('result').innerHTML = text;
}

function showingC()
{
	document.compForm.deadline.value = "NIL";
	document.compForm.compdate.value = "NIL";
	//setting the heading
	text = "<tr>";
	text += "<th width = '200'>中文名字</th>";
	text += "<th>英文名字</th>";
	text += "<th>代表姓名</th>";
	text += "<th>代表電話</th>";
    text += "<th>代表電郵</th>";
    text += "<th>會員類別</th>";
	text += "</tr>";
	//Two variables to store the total
	total_items = 0;
	total_amount = 0;
	for (i = 0; i < club.length; i++)
	{
			text += "<tr>";
			for(j = 0; j < club[0].length; j++)
			{
				text += "<td>" + club[i][j] + "</td>";
			}
			text += "</tr>";
	}
	text += "<tr>";
	text += "<td colspan='2'>Total</td>";
	text += "<td></td>";
	text += "<td></td>";
	document.getElementById('result').innerHTML = text;
}


</script>
