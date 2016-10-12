<?php
// This is required for older versions of php, this can be removed when php is updated
require_once('passwordLib.php');
if (isset($_POST['submit'])) {
    if($_POST['password'] == $_POST['verifypassword']) {
        $options = array(
            'cost' => 12
        );
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
    } else {
        $errmsg = "Password and Verify Password do not match!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Password Generator</title>
</head>
<body>
    <form method='post' action='<?= $_SERVER['PHP_SELF'] ?>'>
        <input type='password' name='password' placeholder='Password' required>
        <input type='password' name='verifypassword' placeholder='Verify Password' required>
        <input type='submit' name='submit' value='submit'>
    </form>
<?php
if (isset($password) && !empty($password)) {
    echo "<p>Password hash is:</p><p><b>$password</b></p><br/>\n";
} elseif (isset($errmsg) && !empty($errmsg)) {
    echo "<p style='color:red'>$errmsg</p>\n";
}
?>
</body>
</html>
