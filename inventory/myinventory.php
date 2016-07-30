<?php
require_once('inventorymanager.php');
$inventory_api = new InventoryManager();
$results = $inventory_api->dbQueryUserProducts();
$inventory_header_title = 'My Inventory Index';
$goto_after_mod = $_SERVER['PHP_SELF'];
require_once('inventory.php');
