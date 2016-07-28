<div class="container">
    <?php if (isset($_GET['item'])) { ?>
    <div class="alert alert-dismissible alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <p>Successfully added or updated inventory.</p>
    </div>
    <?php } ?>

    <h2><?= $inventory_header_title ?></h2>
    <form role="search">
        <div class="form-group" style="float: right">
            <input class="form-control" placeholder="Search" type="text">
        </div>
    </form>
    <p><a href="new.php">Add New Product</a></p>
    <!-- Start modify form -->
    <form class="form-inline" action="modify.php" method="get" role="form">
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
        <?php foreach ($results as $record) { ?>
            <tr>
                <td><a href="http://webapps.xes-mad.com/support/perl/apps/prodTracking/mfg.pl?mode=display&amp;serNum=<?= $record['serial'] ?>&amp;product=<?= $record['product'] ?>"><?= $record['serial'] ?></a></td>
                <td><?= $record['description'] ?></td>
            <?php
            if ($users_api->authorizeAdmin()) {
                echo "<td>" . $record['username'] . "</td>\n";
            } else {
                echo "<td style='padding-top: 0px; padding-bottom: 0px'>\n";
                if ($record['username'] == 'Unclaimed') {
                    echo "<button class='btn btn-default' ";
                } elseif ($record['username'] == $_SESSION['xes_username']) {
                    echo "<button class='btn btn-primary' ";
                } else {
                    echo "<button class='btn btn-danger' ";
                }
                // This doesn't work yet...
                echo "id='claim' onclick='claimBoard()' type='button'>" . $record['username'] . "</button>\n</td>\n";
            }
            ?>
                <td style="padding-top: 0px; padding-bottom: 0px">
                    <button class="btn btn-default" type="submit" name="item_id" value="<?= $record['id'] ?>">Edit</button>
                    <a href="delete.php?serial=<?= $record['serial'] ?>&amp;product=<?= $record['product'] ?>" class="btn btn-danger" type="button">Delete</a>
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
