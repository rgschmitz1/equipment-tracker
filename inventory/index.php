<?php
require_once('../header.php');
require_once('inventorymanager.php');
$inventory_api = new InventoryManager();

$results = $inventory_api->dbQueryProducts();
?>

<div class="container">
    <?php if (isset($_GET['item'])) { ?>
    <div class="alert alert-dismissible alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <p>Successfully added or updated inventory.</p>
    </div>
    <?php } ?>

    <h2>Inventory Index</h2>
    <p><a href="new.php">Add New Product</a></p>
    <!-- Start modify form -->
    <form class="form-inline" action="<?= SITE_ROOT ?>/inventory/modify.php" method="get" role="form">
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>Serial</th>
                <th>Product</th>
                <th>Description</th>
                <th>Location</th>
                <th>Modify</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($results as $record) { ?>
            <tr>
                <td><?= $record['serial'] ?></td>
                <td><?= $record['product'] ?></td>
                <td><?= $record['description'] ?></td>
                <td style="padding-top: 0px; padding-bottom: 0px">
                    <button class="btn btn-default" id="btnUpdate" type="button"><?= $record['username'] ?></button>
                </td>
                <td style="padding-top: 0px; padding-bottom: 0px">
                    <button class="btn btn-default" type="submit" name="item_id" value="<?= $record['ItemID'] ?>">Edit</button>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
    </form>
    <!-- End modify form -->

</div>

<!-- This doesn't currently work
<script type="text/javascript">
    $(document).ready(function() {
        //js -> call InventoryService.php
        $("#btnUpdate").click(function() {
            $.ajax("InventoryService.php", {"data":{"id":$("#idToUpdate").val(),
                                                    "user_id":$("#user_id").val(),
                                                    "method":"PUT"});
        });
    })
</script>
-->

<?php
include('../footer.php');
