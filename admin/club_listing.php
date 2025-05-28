<?php
session_start();

$title = "Club listing";
include_once($_SERVER['DOCUMENT_ROOT']."/common/header.php");

if (!$_SESSION['admin']) {
    echo '<meta http-equiv=REFRESH CONTENT=1;url=/index.php>';
}

$hasEmailCriteria = false;
$hasRefIdCriteria = false;

if(isset($_GET["email"])) {
    $_SESSION['email'] = $_GET["email"];
    $hasEmailCriteria = true;
}

if(isset($_GET["Ref_id"])) {
    $_SESSION['ref_id'] = $_GET["Ref_id"];
    $hasRefIdCriteria = true;
}
?>

<title>Club Listing</title>

<script type='text/javascript'>
$(document).ready(function() {
    $('#example').DataTable({
        "lengthMenu": [ [ 20,50,100,200, -1], [20,50,100,200, "All"] ],
        colReorder: true,
        responsive: true,
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                title: 'Data export'
            },
            {
                extend: 'csvHtml5',
                title: 'Data export'
            },
            {
                extend: 'excelHtml5',
                title: 'Data export'
            },
        ]
    });
} );
</script>

<a href="<?php echo $_SERVER['HTTP_REFERER'] ?>">Back</a>
<a href="dashboard.php"> | Dashboard</a>

<table id="example" class="table table-hover table-condensed table-striped table-responsive">
    <thead>
        <tr>
            <th>會員<br>Members</th>
            <th>會員ID<br>Member ID</th>
            <th>中文名字<br>Chinese Name</th>
            <th>英文名字<br>English Name</th>
            <th>代表姓名<br>Rep Name</th>
            <th>代表電話<br>Rep Phone</th>
            <th>代表電郵<br>Rep Email</th>
            <th>代表地址<br>Rep Address</th>
            <th>會員類別<br>Membership Type</th>
            <th>用户名<br>Username</th>
            <th>Code<br>Code</th>
            <th>商業登記/社團註冊<br>BR</th>
            <th>登記日期<br>Applied Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $queryMain = "select ref_id, name_chi, name, rep_name, rep_address, rep_phone, rep_email, type, address, username, code, br, date from club where category = ?";
        $emailCriteria = " and rep_email = ?";
        $refIdCriteria = " and ref_id = ?";

        $myQuery = $queryMain;
        $params = ["local"];

        if ($hasEmailCriteria) {
            $myQuery = $myQuery . $emailCriteria;
            $params[] = $_SESSION['email'];
        }

        if ($hasRefIdCriteria) {
            $myQuery = $myQuery . $refIdCriteria;
            $params[] = $_SESSION['ref_id'];
        }

        $stmt = $pdo->prepare($myQuery);
        $stmt->execute($params);

        while ($comp = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <tr>
            <td><?php 
                echo "<a href=member_listing.php?clubcode=".$comp['username'].">"."All</a>" ;
                echo "<a href=member_listing.php?clubcode=".$comp['username']."&role=Referee>"." Ref</a>" ;
                echo "<a href=member_listing.php?clubcode=".$comp['username']."&role=Coach>"." Coach</a>" ;
            ?>
            </td>
            <td><?php echo $comp['ref_id'] ?></td>
            <td><?php echo $comp['name_chi'] ?></td>
            <td><?php echo ($comp['name'])?> </td>
            <td><?php echo ($comp['rep_name']) ?></td>
            <td><?php echo ($comp['rep_phone']) ?></td>
            <td><?php echo ($comp['rep_email']) ?></td>
            <td><?php echo ($comp['address']) ?></td>
            <td><?php echo ($comp['type']) ?></td>
            <td><?php echo ($comp['username']) ?></td>
            <td><?php echo ($comp['code']) ?></td>
            <td><?php echo ($comp['br']) ?></td>
            <td><?php echo ($comp['date']) ?></td>
        </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th>會員<br>Members</th>
            <th>會員ID<br>Member ID</th>
            <th>中文名字<br>Chinese Name</th>
            <th>英文名字<br>English Name</th>
            <th>代表姓名<br>Rep Name</th>
            <th>代表電話<br>Rep Phone</th>
            <th>代表電郵<br>Rep Email</th>
            <th>代表地址<br>Rep Address</th>
            <th>會員類別<br>Membership Type</th>
            <th>用户名<br>Username</th>
            <th>Code<br>Code</th>
            <th>商業登記/社團註冊<br>BR</th>
            <th>登記日期<br>Applied Date</th>
        </tr>
    </tfoot>
</table>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/common/footer.php"); ?>
