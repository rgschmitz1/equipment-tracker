<?php
require_once('../header.php');
echo "<div class='container'>\n";
if (isset($error_msg) && !empty($error_msg)) {
?>
    <div class='alert alert-danger'>
        <p><?= $error_msg ?></p>
    </div>
<?php
}
if (isset($_GET['item'])) {
?>
    <div class='alert alert-success'>
        <p>Successfully added or updated inventory.</p>
    </div>
<?php
}
?>
    <h2><?= $inventory_header_title ?></h2>
    <!-- Search bar -->
    <div class='row'>
        <div class='col-xs-3'>
            <a href='new.php'>Add New Product</a>
        </div>
    </div>
    <br />
</div>
<div class='container-fluid'>
    <table id='inventory' class='table table-bordered table-striped table-hover' cellspacing='0' width='100%'>
        <thead>
            <tr>
                <th>Serial</th>
                <th>Description</th>
                <th>Last Claimed</th>
                <th>Location</th>
<?php
if ($users_api->authorizeAdmin()) {
    echo "<th>Modify / Delete</th>\n";
} else {
    echo "<th>Modify</th>\n";
}
?>
            </tr>
        </thead>
        <tbody>
            <tr>
               <td colspan="5" class="dataTables_empty">Loading data from server</td>
            </tr>
        </tbody>
    </table>
</div>
<script>
// DataTables configuration
function serial(data) {
    return "<a href='http://webapps.xes-mad.com/support/perl/apps/prodTracking/mfg.pl?mode=display&amp;serNum=" + data['serial'] + "&amp;product=" + data['product'] + "'>" + data['serial'] + "</a>";
}
function history(data) {
    return "<a href='history.php?id=" + data['product_id'] + "&amp;serial=" + data['serial'] + "'>" + data['claim_date'].substring(0, 10) + "</a>";
}
function claim(data) {
    var userid = "<?php if (isset($_SESSION['xes_userid'])) echo $_SESSION['xes_userid'] ?>";
    var username = "<?php if (isset($_SESSION['xes_username'])) echo $_SESSION['xes_username'] ?>";
    if (data['username'] == 'Unclaimed') {
        claimurl = "<button class='btn btn-default'";
    } else if (data['username'] == username) {
        claimurl = "<button class='btn btn-primary'";
    } else {
        claimurl = "<button class='btn btn-danger'";
    }
    claimurl += " style='padding-top: 0px; padding-bottom: 0px' id='claim' onclick='claimitem(this, \"" + username + "\", \"" + userid + "\", \"" + data['product'] + "\", \"" + data['serial'] + "\")' value='" + data['product_id'] + "'>" + data['username'] + "</button>";
    return claimurl;
} 
function claimitem(item, username, userid, product, serial) {
    if (item.className == 'btn btn-primary') {
        item.className = 'btn btn-default';
        item.textContent = 'Unclaimed';
        $.ajax("claim.php", {"data":{"claim":item.value, "user":"1"}, "method":"POST"});
    } else {
        item.className = 'btn btn-primary';
        item.textContent = username;
        $.ajax("claim.php", {"data":{"claim":item.value, "user":userid, "product":product, "serial":serial}, "method":"POST"});
    }
}
function edit(data) {
    editurl = "<form action='modify.php' method='get' role='form'>";
    editurl += "<input type='hidden' name='navaftermod' value='<?= $_SERVER['PHP_SELF'] ?>'>";
    editurl += "<button style='padding-top: 0px; padding-bottom: 0px' class='btn btn-default' type='submit' name='item_id' value='" + data['product_id'] + "'>Edit</button>";
    editurl += "</form>";
    return editurl;
}

$(document).ready(function(){
    // Configure dataTables
    var inventoryTable = $('#inventory').DataTable({
        "pageLength": 25,
        "dom": "t<'row'<'col-sm-4'l><'col-sm-4 text-center'i><'col-sm-4'p>>",
        "ajax": {
<?php
$present_site = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
if ($present_site == SITE_ROOT . '/inventory/myindex.php') {
?>
            "url": "json_data.php?user_id=<?php if (isset($_SESSION['xes_userid'])) echo $_SESSION['xes_userid'] ?>"
<?php
} else if ($present_site == SITE_ROOT . '/inventory/claimedindex.php') {
?>
            "url": "json_data.php?claimed=1"
<?php
} else {
?>
            "url": "json_data.php"
<?php
}
?>
        },
        "columns": [
            {"data": null, "width": "10%", "searchable": true, "render": serial},
            {"data": "description", "width": "60%", "searchable": true},
            {"data": null, "width": "10%", "searchable": true, "render": history},
<?php
if ($users_api->authorizeAdmin()) {
?>
            {"data": "username", "width": "10%", "searchable": true},
<?php
} else {
?>
            {"data": null, "width": "10%", "searchable": true, "render": claim},
<?php
}
?>
            {"data": null, "width": "10%", "render": edit}
        ]
    });
    // Move product filter to navbar using filterbox id
    $('#filterbox').keyup(function() {
        inventoryTable.search(this.value).draw();
    });
    // Add sticky header to inventory page
    $('#inventory').floatThead({
        top:50
    });
});
</script>
<?php
require_once('../footer.php');
