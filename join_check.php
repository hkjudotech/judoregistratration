<?php
declare(strict_types=1);

session_start();
$title = "現有報名 Current Registration";
include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

try {
    //Number of Columns
    $column = 15;
    
    // Get club name
    $stmt = $pdo->prepare('SELECT name, name_chi FROM club WHERE username = ?');
    $stmt->execute([$username]);
    $name3 = $stmt->fetch();

    if(isset($_GET["short"])) {
        $_SESSION['short'] = $_GET["short"];
    }
    if(isset($_GET["dl"])) {
        $_SESSION['dl'] = $_GET['dl'];
        echo '</a></h3><div><p>';
    }

    // Get competition name
    $stmt = $pdo->prepare('SELECT name, name_eng FROM competition WHERE short = ?');
    $stmt->execute([$_SESSION['short']]);
    $comp3 = $stmt->fetch();

    //Select data from participants
    $stmt = $pdo->prepare("SELECT id, name, name_chi, gender, division, weight, 
                          identity, payment 
                          FROM " . $_SESSION['category'] . 
                          " WHERE country = ? AND competition = ? 
                          ORDER BY gender, weight, name");
    $stmt->execute([$name3['name'], $_SESSION['short']]);
?>


<div class = "row row-block">
	<form name="myForm" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<div class ="row text-center">
			<?php
			echo $comp3['name'];
			echo "<br>";
			echo $comp3['name_eng'];
			?>
		</div>

		<div class = "row mt2">
			<div class = "col-md-1 col-md-offset-1">身份<br>Identity</div>
			<div class = "col-md-2">參賽者<br>Participants</div>
			<div class = "col-md-1">中文名稱<br>Chinese Name</div>
			<div class = "col-md-1">性別年齡<br>Gender/Age</div>
			<div class = "col-md-1">Division<br>Division</div>
			<div class = "col-md-1">體重<br> Weight</div>
			<div class = "col-md-2">已網上付款<br>Paid on Web</div>
			<div class = "col-md-1">刪除<br>Delete</div>
		</div>

		<?php
		$count = 1;
        while ($part3 = $stmt->fetch()) {
		 
			$p_id[$count] = $part3['id'];
			if($part3['payment'] == "paid"){
				$paid = "Yes";
			}else{
				$paid = "No";
			}
			
			   ?>
                <div class="row mt1">
                    <div class="col-md-1 col-md-offset-1"><?= htmlspecialchars($part3['identity']) ?></div>
                    <div class="col-md-2"><?= htmlspecialchars($part3['name']) ?></div>
                    <div class="col-md-1"><?= htmlspecialchars($part3['name_chi']) ?></div>
                    <div class="col-md-1"><?= htmlspecialchars($part3['gender']) ?></div>
                    <div class="col-md-1"><?= htmlspecialchars($part3['division']) ?></div>
                    <div class="col-md-1"><?= htmlspecialchars($part3['weight']) ?></div>
                    <div class="col-md-2"><?= $paid ?></div>
                    <?php if ($_SESSION['dl'] > 0): ?>
                        <div class="col-md-1">
                            <input type="submit" name="delete<?= $count ?>" value="Delete"/>
                        </div>
                    <?php else: ?>
                        <div class="col-md-1"></div>
                    <?php endif; ?>
                </div>
                <?php
                $count++;
		     
		 }
            ?>
		
	</form>
</div>

  <?php
   // Handle deletions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach ($_POST as $key => $value) {
            if (str_starts_with($key, 'delete')) {
                $index = (int)substr($key, 6);
                if (isset($p_id[$index])) {
                    deleteParticipant($pdo, $p_id[$index]);
                }
            }
        }
    }

    
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    echo "An error occurred. Please try again later.";
}

// Delete function using PDO
function deleteParticipant(PDO $pdo, int $id): void 
{
    try {
        $stmt = $pdo->prepare("DELETE FROM " . $_SESSION['category'] . " WHERE id = ?");
        $stmt->execute([$id]);
        echo "Participant deleted";
        echo '<meta http-equiv=REFRESH CONTENT=2;>';
    } catch (PDOException $e) {
        error_log("Delete Error: " . $e->getMessage());
        throw new RuntimeException("Could not delete participant");
    }
}

 include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>
