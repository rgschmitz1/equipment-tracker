<?php
require_once('users/usermanager.php');
$users_api = new UserManager();
$users_api->authenticateUser();
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <title>X-ES <?= SITE_TITLE ?></title>
    <!-- Favorite icon -->
    <link rel='shortcut icon' type='image/x-icon' href='<?= SITE_ROOT ?>/favicon.ico' />
    <!-- Bootstrap v3.3.6 (http://getbootstrap.com) -->
    <link rel='stylesheet' type='text/css' href='<?= SITE_ROOT ?>/css/bootstrap.min.css' />
    <!-- DataTables stylesheet -->
    <link rel='stylesheet' type='text/css' href='<?= SITE_ROOT ?>/css/dataTables.bootstrap.min.css' />
    <!-- Custom stylesheet -->
    <link rel='stylesheet' type='text/css' href='<?= SITE_ROOT ?>/css/custom.css' />
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src='<?= SITE_ROOT ?>/js/jquery-1.12.0.min.js'></script>
    <script src='<?= SITE_ROOT ?>/js/jquery-migrate-1.2.1.min.js'></script>
    <!-- Include all compiled plugins -->
    <script src='<?= SITE_ROOT ?>/js/bootstrap.min.js'></script>
    <!-- Float table headers -->
    <script src='<?= SITE_ROOT ?>/js/jquery.floatThead.min.js'></script>
    <!-- DataTables plugin -->
    <script src='<?= SITE_ROOT ?>/js/jquery.dataTables.min.js'></script>
    <script src='<?= SITE_ROOT ?>/js/dataTables.bootstrap.min.js'></script>
</head>
<body>
<?php
require_once('navbar.php');
