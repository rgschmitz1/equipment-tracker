<?php
require_once('../header.php');
// This is required for older versions of php, this can be removed when php is updated
require_once('passwordLib.php');

// Clear error message
$error_msg = '';

// If the user isn't logged in, try to log them in
if (!isset($_SESSION['xes_adminid']) && isset($_POST['submit'])) {
    // grab username and password from user
    $user_username = $_POST['username'];
    $user_password = $_POST['password'];

    if (empty($user_username) || empty($user_password)) {
        $error_msg = 'You must enter a valid username and password to login.';
    } else {
        // lookup user from the database
        $data = $users_api->dbAdminUserLogin($user_username, $user_password);
        if ((count($data) == 0) || (count($data) == 1)) {
            $error_msg = 'You must enter a valid username and password to login.';
        } elseif (count($data) == 2) {
            if (password_verify($user_password, $data['password'])) {
                // Login is OK, set the SESSION username and id, then redirect to homepage
                $_SESSION['xes_username'] = $user_username;
                $_SESSION['xes_adminid'] = $data['id'];
                $users_api->dbClose();
                header('Location: ' . SITE_ROOT);
            } else {
                $error_msg = 'You must enter a valid username and password to login.';
            }
        } else {
            $error_msg = 'Duplicate username and password exists in database, admin must fix!';
        }
    }
}

echo "<div class='container'>\n";

// Check if user is already logged in
if (empty($_SESSION['xes_adminid'])) {
    if (!empty($error_msg)) {
?>
    <div class='alert alert-dismissible alert-danger'>
        <button type='button' class='close' data-dismiss='alert'>&times;</button>
        <p><?= $error_msg ?></p>
    </div>
<?php
    }
?>
    <div class='well center-login'>
        <legend>Admin Login</legend>
        <form method='post' action='<?= $_SERVER['PHP_SELF'] ?>'>
            <fieldset>
                <div class='form-group text-left'>
                    <input type='text' class='form-control' value='<?php if (!empty($user_username)) { echo $user_username; } ?>' name='username' placeholder='Username' required>
                </div>
                <div class='form-group text-left'>
                    <input type='password' class='form-control' name='password' placeholder='Password' required>
                </div>
                <div class='form-group'>
                    <button type='submit' name='submit' class='btn btn-primary'>Submit</button>
                </div>
            </fieldset>
        </form>
    </div>
<?php
} else {
?>
    <p>You are logged in as <b><?= $_SESSION['xes_username'] ?></b>.</p>
<?php
}
echo "</div>\n";

require_once('../footer.php');
