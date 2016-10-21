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
        <p>Successfully added or updated equipment.</p>
    </div>
<?php
}
?>
    <h2><?= $equipment_header_title ?></h2>
    <!-- Search bar -->
    <div class='row'>
        <div class='col-xs-3'>
            <a href='new.php'>Add New Product</a>
        </div>
    </div>
    <br />
</div>
<div class='container-fluid'>
    <table id='equipment' class='table table-bordered table-striped table-hover' cellspacing='0' width='100%'>
        <thead>
            <tr>
                <th>Serial</th>
                <th>Description</th>
                <th>Cfg</th>
                <th>Rev</th>
                <th>ECO</th>
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
               <td colspan="8" class="dataTables_empty">Loading data from server</td>
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
    return "<a id='history" + data['serial'] + "' href='history.php?id=" + data['product_id'] + "&amp;serial=" + data['serial'] + "'>" + data['claim_date'].substring(0, 10) + "</a>";
}
function claim(data) {
    var username = "<?php if (isset($_SESSION['xes_username'])) echo $_SESSION['xes_username'] ?>";
    if (data['username'] == 'Unclaimed') {
        claimurl = "<button class='btn btn-default btn-no-pad'";
    } else if (data['username'] == username) {
        claimurl = "<button class='btn btn-primary btn-no-pad'";
    } else {
        claimurl = "<button class='btn btn-danger btn-no-pad'";
    }
    claimurl += " onclick='claimitem(this, \"" + data['product'] + "\", \"" + data['serial'] + "\")' value='" + data['product_id'] + "'>" + data['username'] + "</button>";
    return claimurl;
}
function claimitem(item, product, serial) {
    var userid = "<?php if (isset($_SESSION['xes_userid'])) echo $_SESSION['xes_userid'] ?>";
    var username = "<?php if (isset($_SESSION['xes_username'])) echo $_SESSION['xes_username'] ?>";
    var fullname = "<?php if (isset($_SESSION['fullname'])) echo $_SESSION['fullname'] ?>";
    var claimurl = 'http://webapps.xes-mad.com/support/perl/apps/prodTracking/mfg.pl';
    // Update Last Claimed field text
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    if (dd < 10) dd = '0' + dd;
    if (mm < 10) mm = '0' + mm;
    today = yyyy + '-' + mm + '-' + dd;
    var hrefid = '#history' + serial;
    $(hrefid).text(today);
    // Update location history
    if (item.className == 'btn btn-primary btn-no-pad') {
        item.className = 'btn btn-default btn-no-pad';
        item.textContent = 'Unclaimed';
        $.ajax("claim.php", {"data":{"claim":item.value, "user":"1"}, "method":"POST"});
        // Update product tracking
        $.ajax(claimurl, {"data":{"mode":"display", "serNum":serial, "product":product, "mode":"Claim", "location":"<?= SITE_TITLE ?>"}, "method":"GET"});
<?php
$present_site = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
if ($present_site != SITE_ROOT . '/equipment/index.php') {
?>
        location.reload();
<?php
}
?>
    } else {
        item.className = 'btn btn-primary btn-no-pad';
        item.textContent = username;
        $.ajax("claim.php", {"data":{"claim":item.value, "user":userid, "product":product, "serial":serial}, "method":"POST"});
        // Update product tracking
        $.ajax(claimurl, {"data":{"mode":"display", "serNum":serial, "product":product, "mode":"Claim", "location":fullname}, "method":"GET"});
    }
}
function edit(data) {
    editurl = "<form action='modify.php' method='get' role='form'>";
    editurl += "<input type='hidden' name='navaftermod' value='<?= $_SERVER['PHP_SELF'] ?>'>";
    editurl += "<button class='btn btn-default btn-no-pad' type='submit' name='item_id' value='" + data['product_id'] + "'>Edit</button>";
    editurl += "</form>";
    return editurl;
}

$(document).ready(function(){
    // Configure dataTables
    var equipmentTable = $('#equipment').DataTable({
        "pageLength": 25,
        "dom": "t<'row'<'col-sm-4'l><'col-sm-4 text-center'i><'col-sm-4'p>>",
        "ajax": {
<?php
$present_site = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
if ($present_site == SITE_ROOT . '/equipment/myindex.php') {
?>
            "url": "json_data.php?user_id=<?php if (isset($_SESSION['xes_userid'])) echo $_SESSION['xes_userid'] ?>"
<?php
} else if ($present_site == SITE_ROOT . '/equipment/claimedindex.php') {
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
            {"data": null, "width": "5%", "searchable": true, "render": serial},
            {"data": "description", "width": "55%", "searchable": true},
            {"data": "cfgnum", "width": "7%", "searchable": true},
            {"data": "revision", "width": "3%", "searchable": true},
            {"data": "eco", "width": "3%", "searchable": true},
            {"data": null, "width": "7%", "searchable": true, "render": history},
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
        equipmentTable.search(this.value).draw();
    });
    // Add sticky header to equipment page
    $('#equipment').floatThead({
        top:50
    });
});
</script>
<?php
require_once('../footer.php');
