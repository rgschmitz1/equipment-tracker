<?php
require_once('../appvars.php');
session_start();
// Delete session array
$_SESSION = array();
session_destroy();
// Redirect to the home page
header('Location: ' . SITE_ROOT);
