<?php
require_once('../header.php');
// If user has submitted form, check user input
if (isset($_POST['claim']) && isset($_POST['user'])) {
    require_once('inventorymanager.php');
    $inventory_api = new InventoryManager();
    // Delete user if all products successfully unclaimed
    if ($inventory_api->dbClaimProduct($_POST['claim'], $_POST['user'])) {
        header('Location: index.php');
    } else {
?>
<div class="container">
    <div class="alert alert-dismissible alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <p>Failed to claim/unclaim product with id <b><?= $_POST['claim'] ?></b> from database.</p>
    </div>
</div>
<?php
    }
} else {
    header('Location: index.php');
}
include('../footer.php');
