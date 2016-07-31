<?php
require_once('inventorymanager.php');
$inventory_api = new InventoryManager();
// If user has submitted query form, check user input
if (isset($_GET['query'])) {
    if (!$results = $inventory_api->dbQueryProducts($_GET['query'], '')) {
        $error_msg = 'Failed to find product with keyword(s) "<b>' . $_GET['query'] . '</b>".';
    }
} else {
    $results = $inventory_api->dbQueryProducts('', '');
}
$inventory_header_title = 'Inventory Index';
$goto_after_mod = $_SERVER['PHP_SELF'];
require_once('inventory.php');
