<?php
session_start();
$title = "Thank you";
include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");
try {
    //Number of Columns
    $column = 5;
    
    // Get club name using PDO
    $stmt = $pdo->prepare('SELECT name, name_chi FROM club WHERE username = ?');
    $stmt->execute([$username]);
    $name3 = $stmt->fetch();
    
    echo htmlspecialchars($name3['name_chi']);
    echo " ";
    echo htmlspecialchars($name3['name']);
    echo '</a></h3><div><p>';
    ?>
    
    <p align="center">
        謝謝閣下的付款，付費已經完成，有關收據已電郵給閣下，閣下可往paypal.com了解交易的詳情<br>
        Thank you for your payment. Your transaction has been completed, and a receipt for your purchase 
        has been emailed to you. You may log into your account at www.paypal.com to view details of this transaction.
    </p>
    
    <?php
} catch(PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    echo "An error occurred. Please try again later.";
}


include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>
