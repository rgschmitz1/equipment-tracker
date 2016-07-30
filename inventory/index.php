<?php
require_once('inventorymanager.php');
$inventory_api = new InventoryManager();
$results = $inventory_api->dbQueryProducts();
$inventory_header_title = 'Inventory Index';
$goto_after_mod = $_SERVER['PHP_SELF'];
require_once('inventory.php');
