<?php
require_once('../startsession.php');
startAppSession();
// Delete session array
$_SESSION = array();
session_destroy();
// Redirect to the home page
header('Location: ' . SITE_ROOT);
