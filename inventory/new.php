<?php
require_once('../header.php');
require_once('inventorymanager.php');
$inventory_api = new InventoryManager();

$list = array('Product' => 'Product',
              'Description' => 'Description',
              'Serial' => 'Serial');

// If user has submitted form, check user input
if (isset($_POST['submit'])) {
    $error_msg = '';
    // Check each input is set
    while (list($key,) = each($list)) {
        $data["$key"] = '';
        $error["$key"] = false;
        if (!isset($_POST["$key"]) || empty($_POST["$key"])) {
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

        if ($inventory_api->dbCheckDuplicateProduct($serial) == 0) {
            $result = $inventory_api->dbNewProduct($product, $description, $serial);
            if ($result == 0) {
                $inventory_api->dbError();
            } else {
                $inventory_api->dbClose();
                header("Location: index.php?item");
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
    <div class="alert alert-dismissible alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <p><?= $error_msg ?></p>
    </div>
<?php
}
?>
    <div class="well">
        <legend>Add Product</legend>
        <form class="form-horizontal" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            <fieldset>
<?php

// Display new item form below
foreach ($list as $key => $value) {
    // Highlight form input as in error if flagged as having an issue
    if (isset($error["$key"]) && ($error["$key"])) {
        echo "<div class='form-group has-error has-feedback'>\n";
    } else {
        echo "<div class='form-group'>\n";
    }
    echo "<label for='$key' class='col-sm-2 control-label'>$value</label>\n";
    if ($key == 'Description') {
        echo "<div class='col-sm-10'>\n";
    } else {
        echo "<div class='col-sm-3'>\n";
    }
    if ($key == 'Serial') {
        echo "<input type='number' min='10000000' max='99999999'";
    } else {
        echo "<input type='text'";
    }
    echo " class='form-control' name='$key' id='$key' placeholder='$value'";
    if (!empty($data["$key"])) echo "value='" . $data["$key"] . "' ";
    echo " required>\n";
    // If error is present with input, display error icon in input box
    if (isset($error["$key"]) && ($error["$key"])) {
        echo "<span class='glyphicon glyphicon-remove form-control-feedback' aria-hidden='true'></span>\n";
    }
    echo "</div>\n</div>\n";
}
?>
                <div class="form-group">
                    <div class="col-sm-1 col-sm-offset-2">
                        <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>
<?php
include('../footer.php');
