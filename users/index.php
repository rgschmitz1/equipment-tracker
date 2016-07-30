<?php
require_once('../header.php');
if (!$users_api->authorizeAdmin()) {
    function shutdown()
    {
        require_once('../footer.php');
    }
    register_shutdown_function('shutdown');
    exit("<div class='container'>You must be an administrative user to access this page.</div>\n");
}
$results = $users_api->dbQueryUsers();

echo "<div class='container'>\n";

if (isset($_GET['user'])) {
?>
    <div class="alert alert-dismissible alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <p>Successfully added user <b><?= $_GET['user'] ?></b>.</p>
    </div>
<?php
}
?>
    <h2>User Index</h2>
    <p><a href="new.php">Add New User</a></p>
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>Username</th>
            </tr>
        </thead>
        <tbody>
<?php
foreach ($results as $record) {
?>
            <tr>
                <td>
                    <?= $record['username'] ?>

                    <button style="float: right" class="btn btn-danger btn-xs" type="button" data-toggle="modal" data-target="#deleteModal<?= $record['id'] ?>" data-backdrop="static">Delete</button>
                    <!-- Delete Modal -->
                    <div id="deleteModal<?= $record['id'] ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Delete User</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Please confirm you would like to delete user <b><?= $record['username'] ?></b>.</p>
                                </div>
                                <div class="modal-footer">
                                    <form action="delete.php" method="post" role="form">
                                        <button type="submit" name="delete" value="<?= $record['id'] ?>" class="btn btn-danger">Confirm</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
<?php
}
?>
        </tbody>
    </table>
</div>
<?php
include('../footer.php');
