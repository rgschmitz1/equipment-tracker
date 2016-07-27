<?php
require_once('../header.php');
require_once('inventorymanager.php');
$inventory_api = new InventoryManager();

if (!isset($_GET['item_id']) && !isset($_POST['item_id'])) {
    header('Location: index.php');
} elseif (isset($_GET['item_id']) && !empty($_GET['item_id'])) {
    $itemid = $_GET['item_id'];
} elseif (isset($_POST['item_id']) && !empty($_POST['item_id'])) {
    $itemid = $_POST['item_id'];
}

$updatelist = array('Product' => 'product',
                    'Description' => 'description',
                    'Serial' => 'serial');

$results = $inventory_api->dbFetchProduct($itemid);

// There should be only one item that matches the item id in the database
if (count($results) == 0) {
    exit("<div class='container'>Invalid itemid #$itemid, try again.</div>");
} elseif (count($results) == 3) {
    foreach ($updatelist as $key => $value) {
        $data["$key"] = $results["$value"];
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

        if ($inventory_api->dbCheckDuplicateProduct($serial) == 0) {
            $result = $inventory_api->dbModifyProduct($product, $description, $serial);
            if ($result == 1) {
                $inventory_api->dbClose();
                header("Location: index.php?item");
            } else {
                $inventory_api->dbError();
            }
        } else {
            $error['Serial'] = true;
            $error_msg = 'Duplicate serial number found in database, check input and try again.';
        }
    }
}

echo '<div class="container">';

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
        echo "<input type='hidden' name='item_id' value='$itemid'>";
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
            if ($key == 'Serial') {
                echo "<input type='number' min='10000000' max='99999999' ";
            } else {
                echo "<input type='text' ";
            }
            echo "class='form-control' name='$key' id='$key' value='" . $data["$key"] . "' placeholder='$key' required>\n";
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