<?php
require_once('../header.php');
if (!$users_api->authorizeAdmin()) {
    require_once('../footer.php');
    exit("<div class='container'>You must be an administrative user to access this page.</div>\n");
}
require_once('equipmentmanager.php');
$equipment_api = new EquipmentManager();

// Query unapproved products after authorizing
$results = $equipment_api->dbQueryUnapprovedProducts();
?>

<div class='container'>
    <h2>Claim Approval</h2>
</div>
<div class='container-fluid'>
    <table class='table table-bordered table-striped table-hover sticky-header'>
        <thead>
            <tr>
                <th>Serial</th>
                <th>Description</th>
                <th>Claim Date</th>
                <th>Location</th>
                <th>
                    Authorize
                    <span class='glyphicon glyphicon-info-sign' data-toggle='tooltip' data-placement='left' title='Click accept button to authorize claim.'></span>
                </th>
            </tr>
        </thead>
        <tbody>
<?php
// Display a list of claimed products that needs to be approved by admin
foreach ($results as $record) {
?>
            <tr id='<?= $record['claim_id'] ?>'>
                <td><?= $record['serial'] ?></td>
                <td><?= $record['description'] ?></td>
                <td><?= $record['claim_date'] ?></td>
                <td><?= $record['username'] ?></td>
                <td>
                    <button class='btn btn-primary btn-no-pad' onclick='accept("<?= $record['claim_id'] ?>")'>Accept</button>
                </td>
            </tr>
<?php
}
?>
        </tbody>
    </table>
</div>
<script>
function accept(id) {
    $('#' + id).hide();
    $.ajax('processauthorize.php', {'data':{'authorize':id}, 'method':'POST'});
}
$(document).ready(function(){
    // Add sticky header
    $('.sticky-header').floatThead({
        top:50
    });
});
</script>
<?php
require_once('../footer.php');
