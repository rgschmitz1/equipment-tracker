<?php
require_once('../header.php');
require_once('equipmentmanager.php');
$equipment_api = new EquipmentManager();

$list = array('Product',
              'Description',
              'Serial',
              'CfgNum',
              'Revision',
              'ECO');

// If user has submitted form, check user input
if (isset($_POST['submit'])) {
    $error_msg = '';
    // Check each input is set
    foreach ($list as $key) {
        $data["$key"] = '';
        $error["$key"] = false;
        if (($key == 'ECO') && (!isset($_POST["$key"]) || empty($_POST["$key"]))) {
            $data['ECO'] = '0';
        } elseif (!isset($_POST["$key"]) || empty($_POST["$key"])) {
            $error_msg = 'All fields must be filled in, try again.';
            $error["$key"] = true;
        } else {
            $data["$key"] = $_POST["$key"];
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

        if ($equipment_api->dbCheckDuplicateProduct($serial) == 0) {
            if ($equipment_api->dbNewProduct($product, $description, $serial, $cfgnum, $revision, $eco)) {
                $equipment_api->dbClose();
                header("Location: index.php?item");
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
        <legend>Add Product</legend>
        <form class='form-horizontal' action='<?= $_SERVER['PHP_SELF'] ?>' method='post'>
            <fieldset>
<?php

// Display new item form below
foreach ($list as $key) {
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
            echo " required>\n";
            break;
        case 'Product':
            // Generate a list of active products
            $products = $equipment_api->dbXesappsProducts();
            echo "<select class='form-control' name='$key'>\n";
            foreach ($products as $product => $value) {
                if (!empty($data["$key"]) && ($data["$key"] == $value[0])) {
                    echo "<option value='$value[0]' selected>$value[0]</option>\n";
                } else {
                    echo "<option value='$value[0]'>$value[0]</option>\n";
                }
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
            echo " required>\n";
            break;
        case 'Revision':
            echo "<input type='text' maxlength='3' class='form-control' name='$key' placeholder='$key'";
            if (!empty($data["$key"])) {
                echo " value='" . $data["$key"] . "'";
            }
            echo " required>\n";
            break;
        case 'ECO':
            echo "<input type='text' maxlength='2' pattern='\d+' class='form-control' name='$key' placeholder='$key'";
            if (!empty($data["$key"])) {
                echo " value='" . $data["$key"] . "'";
            }
            echo " required>\n";
            break;
    }
    // If error is present with input, display error icon in input box
    if (isset($error["$key"]) && ($error["$key"])) {
        echo "<span class='glyphicon glyphicon-remove form-control-feedback' aria-hidden='true'></span>\n";
    }
    echo "</div>\n</div>\n";
}
?>
                <div class='form-group'>
                    <div class='col-sm-1 col-sm-offset-2'>
                        <button type='submit' name='submit' value='submit' class='btn btn-primary'>Submit</button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>
<?php
require_once('../footer.php');
