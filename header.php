<?php
require_once('appvars.php');
require_once('users/usermanager.php');
$api = new UserManager();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>X-ES MfgTest Inventory</title>
    <!-- https://getbootstrap.com -->
    <link rel="stylesheet" type="text/css" href="<?= SITE_ROOT ?>/css/bootstrap.min.css" />
    <!-- custom stylesheet -->
    <link rel="stylesheet" type="text/css" href="<?= SITE_ROOT ?>/css/custom.min.css" />
</head>
<body>

<?php
$api->authenticateUser();
require_once('navbar.php');
