<?php
require_once('authenticateuser.php');
if (!$users_api->authorizeAdmin())
    exit('You must be an administrative user to access this page.');

// If user has submitted form, check user input
if (isset($_POST['delete'])) {
    require_once('../equipment/equipmentmanager.php');
    $equipment_api = new EquipmentManager();
    // Unclaim all products for user to delete
    if ($equipment_api->dbUnclaimAll($_POST['delete'])) {
        // Delete user if all products successfully unclaimed
        if ($users_api->dbDeleteUser($_POST['delete'])) {
            header('Location: index.php');
        } else {
            echo 'Failed to delete user id <b>' . $_POST['delete'] . '</b> from database.';
        }
    }
} else {
    header('Location: index.php');
}
