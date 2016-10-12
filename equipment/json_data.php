<?php
require_once('equipmentmanager.php');
$equipment_api = new EquipmentManager();
// Set user_id to null if not set
$user_id = (isset($_GET['user_id']) ? $_GET['user_id'] : '');
// Set claimed to null if not set
$claimed = (isset($_GET['claimed']) ? $_GET['claimed'] : '');
$results = $equipment_api->dbQueryProducts($user_id, $claimed);
$output['data'] = $results;
echo json_encode($output);
