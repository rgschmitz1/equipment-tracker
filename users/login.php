<?php
require_once('../header.php');

// Clear error message
$error_msg = '';

// If the user isn't logged in, try to log them in
if (!isset($_SESSION['id']) && isset($_POST['submit'])) {
    // grab username from input
    $user_username = $_POST['username'];

    if (empty($user_username)) {
        $error_msg = 'You must enter a valid username to login.';
    } else {
        // lookup user from the database
        $data = $api->dbUserLogin($user_username);

        if (count($data) == 0) {
            $error_msg = 'Invalid username entered, try again.';
        } elseif (count($data) == 1) {
            // Login is OK, set the SESSION username and id, then redirect to homepage
            $_SESSION['username'] = $user_username;
            foreach ($data as $value) {
                $_SESSION['id'] = $value['id'];
            }
            $api->dbClose();
            header('Location: ' . SITE_ROOT);
        } else {
            $error_msg = 'Duplicate username exists in database, admin must fix!';
        }
    }
}

echo '<div class="container">';

// Check if user is already logged in
if (empty($_SESSION['id'])) {
    if (!empty($error_msg)) {
        ?>
        <div class="alert alert-dismisable alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <p><?= $error_msg ?></p>
        </div>
        <?php
    }
    ?>

    <div class="well center-login">
        <legend>Login</legend>
        <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
            <fieldset>
                <div class="form-group text-left">
                    <label for="username" class="control-label">Username</label>
                    <input type="text" class="form-control" value="<?php if (!empty($user_username)) { echo $user_username; } ?>"
                     id="username" name="username" placeholder="Username">
                </div>
                <div class="form-group">
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </div>
            </fieldset>
        </form>
    </div>

    <?php
} else {
    echo '<p>You are logged in as <b>' . $_SESSION['username'] . '</b>.</p>';
}

echo '</div>';

require_once('../footer.php');
