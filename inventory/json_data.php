<?php
require_once('inventorymanager.php');
$inventory_api = new InventoryManager();
// Set user_id to null if not set
$user_id = (isset($_GET['user_id']) ? $_GET['user_id'] : '');
// Set not_claimed to null if not set
$claimed = (isset($_GET['claimed']) ? $_GET['claimed'] : '');
$results = $inventory_api->dbQueryProducts($user_id, $claimed);
$output['data'] = $results;
echo json_encode($output);
