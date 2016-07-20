<?php
require_once('../appvars.php');

session_start();
if (isset($_SESSION['id'])) {
    // Delete session array
    $_SESSION = array();
    session_destroy();
}

// Redirect to the home page
header('Location: ' . SITE_ROOT);
