<?php
require_once('appvars.php');
require_once('users/usermanager.php');
$users_api = new UserManager();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>X-ES MfgTest Inventory</title>
    <!-- https://getbootstrap.com -->
    <link rel="stylesheet" type="text/css" href="<?= SITE_ROOT ?>/css/bootstrap.min.css" />
    <!-- custom stylesheet -->
    <link rel="stylesheet" type="text/css" href="<?= SITE_ROOT ?>/css/custom.min.css" />
    <!-- favorite icon -->
    <link rel="shortcut icon" href="<?= SITE_ROOT ?>/favicon.ico" type="image/x-icon" />
</head>
<body>

<?php
$users_api->authenticateUser();
require_once('navbar.php');
