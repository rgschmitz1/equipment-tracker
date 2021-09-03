<?php
require_once('../header.php');

// Clear error message
$error_msg = '';

// If the user isn't logged in, try to log them in
if (!isset($_SESSION['xes_userid']) && isset($_POST['submit'])) {
    // grab username from input
    $user_username = $_POST['username'];

    if (empty($user_username)) {
        $error_msg = 'You must enter a valid username to login.';
    } else {
        // lookup user from the database
        $data = $users_api->dbUserLogin($user_username);

        if (!isset($data) || empty($data)) {
            // User does not exist in database, check LDAP server
            //if ($users_api->ldapSearch($user_username, '')) {
                if ($users_api->dbCreateUser($user_username)) {
                    $users_api->dbClose();
                } else {
                    $users_api->dbError();
                }
                $data = $users_api->dbUserLogin($user_username);
            //} else {
            //    $error_msg = 'Invalid username entered, try again.';
            //}
        }
        if (isset($data) && !empty($data) && isset($data['status']) && $data['status'] == 1) {
            // Login is OK, set the SESSION username and id, then redirect to homepage
            $_SESSION['fullname'] = $user_username;
            //$_SESSION['fullname'] = $users_api->ldapSearch($user_username, '1');
            $_SESSION['xes_username'] = $user_username;
            $_SESSION['xes_userid'] = $data['id'];
            $users_api->dbClose();
            echo "<script>window.location = '" . SITE_ROOT . "'</script>\n";
            //header('Location: ' . SITE_ROOT);
        } elseif (isset($data) && !empty($data)) {
            $error_msg = "User <b>$user_username</b> has been disabled, please email <a href='mailto:" . ADMIN_EMAIL . "'>" . ADMIN_EMAIL . "</a>.";
        } else {
            $error_msg = 'Invalid username entered, try again.';
        }
    }
}

echo "<div class='container'>\n";

// Check if user is already logged in
if (empty($_SESSION['xes_userid'])) {
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
        <legend>Login</legend>
        <form method='post' action='<?= $_SERVER['PHP_SELF'] ?>'>
            <fieldset>
                <div class='form-group text-left'>
                    <input type='text' class='form-control' value='<?php if (!empty($user_username)) { echo $user_username; } ?>' name='username' placeholder='Username' autofocus required>
                </div>
                <div class='form-group'>
                    <button type='submit' name='submit' class='btn btn-primary'>Submit</button>
                </div>
            </fieldset>
        </form>
        <p><a href='adminlogin.php'>Admin Login Page</a></p>
    </div>
<?php
} else {
?>
    <p>You are logged in as <b><?= $_SESSION['xes_username'] ?></b>.</p>
<?php
}
echo "</div>\n";
require_once('../footer.php');
