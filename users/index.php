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
    <div class='alert alert-success'>
        <p>Successfully added user <b><?= $_GET['user'] ?></b>.</p>
    </div>
<?php
}
?>
    <h2>User Index</h2>
    <p><a href='new.php'>Add New User</a></p>
    <table class='table table-bordered table-striped table-hover sticky-header'>
        <thead>
            <tr>
                <th class='col-xs-11'>Username</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
<?php
foreach ($results as $record) {
?>
            <tr>
                <td><?= $record['username'] ?></td>
<?php
    echo "<td><button onclick='alterstatus(this, \"" . $record['id'] . "\")' ";
    if (!empty($record['status']) && $record['status'] == 1) {
        echo "class='btn btn-success btn-no-pad'>Enabled</button></td>\n";
    } else {
        echo "class='btn btn-danger btn-no-pad'>Disabled</button></td>\n";
    }
?>
            </tr>
<?php
}
?>
        </tbody>
    </table>
</div>
<script>
function alterstatus(item, id) {
    if (item.className == 'btn btn-success') {
        item.className = 'btn btn-danger';
        item.textContent = 'Disabled';
        $.ajax('alterstatus.php', {'data':{'id':id, 'status':'0'}, 'method':'POST'});
    } else {
        item.className = 'btn btn-success';
        item.textContent = 'Enabled';
        $.ajax('alterstatus.php', {'data':{'id':id, 'status':'1'}, 'method':'POST'});
    }
}
$(document).ready(function(){
    // Add sticky header
    $('.sticky-header').floatThead({
        top:50
    });
});
</script>
<?php
require_once('../footer.php');
