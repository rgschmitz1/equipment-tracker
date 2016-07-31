<?php
require_once('inventorymanager.php');
$inventory_api = new InventoryManager();
if (!isset($_SESSION))
    session_start();
// If user has submitted query form, check user input
if (isset($_GET['query'])) {
    if (!$results = $inventory_api->dbQueryProducts($_GET['query'], $_SESSION['xes_userid'])) {
        $error_msg = 'Failed to find product with keyword(s) "<b>' . $_GET['query'] . '</b>" for username <b>' . $_SESSION['xes_username'] . '</b>.';
    }
} else {
    $results = $inventory_api->dbQueryProducts('', $_SESSION['xes_userid']);
}
$inventory_header_title = 'My Inventory Index';
$goto_after_mod = $_SERVER['PHP_SELF'];
require_once('inventory.php');
