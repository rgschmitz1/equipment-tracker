<?php
require_once('../header.php');
if (!$users_api->authorizeAdmin()) {
    function shutdown()
    {
        require_once('../footer.php');
    }
    register_shutdown_function('shutdown');
    exit("<div class='container'>You must be an administrative user to access this page.</div>\n");
}
require_once('equipmentmanager.php');
$equipment_api = new EquipmentManager();

if (isset($_POST['authorize'])) {
    if ($equipment_api->dbAuthorizeClaim($_POST['authorize'])) {
        $equipment_api->dbClose();
    } else {
        $equipment_api->dbError();
    }
}

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
                <th>Last Claimed</th>
                <th>Location</th>
                <th>Authorize</th>
            </tr>
        </thead>
        <tbody>
<?php
// Display a list of claimed products that needs to be approved by admin
foreach ($results as $record) {
?>
            <tr>
                <td><?= $record['serial'] ?></td>
                <td><?= $record['description'] ?></td>
                <td><?= $record['claim_date'] ?></td>
                <td><?= $record['username'] ?></td>
                <td style='padding-top: 0px; padding-bottom: 0px'>
                    <form action='<?= $_SERVER['PHP_SELF'] ?>' method='post' role='form'>
                        <button class='btn btn-primary' type='submit' name='authorize' value='<?= $record['claim_id'] ?>'>Accept</button>
                    </form>
                </td>
            </tr>
<?php
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
