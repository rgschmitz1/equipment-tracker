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
if (isset($_POST['delete'])) {
    require_once('../inventory/inventorymanager.php');
    $inventory_api = new InventoryManager();
    // Unclaim all products for user to delete
    if ($inventory_api->dbUnclaimAll($_POST['delete'])) {
        // Delete user if all products successfully unclaimed
        if ($users_api->dbDeleteUser($_POST['delete'])) {
            header('Location: index.php');
        } else {
        ?>
            <div class="container">
                <div class="alert alert-dismissible alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p>Failed to delete user id <b><?= $_POST['delete'] ?></b> from database.</p>
                </div>
            </div>
        <?php
        }
    }
}
require_once('../footer.php');
