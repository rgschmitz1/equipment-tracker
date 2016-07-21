<?php
require_once('../header.php');

if (!isset($_GET['item_id']) && !isset($_POST['item_id'])) {
    header('Location: index.php');
} elseif (isset($_GET['item_id']) && !empty($_GET['item_id'])) {
    $itemid = $_GET['item_id'];
} elseif (isset($_POST['item_id']) && !empty($_POST['item_id'])) {
    $itemid = $_POST['item_id'];
}

$updatelist = array('Brand',
                    'Model',
                    'Description',
                    'Tested',
                    'ItemCondition',
                    'eBayItemNum',
                    'eBayApproxPrice',
                    'Location');

$query = "SELECT * FROM Inventory WHERE ItemID = '$itemid'";
$results = $api->dbQuery($query);

// There should be only one item that matches the item id in the database
if (mysqli_num_rows($results) == 0) {
    exit("<div class='container'>Invalid itemid #$itemid, try again.</div>");
} elseif (mysqli_num_rows($results) == 1) {
    $record = mysqli_fetch_array($results);
    foreach ($updatelist as $key) {
        $data["$key"] = $record["$key"];
    }
}

// If user has submitted form, check user input
if (isset($_POST['submit'])) {
    $error_msg = '';
    // Check each input is set
    foreach ($updatelist as $key) {
        $error["$key"] = false;
        if (isset($_POST["$key"]) && !empty($_POST["$key"])) {
            $data["$key"] = $api->dbInputCheck($_POST["$key"]);
        } elseif (("$key" == 'Tested') && empty($_POST["$key"])) {
            $data["$key"] = 0;
        } else {
            $error_msg = 'All fields must be filled in, try again.';
            $error["$key"] = true;
        }
    }
    // If no errors exist, input item info into database
    if (empty($error_msg)) {
        $brand = $data['Brand'];
        $model = $data['Model'];
        $description = $data['Description'];
        $tested = $data['Tested'];
        $item_condition = $data['ItemCondition'];
        $ebay_item_num = $data['eBayItemNum'];
        $ebay_approx_price = $data['eBayApproxPrice'];
        $location = $data['Location'];

        $query = "UPDATE Inventory SET Brand = '$brand', Model = '$model', Description = '$description',
            Tested = '$tested', ItemCondition = '$item_condition', eBayItemNum = '$ebay_item_num',
            eBayApproxPrice = '$ebay_approx_price', Location = '$location' WHERE ItemID = '$itemid'";

        $result = $api->dbQuery($query);
        if (!$result) {
            echo $query;
            $api->dbError();
        } else {
            $api->dbClose();
            header("Location: index.php?item");
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

$list = array('Brand' => 'Brand',
              'Model' => 'Model',
              'Description' => 'Description',
              'Tested' => 'Tested',
              'ItemCondition' => 'Condition',
              'eBayItemNum' => 'eBay Item #',
              'eBayApproxPrice' => 'eBay Approx. Price',
              'Location' => 'Location');
?>

<div class="well">
    <legend>Modify Item</legend>
    <form class="form-horizontal" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
        <fieldset>

        <?php
        // Display modify item form below
        echo "<input type='hidden' name='item_id' value='$itemid'>";
        foreach ($list as $key => $value) {
            // Highlight form input as in error if flagged as having an issue
            if (isset($error["$key"]) && ($error["$key"])) {
                echo '<div class="form-group has-error has-feedback">';
            } else {
                echo '<div class="form-group">';
            }
            echo "<label for='$key' class='col-sm-2 control-label'>$value</label>";
            if ($key == 'Description') {
                echo "<div class='col-sm-10'>";
            } else {
                echo "<div class='col-sm-3'>";
            }
            if ($key != 'Tested') {
                echo "<input type='text' class='form-control' name='$key' id='$key' value='" . $data["$key"] . "' placeholder='$value'>";
            } else {
            ?>
                Yes <input id='<?= $key ?>' name='<?= $key ?>' type='radio' value='1' <?php if ($data["$key"] == 1) echo "checked='checked'" ?>>
                No <input id='<?= $key ?>' name='<?= $key ?>' type='radio' value='0' <?php if ($data["$key"] == 0) echo "checked='checked'" ?>>
            <?php
            }
            // If error is present with input, display error icon in input box
            if (isset($error["$key"]) && ($error["$key"])) {
                echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
            }
            echo '</div></div>';
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

<?php
echo '</div>';
include('../footer.php');
