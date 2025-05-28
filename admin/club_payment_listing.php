<?php
session_start();

$title = "Club Payment Listing";
include_once($_SERVER['DOCUMENT_ROOT'] . "/common/header.php");

if (!$_SESSION['admin']) {
    echo '<meta http-equiv=REFRESH CONTENT=1;url=/index.php>';
    exit();
}
?>

<title>Club Payment Listing</title>

<script type='text/javascript'>
    $(document).ready(function() {
        $('#example').DataTable({
            "lengthMenu": [
                [20, 50, 100, 200, -1],
                [20, 50, 100, 200, "All"]
            ],
            colReorder: true,
            "order": [
                [0, "desc"]
            ],
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
                }
            ]
        });
    });
</script>

<a href="<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'dashboard.php' ?>">Back</a>
<a href="dashboard.php"> | Dashboard</a>

<table id="example" class="table table-hover table-condensed table-striped table-responsive">
    <thead>
        <tr>
            <th>識別號碼<br>ID</th>
            <th>屬會編號<br>Club ID</th>
            <th>屬會資料<br>Club Details</th>
            <th>付款日期<br>Payment Date</th>
            <th>付款人電郵<br>Payer Email</th>
            <th>自訂識別號碼<br>Custom ID</th>
            <th>會費 (港元)<br>Fee (HK$)</th>
            <th>已付款<br>Paid</th>
            <th>付款年度<br>Year</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $myQuery = "SELECT id, club_id, year, fee, paid, custom_id, payer_email, paid_at FROM club_payments ORDER BY id desc";
        $stmt = $pdo->prepare($myQuery);
        $stmt->execute();

        while ($comp = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
            <tr>
                <td><?php echo htmlspecialchars($comp['id']) ?></td>
                <td><?php echo htmlspecialchars($comp['club_id']) ?></td>
                <td><?php echo "<a href=club_listing.php?Ref_id=" . $comp['club_id'] . " target='blank'>" . " Club Details</a>" ?></td>
                <td><?php echo htmlspecialchars($comp['paid_at']) ?></td>
                <td><?php echo htmlspecialchars($comp['payer_email']) ?></td>
                <td><?php echo htmlspecialchars($comp['custom_id']) ?></td>
                <td><?php echo htmlspecialchars($comp['fee']) ?></td>
                <td><?php echo $comp['paid'] == 1 ? 'Yes' : 'No' ?></td>
                <td><?php echo htmlspecialchars($comp['year']) ?></td>
            </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th>識別號碼<br>ID</th>
            <th>屬會編號<br>Club ID</th>
            <th>屬會資料<br>Club Details</th>
            <th>付款日期<br>Payment Date</th>
            <th>付款人電郵<br>Payer Email</th>
            <th>自訂識別號碼<br>Custom ID</th>
            <th>會費 (港元)<br>Fee (HK$)</th>
            <th>已付款<br>Paid</th>
            <th>付款年度<br>Year</th>
        </tr>
    </tfoot>
</table>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/common/footer.php"); ?>
