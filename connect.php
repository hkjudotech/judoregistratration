<?php
session_start();

try {
    // Connect to database using PDO
    $pdo = new PDO(
        'mysql:host=localhost;dbname=judonorg_judo;charset=utf8',
        'judonorg_reg',
        '1024judo',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
    
    echo 'logging in...!';

    // Getting data from post
    $id = $_POST['username'];
    $pw = $_POST['password'];

    echo "is id " . $id;

    // Prepare and execute query using parameterized statement
    $stmt = $pdo->prepare("SELECT * FROM club WHERE username = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_NUM);

    if($id != null && $pw != null && $row[1] == $id && $row[2] == $pw) {
        // writing to session
        $_SESSION['username'] = $id;
        echo '登入成功!';

        // Second query using prepared statement
        $stmt_pre = $pdo->prepare("SELECT category, type FROM club WHERE username = ?");
        $stmt_pre->execute([$id]);
        $row_pre = $stmt_pre->fetch(PDO::FETCH_ASSOC);

        $_SESSION['category'] = $row_pre['category'];
        $_SESSION['admin'] = false;

        if($row_pre['type'] == "admin") {
            $_SESSION['admin'] = true;
            echo '<meta http-equiv=REFRESH CONTENT=1;url=/admin/dashboard.php>';
        } else {
            echo '<meta http-equiv=REFRESH CONTENT=1;url=front.php>';
        }

        $_SESSION['log'] = 1;
    } else {
        echo '登入失敗!';
        echo '<meta http-equiv=REFRESH CONTENT=1;url=/index.php>';
        $_SESSION['log'] = 0;
    }

} catch(PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}