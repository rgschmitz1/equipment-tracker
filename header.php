<?php
require_once('appvars.php');
// Check if user is logged in, otherwise redirect to login page
if (!isset($_SESSION))
    session_start();
if (!isset($_SESSION['xes_userid']) && !isset($_SESSION['xes_adminid'])) {
    if ((SITE_ROOT . $_SERVER['PHP_SELF'] != SITE_ROOT . '/users/login.php') &&
        (SITE_ROOT . $_SERVER['PHP_SELF'] != SITE_ROOT . '/users/adminlogin.php')) {
        header('Location: ' . SITE_ROOT . '/users/login.php');
    }
}
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
