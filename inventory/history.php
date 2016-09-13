<?php
if (!isset($_GET['id']) || empty($_GET['id']))
    header("Location: $goto_after_mod");
require_once('../header.php');
require_once('inventorymanager.php');
$inventory_api = new InventoryManager();
$results = $inventory_api->dbFetchClaimHistoryById($_GET['id']);
?>
<div class='container'>
    <h2><?= $_GET['serial'] ?> History</h2>
    <table class='table table-bordered table-striped table-hover sticky-header'>
        <thead>
            <tr>
                <th>Claim Date</th>
                <th>Location</th>
                <th>Authorized</th>
            </tr>
        </thead>
        <tbody>
<?php
foreach ($results as $record) {
?>
            <tr>
                <td><?= $record['claim_date'] ?></td>
                <td><?= $record['username'] ?></td>
<?php
    if ($record['username'] == 'Unclaimed') {
        echo "<td></td>\n";
    } else if ($record['approved']) {
        echo "<td>Yes</td>\n";
    } else {
        echo "<td style='background-color:red; color:white'>No</td>\n";
    }
    echo "</tr>\n";
}
?>
        </tbody>
    </table>
</div>
<script>
$(document).ready(function(){
    // Add sticky header
    $('.sticky-header').floatThead({
        top:50
    });
});
</script>
<?php
require_once('../footer.php');
