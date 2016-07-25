<?php
require_once('../header.php');
require_once('inventorymanager.php');
$inventory_api = new InventoryManager();
$results = $inventory_api->dbQueryUserProducts();
$inventory_header_title = 'My Inventory Index';
require_once('inventory.php');
require_once('../footer.php');
