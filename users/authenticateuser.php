<?php
require_once('usermanager.php');
$users_api = new UserManager();
$users_api->authenticateUser();
