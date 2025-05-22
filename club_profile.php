<?php
session_start();
$title = "會籍資料 Club Profile";
include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

try {
    // Fetch club info
    $stmt = $pdo->prepare("SELECT * FROM club WHERE username = ?");
    $stmt->execute([$username]);
    $club = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$club) {
        echo "<div class='alert alert-danger'>找不到您的會籍資料 Club information not found.</div>";
        include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php");
        exit;
    }

    // Handle update
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $fields = [
            'name', 'name_chi', 'type', 'address', 'phone', 'practice_place', 'practice_time',
            'coach_name', 'coach_dan', 'coach_id', 'coach2_name', 'coach2_dan', 'coach2_id',
            'rep_name', 'rep_id', 'rep_address', 'rep_phone', 'rep_email'
        ];
        $update = [];
        $params = [];
        foreach ($fields as $field) {
            $update[] = "$field = ?";
            $params[] = $_POST[$field];
        }
        $params[] = $username;
        $sql = "UPDATE club SET " . implode(", ", $update) . " WHERE username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        echo "<div class='alert alert-success'>資料已更新 Club information updated.</div>";

        // Refresh club info
        $stmt = $pdo->prepare("SELECT * FROM club WHERE username = ?");
        $stmt->execute([$username]);
        $club = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    ?>
    <div class="row row-block">
        <h3>會籍資料 Club Profile</h3>
        <form method="post">
            <div class="form-group">
                <label>英文名稱 Name:</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($club['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>中文名稱 Name (Chi):</label>
                <input type="text" name="name_chi" class="form-control" value="<?php echo htmlspecialchars($club['name_chi']); ?>" required>
            </div>
            <div class="form-group">
                <label>類型 Type:</label>
                <input type="text" name="type" class="form-control" value="<?php echo htmlspecialchars($club['type']); ?>">
            </div>
            <div class="form-group">
                <label>會員號碼 Membership No.:</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($club['Ref_id']); ?>" readonly>
            </div>
            <div class="form-group">
                <label>地址 Address:</label>
                <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($club['address']); ?>">
            </div>
            <div class="form-group">
                <label>電話 Phone:</label>
                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($club['phone']); ?>">
            </div>
            <div class="form-group">
                <label>練習地點 Practice Place:</label>
                <input type="text" name="practice_place" class="form-control" value="<?php echo htmlspecialchars($club['practice_place']); ?>">
            </div>
            <div class="form-group">
                <label>練習時間 Practice Time:</label>
                <input type="text" name="practice_time" class="form-control" value="<?php echo htmlspecialchars($club['practice_time']); ?>">
            </div>
            <div class="form-group">
                <label>主教練姓名 Coach Name:</label>
                <input type="text" name="coach_name" class="form-control" value="<?php echo htmlspecialchars($club['coach_name']); ?>">
            </div>
            <div class="form-group">
                <label>主教練段位 Coach Dan:</label>
                <input type="text" name="coach_dan" class="form-control" value="<?php echo htmlspecialchars($club['coach_dan']); ?>">
            </div>
            <div class="form-group">
                <label>主教練證號 Coach ID:</label>
                <input type="text" name="coach_id" class="form-control" value="<?php echo htmlspecialchars($club['coach_id']); ?>">
            </div>
            <div class="form-group">
                <label>副教練姓名 Coach2 Name:</label>
                <input type="text" name="coach2_name" class="form-control" value="<?php echo htmlspecialchars($club['coach2_name']); ?>">
            </div>
            <div class="form-group">
                <label>副教練段位 Coach2 Dan:</label>
                <input type="text" name="coach2_dan" class="form-control" value="<?php echo htmlspecialchars($club['coach2_dan']); ?>">
            </div>
            <div class="form-group">
                <label>副教練證號 Coach2 ID:</label>
                <input type="text" name="coach2_id" class="form-control" value="<?php echo htmlspecialchars($club['coach2_id']); ?>">
            </div>
            <div class="form-group">
                <label>負責人姓名 Rep Name:</label>
                <input type="text" name="rep_name" class="form-control" value="<?php echo htmlspecialchars($club['rep_name']); ?>">
            </div>
            <div class="form-group">
                <label>負責人證號 Rep ID:</label>
                <input type="text" name="rep_id" class="form-control" value="<?php echo htmlspecialchars($club['rep_id']); ?>">
            </div>
            <div class="form-group">
                <label>負責人地址 Rep Address:</label>
                <input type="text" name="rep_address" class="form-control" value="<?php echo htmlspecialchars($club['rep_address']); ?>">
            </div>
            <div class="form-group">
                <label>負責人電話 Rep Phone:</label>
                <input type="text" name="rep_phone" class="form-control" value="<?php echo htmlspecialchars($club['rep_phone']); ?>">
            </div>
            <div class="form-group">
                <label>負責人電郵 Rep Email:</label>
                <input type="email" name="rep_email" class="form-control" value="<?php echo htmlspecialchars($club['rep_email']); ?>">
            </div>
            <button type="submit" name="update" class="btn btn-primary">更新資料 Update</button>
        </form>
    </div>
    <?php

} catch(PDOException $e) {
    echo "<div class='alert alert-danger'>資料庫錯誤 Database error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php");
?>