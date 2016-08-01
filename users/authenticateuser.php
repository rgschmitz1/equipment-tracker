<?php
require_once('../appvars.php');
require_once('../dbmanager.php');
require_once('usermanager.php');
$users_api = new UserManager();
$users_api->authenticateUser();
