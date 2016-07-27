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
                        <a href="delete.php?id=<?= $record['id'] ?>&user=<?= $record['username'] ?>" class="btn btn-danger btn-xs" type="button">Delete</a>
                    </div>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php
include('../footer.php');
