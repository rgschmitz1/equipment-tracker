<?php
require_once('../startsession.php');
require_once('../dbmanager.php');
class UserManager extends DbManager {
    // Search LDAP server for provided username
    function ldapSearch($username, $returnfullname) {
        $ad = ldap_connect('ad')
            or die("Could not connect to LDAP server");
        if (!ldap_bind($ad)) {
            echo "LDAP bind anonymous failed...";
            return false;
        }
        $attribute = array('displayname');
        $result = ldap_list($ad, "ou=People,dc=x-es,dc=com", "(&(primaryGroupID=513)(userAccountControl=512)(uidNumber>=10000)(cn=$username))", $attribute);
        $entry = ldap_get_entries($ad, $result);
        ldap_close($ad);
        if (isset($entry[0]['displayname'][0])) {
            if (empty($returnfullname)) {
                return true;
            } else {
                return $entry[0]['displayname'][0];
            }
        } else {
            return false;
        }
    }
    // Email admin user
    function emailAdmin($username, $product, $serial) {
        $fullname = $this->ldapSearch($username, '1');
        $to = ADMIN_EMAIL;
        $subject = 'Inventory Webapp Checkout';
        $message = '<html><body>';
        $message .= "<b>$fullname</b> has checked out a <b>$product</b> with serial number ";
        $message .= "<a href='http://webapps.xes-mad.com/support/perl/apps/prodTracking/mfg.pl?mode=display&amp;serNum=$serial&amp;product=$product'>$serial</a>";
        $message .= ", login to the <a href='" . SITE_ROOT . "/users/adminlogin.php'>Inventory Webapp</a> to authorize claim.";
        $message .= "</body></html>";
        $headers = "From: $username@xes-mad.com\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        mail($to, $subject, $message, $headers);
    }
    // Login user
    function dbUserLogin($username) {
        if ($username == 'Unclaimed')
            return;
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT id FROM users WHERE username=:username";
        try {
            $sql = $dbc->prepare($query);
            $sql->bindParam(":username", $username);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
            return false;
        }
    }
    // Login admin user
    function dbAdminUserLogin($username) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT id, password FROM adminusers WHERE username=:username";
        try {
            $sql = $dbc->prepare($query);
            $sql->bindParam(":username", $username);
            $sql->execute();
            return $sql->fetch(PDO::FETCH_ASSOC);
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
            return false;
        }
    }
    // Create new user
    function dbCreateUser($username) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "INSERT INTO users (username) VALUES (:username)";
        try {
            $sql = $dbc->prepare($query);
            $sql->bindParam(":username", $username);
            $sql->execute();
            return $sql->rowCount();
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
            return false;
        }
    }
    // Delete user from database using id
    function dbDeleteUser($id) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "DELETE FROM users WHERE id=:id";
        try {
            $sql = $dbc->prepare($query);
            $sql->bindParam(":id", $id);
            $sql->execute();
            return true;
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
            return false;
        }
    }
    // Check for duplicate user
    function dbCheckDuplicateUser($username) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT id FROM users WHERE username=:username";
        try {
            $sql = $dbc->prepare($query);
            $sql->bindParam(":username", $username);
            $sql->execute();
            return $sql->rowCount();
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
            return false;
        }
    }
    // Query users
    function dbQueryUsers() {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT id, username FROM users WHERE username NOT LIKE 'Unclaimed' ORDER BY username";
        try {
            $sql = $dbc->prepare($query);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
            return false;
        }
    }
    // Check if user is logged in, otherwise redirect to login page
    function authenticateUser() {
        startAppSession();
        if (!isset($_SESSION['xes_userid']) && !isset($_SESSION['xes_adminid'])) {
            if (('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] != SITE_ROOT . '/users/login.php') &&
                ('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] != SITE_ROOT . '/users/adminlogin.php')) {
                header('Location: ' . SITE_ROOT . '/users/login.php');
            }
        }
    }
    // Check if administrative user is logged in
    function authorizeAdmin() {
        startAppSession();
        if (isset($_SESSION['xes_adminid'])) {
            return true;
        } else {
            return false;
        }
    }
}
