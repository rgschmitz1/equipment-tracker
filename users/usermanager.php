<?php
class UserManager extends DbManager {
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
        }
    }
    // Login admin user
    function dbAdminUserLogin($username, $password) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT id, password FROM adminusers WHERE username=:username";
        try {
            $sql = $dbc->prepare($query);
            $sql->bindParam(":username", $username);
            $sql->execute();
            $data = $sql->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $data['password']))
                return $data['id'];
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
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
        }
        return $db->lastInsertId();
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
        }
    }
    // Query users
    function dbQueryUsers() {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT * FROM users WHERE username NOT LIKE 'Unclaimed' ORDER BY username";
        try {
            $sql = $dbc->prepare($query);
            $sql->execute();
            $results = $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
        }
        return $results;
    }
    // Check if user is logged in, otherwise redirect to login page
    function authenticateUser() {
        if (!isset($_SESSION))
            session_start();
        if (!isset($_SESSION['xes_userid']) && !isset($_SESSION['xes_adminid'])) {
            if (('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] != SITE_ROOT . '/users/login.php') &&
                ('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] != SITE_ROOT . '/users/adminlogin.php')) {
                header('Location: ' . SITE_ROOT . '/users/login.php');
            }
        }
    }
    // Check if administrative user is logged in
    function authorizeAdmin() {
        if (!isset($_SESSION))
            session_start();
        if (isset($_SESSION['xes_adminid'])) {
            return true;
        } else {
            return false;
        }
    }
}
