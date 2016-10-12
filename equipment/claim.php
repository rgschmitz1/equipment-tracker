<?php
require_once('../users/authenticateuser.php');
// If user has submitted form, check user input
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('equipmentmanager.php');
    $equipment_api = new EquipmentManager();
    // Claim/unclaim product
    $file = fopen("php://input", "r");
    $data = stream_get_contents($file);
    $parameters;
    parse_str($data, $parameters);
    if ($equipment_api->dbClaimProduct($parameters['claim'], $parameters['user'])) {
        if ($parameters['user'] != '1') {
            $users_api->emailAdmin($_SESSION['xes_username'], $parameters['product'], $parameters['serial']);
        }
    } else {
        echo 'Failed to claim/unclaim product with id <b>' . $parameters['claim'] . '</b> from database.';
    }
} else {
    header('Location: index.php');
}
