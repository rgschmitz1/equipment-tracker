<?php
require_once('../header.php');
if (!$users_api->authorizeAdmin()) {
    function shutdown()
    {
        require_once('../footer.php');
    }
    register_shutdown_function('shutdown');
    exit('<div class="container">You must be an administrative user to access this page.</div>');
}

// If user has submitted form, check user input
if (isset($_POST['submit'])) {
    require_once('../inventory/inventorymanager.php');
    $inventory_api = new InventoryManager();
    // Unclaim all products for user to delete
    if ($inventory_api->dbUnclaimAll($_POST['id'])) {
        // Delete user if all products successfully unclaimed
        if ($users_api->dbDeleteUser($_POST['id'])) {
            header('Location: index.php');
        } else {
        ?>
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <p>Failed to delete user <b><?= $_POST['user'] ?></b> from database.</p>
            </div>
        <?php
        }
    }
}
?>

<div class="container">

<?php
if (!isset($_GET['user']))
    header('Location: index.php');
?>

    <p>Please confirm you would like to delete user <b><?= $_GET['user'] ?></b>.</p>
    <form class="form-horizontal" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
    <input type="hidden" type="text" name="id" value="<?= $_GET['id'] ?>"</input>
    <button type="submit" name="submit" value="submit" class="btn btn-danger">Confirm</button>
    <a href="index.php" class="btn btn-primary">Cancel</a>
    </form>
</div>

<?php
include('../footer.php');
