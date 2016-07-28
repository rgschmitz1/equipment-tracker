<?php
require_once('../header.php');

// If user has submitted form, check user input
if (isset($_POST['submit'])) {
    require_once('../inventory/inventorymanager.php');
    $inventory_api = new InventoryManager();
    // Delete user if all products successfully unclaimed
    if ($inventory_api->dbDeleteProduct($_POST['serial'])) {
        header('Location: index.php');
    } else {
    ?>
        <div class="alert alert-dismissible alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <p>Failed to delete product with serial number <b><?= $_POST['serial'] ?></b> from database.</p>
        </div>
    <?php
    }
}
?>

<div class="container">

<?php
if (!isset($_GET['serial']) || !isset($_GET['product']))
    header('Location: index.php');
?>

    <p>Please confirm you would like to delete product <b><?= $_GET['product'] ?></b>, with serial number <b><?= $_GET['serial'] ?></b>.</p>
    <form class="form-horizontal" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
    <input type="hidden" name="serial" value="<?= $_GET['serial'] ?>">
    <button type="submit" name="submit" value="submit" class="btn btn-danger">Confirm</button>
    <a href="index.php" class="btn btn-primary">Cancel</a>
    </form>
</div>

<?php
include('../footer.php');
