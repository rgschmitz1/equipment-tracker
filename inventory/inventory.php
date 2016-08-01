<?php
require_once('../header.php');
echo "<div class='container'>\n";
if (isset($error_msg) && !empty($error_msg)) {
?>
    <div class="alert alert-dismissible alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <p><?= $error_msg ?></p>
    </div>
<?php
}
if (isset($_GET['item'])) {
?>
    <div class="alert alert-dismissible alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <p>Successfully added or updated inventory.</p>
    </div>
<?php
}
?>
    <h2><?= $inventory_header_title ?></h2>
    <!-- Search bar -->
    <div class="row">
        <div class="col-xs-3">
            <a href="new.php">Add New Product</a>
        </div>
        <form action="<?= $goto_after_mod ?>" method="get" role="search">
            <div class="col-xs-offset-6 col-xs-3">
                <div class="input-group">
                    <input type="text" name="query" class="form-control" placeholder="Search"<?php if (isset($_GET['query'])) echo ' value="' . $_GET['query'] . '"'; ?>>
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                    </span>
                </div>
            </div>
        </form>
    </div>
    <br />
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>Serial</th>
                <th>Description</th>
                <th>Location</th>
                <th>Modify / Delete</th>
            </tr>
        </thead>
        <tbody>
<?php
foreach ($results as $record) {
?>
            <tr>
                <td><a href="http://webapps.xes-mad.com/support/perl/apps/prodTracking/mfg.pl?mode=display&amp;serNum=<?= $record['serial'] ?>&amp;product=<?= $record['product'] ?>"><?php echo str_pad($record['serial'], 8, '0', STR_PAD_LEFT); ?></a></td>
                <td><?php echo substr($record['description'], 0, 90); ?></td>
<?php
    if ($users_api->authorizeAdmin()) {
?>
                <td>
                    <?= $record['username'] ?>

<?php
    } else {
?>
                <td style="padding-top: 0px; padding-bottom: 0px">
                    <form action="claim.php" method="post" role="form">
                        <input type="hidden" name="navafterclaim" value="<?= $goto_after_mod ?>">
<?php
        if ($record['username'] == 'Unclaimed') {
            echo "<input type='hidden' name='user' value='" . $_SESSION['xes_userid'] . "'>\n";
            echo "<button class='btn btn-default'";
        } elseif ($record['username'] == $_SESSION['xes_username']) {
            echo "<input type='hidden' name='user' value='1'>\n";
            echo "<button class='btn btn-success'";
        } else {
            echo "<input type='hidden' name='user' value='" . $_SESSION['xes_userid'] . "'>\n";
            echo "<button class='btn btn-warning'";
        }
        echo " type='submit' name='claim' value='" . $record['id'] . "'>" . $record['username'] . "</button>\n";
        echo "</form>\n";
    }
?>
                </td>
                <td style="padding-top: 0px; padding-bottom: 0px">
                    <!-- Edit form -->
                    <form action="modify.php" method="get" role="form">
                        <input type="hidden" name="navaftermod" value="<?= $goto_after_mod ?>">
                        <button style="float: left; margin-right: 6px" class="btn btn-default" type="submit" name="item_id" value="<?= $record['id'] ?>">Edit</button>
                    </form>
                    <!-- Delete Modal -->
                    <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#deleteModal<?= $record['id'] ?>" data-backdrop="static">Delete</button>
                    <div id="deleteModal<?= $record['id'] ?>" class="modal" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Delete Product</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Please confirm you would like to delete <b><?= $record['product'] ?></b>, with serial number <b><?= $record['serial'] ?></b>.</p>
                                </div>
                                <div class="modal-footer">
                                    <form action="delete.php" method="post" role="form">
                                        <input type="hidden" name="navafterdel" value="<?= $goto_after_mod ?>">
                                        <input type="hidden" name="serial" value="<?= $record['serial'] ?>">
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
require_once('../footer.php');
