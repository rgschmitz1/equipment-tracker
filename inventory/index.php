<?php
require_once('../header.php');

$query = "SELECT a.*, b.username FROM products a, users b WHERE a.user_id=b.id";
$result = $api->dbQuery($query);
if (!$result)
    $api->dbError($query);
?>

<div class="container">
    <?php if (isset($_GET['item'])) { ?>
    <div class="alert alert-dismissible alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <p>Successfully added or updated inventory.</p>
    </div>
    <?php } ?>

    <h2>Inventory Management</h2>
    <p><a href="new.php">Add New Inventory</a></p>
</div>

<div class="container-fluid">
    <!-- Start modify form -->
    <form class="form-inline" action="<?= SITE_ROOT ?>/inventory/modify.php" method="get" role="form">
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>Product</th>
                <th>Description</th>
                <th>Serial</th>
                <th>Location</th>
                <th>Modify</th>
            </tr>
        </thead>
        <tbody>

        <?php while ($record = mysqli_fetch_array($result)) { ?>
            <tr>
                <td><?= $record['product'] ?></td>
                <td><?= $record['description'] ?></td>
                <td><?= $record['serial'] ?></td>
                <td><?= $record['username'] ?></td>
                <td style="padding-top: 0px; padding-bottom: 0px;">
                    <button type="submit" name="item_id" value="<?= $record['ItemID'] ?>" class="btn btn-default">Edit</button>
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
