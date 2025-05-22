<?php
try {
    // First query
    $query = "SELECT COUNT(*) FROM participants_" . $_SESSION['category'] . " WHERE club = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die('Error: ' . $e->getMessage());
}
?>

<div class="row row-block">
    <h4>
        <?php echo "已註冊會員:" . $row['COUNT(*)']; ?><br>
        <?php echo "Current registered participants: " . $row['COUNT(*)']; ?><br><br>
    </h4>
</div>