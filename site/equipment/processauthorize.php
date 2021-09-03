<?php
require_once('../users/authenticateuser.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('equipmentmanager.php');
    $equipment_api = new EquipmentManager();
    $file = fopen("php://input", "r");
    $data = stream_get_contents($file);
    $parameters;
    parse_str($data, $parameters);
    if ($equipment_api->dbAuthorizeClaim($_POST['authorize'])) {
        $equipment_api->dbClose();
    } else {
        $equipment_api->dbError();
    }
} else {
    header('Location: index.php');
}
