<?php
class UserManager {
    // Define database connection constants
    const DB_USER = 'root';
    const DB_PASSWORD = '';

    // Set database connect variable
    private function dbConnect() {
        $dbc = new PDO('mysql:host=localhost;dbname=mfgtest', self::DB_USER, self::DB_PASSWORD)
            or exit('Error connecting to MySQL server.');
        return $dbc;
    }

    // Check for duplicate user
    function dbCheckTransactions($username, $request) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT request FROM transactions WHERE username=:username AND request=:request";
        try {
            $sql = $dbc->prepare($query);
            $sql->bindParam(":username", $username);
            $sql->bindParam(":request", $request);
            $sql->execute();
            return $sql->rowCount();
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
        }
    }
    // Login user
    function dbUserLogin($username) {
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
        $query = "SELECT id FROM users WHERE username=:username AND password=:password";
        try {
            $sql = $dbc->prepare($query);
            $sql->bindParam(":username", $username);
            $sql->bindParam(":password", $password);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
        }
    }
    // Create new user
    function dbCreateUser($username, $password) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
        try {
            $sql = $dbc->prepare($query);
            $sql->bindParam(":username", $username);
            $sql->bindParam(":password", $password);
            $sql->execute();
            return $sql->rowCount();
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
        }
        return $db->lastInsertId();
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
    // Query database
    function dbQueryUsers() {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT * FROM users";
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

    // Close database connection
    function dbClose() {
        $dbc = $this->dbConnect();
        $dbc = null;
    }

    // Return database error
    function dbError() {
        exit("Database query error.");
    }

    // Check if user is logged in, otherwise redirect to login page
    function authenticateUser() {
        if (!isset($_SESSION))
            session_start();
        if (!isset($_SESSION['id'])) {
            $site_root = '/xes';
            if (($_SERVER['PHP_SELF'] != "$site_root/users/login.php") && ($_SERVER['PHP_SELF'] != "$site_root/users/new.php")) {
                header("Location: $site_root/users/login.php");
            }
        }
    }

    // Check if administrative user is logged in
    function authorizeAdmin() {
        if (!isset($_SESSION))
            session_start();
        if (!isset($_SESSION['id']) || ($_SESSION['id'] != self::ADMIN_ID)) {
            return false;
        } else {
            return true;
        }
    }
}
