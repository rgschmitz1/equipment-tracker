<?php
require_once('authenticateuser.php');
if (!$users_api->authorizeAdmin())
    exit('You must be an administrative user to access this page.');

// If user has submitted form, check user input
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('../equipment/equipmentmanager.php');
    $file = fopen("php://input", "r");
    $data = stream_get_contents($file);
    $parameters;
    parse_str($data, $parameters);
    // Delete user if all products successfully unclaimed
    if ($users_api->dbAlterUserStatus($_POST['id'], $_POST['status'])) {
        $users_api->dbClose();
    } else {
        $users_api->dbError();
    }
} else {
    header('Location: index.php');
}
