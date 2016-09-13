<?php
require_once('users/usermanager.php');
$users_api = new UserManager();
$admin_user = $users_api->authorizeAdmin();
?>
<nav class='navbar navbar-default navbar-fixed-top'>
    <div class='container'>
        <div class='navbar-header'>
            <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar-collapse-1'>
                <span class='sr-only'>Toggle navigation</span>
                <span class='icon-bar'></span>
                <span class='icon-bar'></span>
                <span class='icon-bar'></span>
            </button>
            <a class='navbar-brand' href='<?= SITE_ROOT ?>'><?= SITE_TITLE ?></a>
        </div>
<?php
if (isset($_SESSION['xes_userid']) || isset($_SESSION['xes_adminid'])) {
?>
        <div class='collapse navbar-collapse' id='navbar-collapse-1'>
            <ul class='nav navbar-nav'>
<?php
    if (!$admin_user) {
?>
                <li><a href='<?= SITE_ROOT ?>/inventory/myindex.php'>My Inventory</a></li>
<?php
    }
?>
                <li class='dropdown'>
                    <a href='<?= SITE_ROOT ?>/inventory/index.php' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-expanded='false'>Inventory<span class='caret'></span></a>
                    <ul class='dropdown-menu' role='menu'>
<?php
    if ($admin_user) {
?>
                        <li><a href='<?= SITE_ROOT ?>/inventory/authorize.php'>Authorize</a></li>
                        <li class='divider'></li>
<?php
    }
?>
                        <li><a href='<?= SITE_ROOT ?>/inventory/index.php'>Index</a></li>
                        <li><a href='<?= SITE_ROOT ?>/inventory/claimedindex.php'>Claimed</a></li>
                        <li><a href='<?= SITE_ROOT ?>/inventory/new.php'>New</a></li>
                    </ul>
                </li>
<?php
    if ($admin_user) {
?>
                <li><a href='<?= SITE_ROOT ?>/users/index.php'>Users</a></li>
<?php
    }
?>
            </ul>
            <ul class='nav navbar-nav navbar-right'>
                <li><a href='<?= SITE_ROOT ?>/users/logout.php'>Logout</a></li>
            </ul>
            <div id='filterbox-container' class='navbar-form navbar-right'>
                <input id='filterbox' type='text' class='form-control' placeholder='Search Inventory'>
            </div>
        </div>
<?php
}
?>
    </div>
</nav>
