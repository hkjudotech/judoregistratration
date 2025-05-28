<?php
session_start();

$title = "Event Payment Listing";
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
            buttons: [{
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
            <th>活動名稱<br>Event Name</th>
            <th>屬會<br>Club Name(CHI)</th>
            <th>屬會<br>Club Name </th>
            <th>付款日期<br>Payment Date</th>
            <th>自訂識別號碼<br>Custom ID</th>
            <th>合計 (港元)<br>Total Amount (HK$)</th>
            <th>參加者<br>Participant No.</th>
            <th>新會員<br>New Member</th>
            <th>已付款<br>Paid</th>
            <th>付款人電郵<br>Payer Email</th>


        </tr>
    </thead>
    <tbody>
        <?php
        $myQuery = "SELECT id, custom_id, competition, club_name, club_name_chi, total_amount, participant_count, membership_fee_count,  payer_email, paid_at, paid
         FROM event_payment ORDER BY id desc";
        $stmt = $pdo->prepare($myQuery);
        $stmt->execute();

        while ($comp = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
            <tr>
                <td><?php echo htmlspecialchars($comp['id']) ?></td>
                <td><?php echo htmlspecialchars($comp['competition']) ?></td>
                <td><?php echo htmlspecialchars($comp['club_name_chi']) ?></td>
                <td><?php echo htmlspecialchars($comp['club_name']) ?></td>
                <td><?php echo htmlspecialchars($comp['paid_at']) ?></td>
                <td><?php echo htmlspecialchars($comp['custom_id']) ?></td>
                <td><?php echo htmlspecialchars($comp['total_amount']) ?></td>
                <td><?php echo htmlspecialchars($comp['participant_count']) ?></td>
                <td><?php echo htmlspecialchars($comp['membership_fee_count']) ?></td>
                <td><?php echo $comp['paid'] == 1 ? 'Yes' : 'No' ?></td>
                <td><?php echo htmlspecialchars($comp['payer_email']) ?></td>

            </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th>識別號碼<br>ID</th>
            <th>活動名稱<br>Event Name</th>
            <th>屬會<br>Club Name(CHI)</th>
            <th>屬會<br>Club Name </th>
            <th>付款日期<br>Payment Date</th>
            <th>自訂識別號碼<br>Custom ID</th>
            <th>合計 (港元)<br>Total Amount (HK$)</th>
            <th>參加者<br>Participant No.</th>
            <th>新會員<br>New Member</th>
            <th>已付款<br>Paid</th>
            <th>付款人電郵<br>Payer Email</th>
        </tr>
    </tfoot>
</table>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/common/footer.php"); ?>