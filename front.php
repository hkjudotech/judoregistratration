<?php
session_start();
$title = "主頁 Home";
include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");
include_once($_SERVER['DOCUMENT_ROOT']."/common/count.php");

try {

  // Club query
    $clubquery = "SELECT name, name_chi, type, Ref_id FROM club WHERE username = ?";
    $stmt = $pdo->prepare($clubquery);
    $stmt->execute([$username]);
    $clubrow = $stmt->fetch(PDO::FETCH_ASSOC);

    // Temp IPN database insertions
   /* $ran = []; // Initialize as empty array
    for ($b = 1; $b < $_SESSION['store']; $b++) {
      $insert = "INSERT INTO " . $_SESSION['category'] . " VALUES (" . $_SESSION["insert".$b] . ",'paid')";
      $ran[$b] = rand(1,999999);
   
       // Replace into temp_ipn
        $in = "REPLACE INTO temp_ipn VALUES (?, ?)";
        $stmt = $pdo->prepare($in);
        $stmt->execute([$ran[$b], $insert]);
    }

    // Building custom string
    $custom = implode("_", array_slice($ran, 1));

*/
?>

<div class="row row-block">
   <div class = "col-xs-10 col-xs-offset-4 col-md-6">

        
        <li>報名功能回復正常。
        <br> Event Registration restored  </li>
        <li>屬會可以透過會籍資料頁面查看及更新其屬會籍資料，並且可以繳交通常於十二月到期的年度會費。
        <br> Annual Club Due payment and Club Info updates functions added</li>
        <li>教練研討班及隊制賽的報名目前正在開發中，將於日後開放使用，感謝您的耐心等待 請持續關注最新消息。
        <br>Coaching Seminar and Team Championship Registration are currently under development and will be accessible at a later date. We appreciate your patience </li>

        

    </div>
</div>

<div class="row row-block">
    <h3>可報名比賽 Available Competition</h3>
    <div class="row">
        <?php
        // Competition query
            $comp = "SELECT * FROM competition WHERE type = ?";
            $stmt = $pdo->prepare($comp);
            $stmt->execute([$_SESSION['category']]);
            
            while ($comp3 = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $deadline = floor(strtotime($comp3['deadline'])/(60*60*24) - time()/(60*60*24)) + 1;
                if ($deadline > -1) {
                    ?>
                    <h4><a href='join.php'><?= $comp3['name'] ?><br><?= $comp3['name_eng'] ?></a></h4>
                    <?php
                }
            }
            ?>
    </div>
</div>

<?php 

} catch(PDOException $e) {
    die('Error: ' . $e->getMessage());
}

include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php");
?>