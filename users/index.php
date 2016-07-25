<?php
require_once('../header.php');
if (!$users_api->authorizeAdmin()) {
    function shutdown()
    {
        require_once('../footer.php');
    }
    register_shutdown_function('shutdown');
    exit('<div class="container">You must be an administrative user to access this page.</div>');
}
$results = $users_api->dbQueryUsers();
?>

<div class="container">

    <?php if (isset($_GET['user'])) { ?>
    <div class="alert alert-dismissible alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <p>Successfully added user <b><?= $_GET['user'] ?></b>.</p>
    </div>
    <?php } ?>

    <h2>User Index</h2>
    <p><a href="new.php">Add New User</a></p>

    <!-- Start modify form -->
    <form class="form-inline" action="<?= SITE_ROOT ?>/users/modify.php" method="get" role="form">
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>Username</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($results as $record) { ?>
            <tr>
                <td>
                    <?= $record['username'] ?>
                    <div style="float: right; text-align: right">
                        <button class="btn btn-danger btn-xs" onclick="deleteUser(<?= $record['id'] ?>)" type="button">Delete</button>
                    </div>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
    </form>
    <!-- End modify form -->

</div>

<?php
include('../footer.php');
