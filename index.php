<?php
require_once('header.php');
if ($_SERVER['PHP_SELF'] != "/inventory/index.php")
    header("Location: /inventory/index.php");
require_once('footer.php');
