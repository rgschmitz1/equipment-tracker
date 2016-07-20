<?php require_once('header.php') ?>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" style="text-shadow: 2px 2px 1px #d0d0d0" href="<?= SITE_ROOT ?>">
                <span style="color:#000099; font-size:36px; font-family:times new roman">X</span><span style="color:#000000; font-size:30px; font-family:times new roman">-ES</span>
            </a>
        </div>

    <?php if (isset($_SESSION['id'])) { ?>

        <div class="collapse navbar-collapse" id="navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="<?= SITE_ROOT ?>/inventory/index.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Inventory<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?= SITE_ROOT ?>/inventory/new.php">New</a></li>
                        <li><a href="<?= SITE_ROOT ?>/inventory/index.php">Index</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="<?= SITE_ROOT ?>/users/index.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Users<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?= SITE_ROOT ?>/users/new.php">New</a></li>
                        <li><a href="<?= SITE_ROOT ?>/users/index.php">Index</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?= SITE_ROOT ?>/users/logout.php">Logout</a></li>
            </ul>
        </div>

    <?php } ?>

    </div>
</nav>