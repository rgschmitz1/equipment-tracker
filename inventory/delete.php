<?php
require_once('../header.php');
// If user has submitted form, check user input
if (isset($_POST['delete'])) {
    require_once('inventorymanager.php');
    $inventory_api = new InventoryManager();
    // Delete user if all products successfully unclaimed
    if ($inventory_api->dbDeleteProduct($_POST['delete'])) {
        header('Location: ' . $_POST['navafterdel']);
    } else {
?>
<div class="container">
    <div class="alert alert-dismissible alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <p>Failed to delete product with serial number <b><?= $_POST['serial'] ?></b> from database.</p>
    </div>
</div>
<?php
    }
} else {
    header('Location: index.php');
}
include('../footer.php');
