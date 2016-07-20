<?php
require_once('../header.php');

$list = array('username' => 'Username',
              'password' => 'Password',
              'PasswordConfirmation' => 'Confirm Password');

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
    // Check that password and confirmation password match
    if (empty($error_msg) && ($data['password'] != $data['PasswordConfirmation'])) {
        $error_msg = 'Password and confirmation password does not match, try again.';
        $error['password'] = true;
        $error['PasswordConfirmation'] = true;
    }
    // Check for duplicate username exists in database
    if (empty($error_msg)) {
        $duplicate_username_results = $api->dbCheckDuplicateUser($data['username']);

        if ($duplicate_username_results > 0) {
            $error_msg = 'Username <b>' . $data['username'] . '</b> already exists in database.';
            $data['username'] = '';
            $error['username'] = true;
        }
    }
    // If no errors exist, input user info into database
    if (empty($error_msg)) {
        $username = $data['username'];
        $password = sha1($data['password']);

        $result = $api->dbCreateUser($username, $password);
        if ($result = 0) {
            $api->dbError();
        } else {
            $api->dbClose();
            header("Location: index.php?user=$username");
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
    <legend>Add User</legend>
    <form class="form-horizontal" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
        <fieldset>

        <?php
        // Display new user form below
        foreach ($list as $key => $value) {
            // Highlight form input as in error if flagged as having an issue
            if (isset($error["$key"]) && ($error["$key"])) {
                echo '<div class="form-group has-error has-feedback">';
            } else {
                echo '<div class="form-group">';
            }
            echo "<label for='$key' class='col-sm-2 control-label'>$value</label>";
            echo "<div class='col-sm-3'>";
            // Change form input type to password for appropriate fields
            if (("$key" == 'password') || ("$key" == 'PasswordConfirmation')) {
                echo "<input type='password' class='form-control' name='$key' id='$key' placeholder='$value'>";
            } else {
                ?>
                <input type='text' class='form-control' name='<?= $key ?>' id='<?= $key ?>' <?php if (!empty($data["$key"])) { echo "value='" . $data["$key"] . "'" ; } ?> placeholder='<?= $value ?>'>
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
