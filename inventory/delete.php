<?php
require_once('../users/authenticateuser.php');
// If user has submitted form, check user input
if (isset($_POST['delete'])) {
    require_once('inventorymanager.php');
    $inventory_api = new InventoryManager();
    // Delete user if all products successfully unclaimed
    if ($inventory_api->dbDeleteProduct($_POST['delete'])) {
        header('Location: ' . $_POST['navafterdel']);
    } else {
        echo 'Failed to delete product with serial number <b>' . $_POST['serial'] . '</b> from database.';
    }
} else {
    header('Location: index.php');
}
