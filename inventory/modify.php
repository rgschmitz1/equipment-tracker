<?php
require_once('../header.php');
require_once('inventorymanager.php');
$inventory_api = new InventoryManager();

if (!isset($_GET['item_id']) && !isset($_POST['item_id'])) {
    header('Location: inded.php');
} elseif (isset($_GET['item_id']) && !empty($_GET['item_id'])) {
    $nav_after_mod = $_GET['navaftermod'];
    $itemid = $_GET['item_id'];
} elseif (isset($_POST['item_id']) && !empty($_POST['item_id'])) {
    $nav_after_mod = $_POST['navaftermod'];
    $itemid = $_POST['item_id'];
}

$updatelist = array('Product' => 'product',
                    'Description' => 'description',
                    'Serial' => 'serial');

$results = $inventory_api->dbFetchProduct($itemid);

// There should be only one product that matches the item id in the database
if (count($results) == 0) {
    exit("<div class='container'>Invalid itemid #$itemid, try again.</div>\n");
} elseif (count($results) == 3) {
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
        if (isset($_POST["$key"]) && !empty($_POST["$key"])) {
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

        if ($product == $results['product'] && $description == $results['description'] && $serial == $results['serial']) {
            $error_msg = 'One of the below fields must be edited to proceed';
        } elseif ($inventory_api->dbCheckDuplicateProduct($serial) == 0 || $serial == $previous_serial) {
            $result = $inventory_api->dbModifyProduct($product, $description, $serial, $itemid);
            if ($result == 1) {
                $inventory_api->dbClose();
                header("Location: $nav_after_mod?item");
            } else {
                $inventory_api->dbError();
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
        <legend>Modify Product</legend>
        <form class="form-horizontal" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
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
    echo "<input type='text'";
    if ($key == 'Serial') {
        echo " maxlength='8' pattern='\d{8}' value='" . str_pad($data['Serial'], 8, '0', STR_PAD_LEFT) . "'";
    } elseif ($key == 'Product') {
        echo " maxlength='30' value='" . $data["$key"] . "'";
    } elseif ($key == 'Description') {
        echo " maxlength='120' value='" . $data["$key"] . "'";
    }
    echo " class='form-control' name='$key' id='$key' placeholder='$key' required>\n";
    // If error is present with input, display error icon in input box
    if (isset($error["$key"]) && ($error["$key"])) {
        echo "<span class='glyphicon glyphicon-remove form-control-feedback' aria-hidden='true'></span>\n";
    }
    echo "</div>\n</div>\n";
}
?>
                <input type="hidden" name="item_id" value="<?= $itemid ?>">
                <input type="hidden" name="navaftermod" value="<?= $nav_after_mod ?>">
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
require_once('../footer.php');
