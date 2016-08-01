<?php
require_once('../users/authenticateuser.php');
// If user has submitted form, check user input
if (isset($_POST['claim']) && isset($_POST['user'])) {
    require_once('inventorymanager.php');
    $inventory_api = new InventoryManager();
    // Claim/unclaim product
    if ($inventory_api->dbClaimProduct($_POST['claim'], $_POST['user'])) {
        header('Location: ' . $_POST['navafterclaim']);
    } else {
        echo 'Failed to claim/unclaim product with id <b>' . $_POST['claim'] . '</b> from database.';
    }
} else {
    header('Location: index.php');
}
