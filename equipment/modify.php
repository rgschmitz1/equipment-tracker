<?php
require_once('../header.php');
require_once('equipmentmanager.php');
$equipment_api = new EquipmentManager();
$admin_user = $users_api->authorizeAdmin();

if (!isset($_GET['item_id']) && !isset($_POST['item_id'])) {
    header('Location: index.php');
} elseif (isset($_GET['item_id']) && !empty($_GET['item_id'])) {
    $nav_after_mod = $_GET['navaftermod'];
    $itemid = $_GET['item_id'];
} elseif (isset($_POST['item_id']) && !empty($_POST['item_id'])) {
    $nav_after_mod = $_POST['navaftermod'];
    $itemid = $_POST['item_id'];
}

$updatelist = array('Product' => 'product',
                    'Description' => 'description',
                    'Serial' => 'serial',
                    'CfgNum' => 'cfgnum',
                    'Revision' => 'revision',
                    'ECO' => 'eco');

$results = $equipment_api->dbFetchProduct($itemid);

// There should be only one product that matches the item id in the database
if (count($results) == 0) {
    exit("<div class='container'>Invalid itemid #$itemid, try again.</div>\n");
} else {
    foreach ($updatelist as $key => $value) {
        $data["$key"] = $results["$value"];
        if ($key == 'Serial') {
            $previous_serial = $results["$value"];
        }
    }
}

// If user has submitted form, check user input
if (isset($_POST['submit'])) {
    $error_msg = '';
    // Check each input is set
    foreach ($updatelist as $key => $value) {
        $error["$key"] = false;
        if (!$admin_user && $key != 'Description') {
            continue;
        }
        if (($key == 'ECO') && (!isset($_POST["$key"]) || empty($_POST["$key"]))) {
            $data['ECO'] = '0';
        } elseif (isset($_POST["$key"]) && !empty($_POST["$key"])) {
            $data["$key"] = $_POST["$key"];
        } else {
            $error_msg = 'All fields must be filled in, try again.';
            $error["$key"] = true;
        }
    }
    // If no errors exist, input item info into database
    if (empty($error_msg)) {
        $product = $data['Product'];
        $description = $data['Description'];
        $serial = $data['Serial'];
        $cfgnum = $data['CfgNum'];
        $revision = $data['Revision'];
        $eco = $data['ECO'];

        if ($product == $results['product'] &&
            $description == $results['description'] &&
            $serial == $results['serial'] &&
            $cfgnum == $results['cfgnum'] &&
            $revision == $results['revision'] &&
            $eco == $results['eco']) {
            $error_msg = 'One of the below fields must be edited to proceed';
        } elseif ($equipment_api->dbCheckDuplicateProduct($serial) == 0 || $serial == $previous_serial) {
            $result = $equipment_api->dbModifyProduct($itemid, $product, $description, $serial, $cfgnum, $revision, $eco);
            if ($result == 1) {
                $equipment_api->dbClose();
                header("Location: $nav_after_mod?item");
            } else {
                $equipment_api->dbError();
            }
        } else {
            $error['Serial'] = true;
            $error_msg = 'Duplicate serial number found in database, check input and try again.';
        }
    }
}
echo "<div class='container'>\n";

// Check if errors exist in form
if (!empty($error_msg)) {
?>
    <div class='alert alert-dismissible alert-danger'>
        <button type='button' class='close' data-dismiss='alert'>&times;</button>
        <p><?= $error_msg ?></p>
    </div>
<?php
}
?>
    <div class='well'>
        <legend>Modify Product</legend>
        <form class='form-horizontal' action='<?= $_SERVER['PHP_SELF'] ?>' method='post'>
            <fieldset>
<?php
// Display modify item form below
foreach ($updatelist as $key => $value) {
    // Highlight form input as in error if flagged as having an issue
    if (isset($error["$key"]) && ($error["$key"])) {
        echo "<div class='form-group has-error has-feedback'>\n";
    } else {
        echo "<div class='form-group'>\n";
    }
    echo "<label for='$key' class='col-sm-2 control-label'>$key</label>\n";
    if ($key == 'Description') {
        echo "<div class='col-sm-10'>\n";
    } else {
        echo "<div class='col-sm-3'>\n";
    }
    switch ($key) {
        case 'Serial':
            echo "<input type='text' maxlength='8' pattern='\d{8}' class='form-control' name='$key' placeholder='$key'";
            if (!empty($data["$key"])) {
                echo " value='" . $data["$key"] . "'";
            }
            if ($admin_user) {
                echo " required>\n";
            } else {
                echo " disabled>\n";
            }
            break;
        case 'Product':
            // Generate a list of active products
            $products = $equipment_api->dbXesappsProducts();
            echo "<select class='form-control' name='$key'";
            if ($admin_user) {
                echo ">\n";
                foreach ($products as $product => $prodvalue) {
                    if ($data["$key"] == $prodvalue[0]) {
                        echo "<option value='$prodvalue[0]' selected>$prodvalue[0]</option>\n";
                    } else {
                        echo "<option value='$prodvalue[0]'>$prodvalue[0]</option>\n";
                    }
                }
            } else {
                echo " disabled>\n";
                echo "<option value='" . $data['Product'] . "' selected>" . $data['Product'] . "</option>\n";
            }
            echo "</select>\n";
            break;
        case 'Description':
            echo "<input type='text' maxlength='120' class='form-control' name='$key' placeholder='$key'";
            if (!empty($data["$key"])) {
                echo " value='" . $data["$key"] . "'";
            }
            echo " required>\n";
            break;
        case 'CfgNum':
            echo "<input type='text' maxlength='12' pattern='\d{8}-\d+' class='form-control' name='$key' placeholder='$key'";
            if (!empty($data["$key"])) {
                echo " value='" . $data["$key"] . "'";
            }
            if ($admin_user) {
                echo " required>\n";
            } else {
                echo " disabled>\n";
            }
            break;
        case 'Revision':
            echo "<input type='text' maxlength='3' class='form-control' name='$key' placeholder='$key'";
            if (!empty($data["$key"])) {
                echo " value='" . $data["$key"] . "'";
            }
            if ($admin_user) {
                echo " required>\n";
            } else {
                echo " disabled>\n";
            }
            break;
        case 'ECO':
            echo "<input type='text' maxlength='2' pattern='\d+' class='form-control' name='$key' placeholder='$key'";
            if (!empty($data["$key"])) {
                echo " value='" . $data["$key"] . "'";
            } else {
                echo " value='0'";
            }
            if ($admin_user) {
                echo " required>\n";
            } else {
                echo " disabled>\n";
            }
            break;
    }
    // If error is present with input, display error icon in input box
    if (isset($error["$key"]) && ($error["$key"])) {
        echo "<span class='glyphicon glyphicon-remove form-control-feedback' aria-hidden='true'></span>\n";
    }
    echo "</div>\n</div>\n";
}
?>
                <input type='hidden' name='item_id' value='<?= $itemid ?>'>
                <input type='hidden' name='navaftermod' value='<?= $nav_after_mod ?>'>
                <div class='form-group'>
                    <div class='col-sm-1 col-sm-offset-2'>
                        <button type='submit' name='submit' value='submit' class='btn btn-primary'>Submit</button>
                    </div>
<?php
if ($admin_user) {
?>
                    <div class='col-sm-offset-3'>
                        <button class='btn btn-danger' type='button' data-toggle='modal' data-target='#deleteModal' data-backdrop='static'>Delete</button>
                    </div>
<?php
}
?>
                </div>
            </fieldset>
        </form>
    </div>
<?php
if ($admin_user) {
?>
    <!-- Delete Modal -->
    <div id='deleteModal' class='modal' role='dialog'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                    <h4 class='modal-title'>Delete Product</h4>
                </div>
                <div class='modal-body'>
                    <p>Please confirm you would like to delete <b><?= $data['Product'] ?></b>, with serial number <b><?= $data['Serial'] ?></b>.</p>
                </div>
                <div class='modal-footer'>
                    <form action='delete.php' method='post' role='form'>
                        <input type='hidden' name='navafterdel' value='<?= $nav_after_mod ?>'>
                        <input type='hidden' name='serial' value='<?= $data['Serial'] ?>'>
                        <button type='submit' name='delete' value='<?= $itemid ?>' class='btn btn-danger'>Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal End -->
<?php
}
?>
</div>
<?php
require_once('../footer.php');
