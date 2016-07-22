<?php
require_once('header.php');
$site_root = '/xes';
if ($_SERVER['PHP_SELF'] != "$site_root/inventory/index.php")
    header("Location: $site_root/inventory/index.php");
#require_once('inventory/index.php');
require_once('footer.php');
