<?php 
// calculating age on a certain day
function dayAge($birthdate,$day,$month,$year)
{
	$splitBirthdayParts = explode("-",$birthdate);
	$myDay=(int)$splitBirthdayParts[2];
	$myMonth=(int)$splitBirthdayParts[1];
	$myYear=(int)$splitBirthdayParts[0];

	//now get today's values
	$age="";
	$todaysDay = $day;
	$todaysMonth = $month;
	$todaysYear = $year;
	$age=$todaysYear-$myYear;
	if ($todaysMonth<$myMonth)
	{
		$age--;
	}
	if ($todaysMonth==$myMonth)
	{
		if ($todaysDay<$myDay)
		{
			$age--;
		}
	}
	if (!is_numeric($age)) $age="???";
	return $age;
}

//calculating age on a certain year
function yearAge($birthdate,$year)
{
  $splitBirthdayParts = explode("-",$birthdate);
  $myDay=(int)$splitBirthdayParts[2];
  $myMonth=(int)$splitBirthdayParts[1];
  $myYear=(int)$splitBirthdayParts[0];
  $age="";
  $todaysYear = $year;
  $age=$todaysYear-$myYear;
  return $age;
}

// To make text Columns
function textColumn($title, $name, $value, $required = false)
{
	if($required){
		$title = $title."*";
	}
	echo"
		<div class = 'row'>
			<div class = 'col-xs-10 col-xs-offset-1 col-md-5'>
				<label for='".$name."'>".$title."</label>
			</div>
			<div class = 'col-xs-10 col-xs-offset-1 col-md-5 col-md-offset-0'>
				<input class = 'form-control' name='".$name."' type='text' value='".$value."'>
			</div>
		</div>
	";
}

// To make date columns
function dateColumn($title, $name, $required = false)
{
	if($required){
		$title = $title."*";
	}
	$day = $name."_day";
	$month = $name."_month";
	$year = $name."_year";
	echo"
		<div class = 'row'>
			<div class = 'col-xs-10 col-xs-offset-1 col-md-4'>
				<label>".$title."</label>
			</div>
			<div class = 'col-xs-3 col-xs-offset-1 col-md-2 col-md-offset-0'>
				<label for='day'>日 Day</label>
				<select class = 'form-control' name='".$day."' type='text'>
					<option></option>";
					for($i = 1; $i < 32; $i++)
					{
						echo "<option value ='".$i."'>".$i."</option>";
					}
				echo"
				</select>
			</div>
			<div class = 'col-xs-3 col-md-2'>
				<label for='month'>月 Month</label>
				<select class = 'form-control' name='".$month."' type='text'>
					<option></option>";
					for($i = 1; $i < 13; $i++)
					{
						echo "<option value ='".$i."'>".$i."</option>";
					}
				echo"	
				</select>
			</div>
			<div class = 'col-xs-3 col-md-2'>
				<label for='year'>年 Year</label>
				<select class = 'form-control' name='".$year."' type='text'>
					<option></option>";
					$year_start = date('Y');
					for($i = $year_start; $i > $year_start-100; $i--)
					{
						echo "<option value ='".$i."'>".$i."</option>";
					}
				echo"	
				</select>
			</div>
		</div>";
}

// Sharing information on confirm pages
function confirmColumn($title, $value){
	echo"
	<div class = 'row'>
		<div class = 'col-xs-10 col-xs-offset-1 col-md-5'>
			<label>".$title."</label>
		</div>
		<div class = 'col-xs-10 col-xs-offset-1 col-md-5 col-md-offset-0'>
			".$value."
		</div>
	</div>";
}

?>
