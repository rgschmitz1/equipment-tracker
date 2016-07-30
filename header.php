<?php
require_once('appvars.php');
require_once('users/usermanager.php');
$users_api = new UserManager();
$users_api->authenticateUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>X-ES MfgTest Inventory</title>
    <!-- favorite icon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?= SITE_ROOT ?>/favicon.ico" />
    <!-- https://getbootstrap.com -->
    <link rel="stylesheet" type="text/css" href="<?= SITE_ROOT ?>/css/bootstrap.min.css" />
    <!-- custom stylesheet -->
    <link rel="stylesheet" type="text/css" href="<?= SITE_ROOT ?>/css/custom.min.css" />
</head>
<body>
<?php
require_once('navbar.php');
