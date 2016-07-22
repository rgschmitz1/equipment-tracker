<?php
require_once('../header.php');

// If user has submitted form, check user input
if (isset($_POST['submit'])) {
    $error_msg = '';
    // Check username input is set
    $data['username'] = '';
    $error['username'] = false;
    if (!isset($_POST['username']) || empty($_POST['username'])) {
        $error_msg = 'All fields must be filled in, try again.';
        $error['username'] = true;
    } else {
        $data['username'] = $_POST['username'];
    }
    // Check for duplicate username exists in database
    if (empty($error_msg)) {
        $duplicate_username_results = $users_api->dbCheckDuplicateUser($data['username']);

        if ($duplicate_username_results > 0) {
            $error_msg = 'Username <b>' . $data['username'] . '</b> already exists in database.';
            $data['username'] = '';
            $error['username'] = true;
        }
    }
    // If no errors exist, input user info into database
    if (empty($error_msg)) {
        $username = $data['username'];

        $result = $users_api->dbCreateUser($username);
        if ($result = 0) {
            $users_api->dbError();
        } else {
            $users_api->dbClose();
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
        // Highlight form input as in error if flagged as having an issue
        if (isset($error['username']) && ($error['username'])) {
            echo '<div class="form-group has-error has-feedback">';
        } else {
            echo '<div class="form-group">';
        }
        echo '<label for="username" class="col-sm-2 control-label">Username</label>';
        echo '<div class="col-sm-3">';
        echo '<input type="text" class="form-control" name="username" id="username" placeholder="Username"';
        if (!empty($data['username'])) { echo "value='" . $data['username'] . "'" ; }
        echo 'required>';
        // If error is present with input, display error icon in input box
        if (isset($error['username']) && ($error['username'])) {
            echo '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
        }
        echo '</div></div>';
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
