<?php
class InventoryManager {
    // Define database connection constants
    const DB_USER = 'root';
    const DB_PASSWORD = '';

    // Set database connect variable
    private function dbConnect() {
        $dbc = new PDO('mysql:host=localhost;dbname=mfgtest', self::DB_USER, self::DB_PASSWORD)
            or exit('Error connecting to MySQL server.');
        return $dbc;
    }

    // Create new user
    function dbNewProduct($product, $description, $serial) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "INSERT INTO products (`product`, `description`, `serial`) VALUES (:product, :description, :serial)";
        try {
            $sql = $dbc->prepare($query);
            $sql->bindParam(":product", $product);
            $sql->bindParam(":description", $description);
            $sql->bindParam(":serial", $serial);
            $sql->execute();
            return $sql->rowCount();
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
        }
        return $db->lastInsertId();
    }
    // Check for duplicate user
    function dbCheckDuplicateProduct($serial) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT id FROM products WHERE serial=:serial";
        try {
            $sql = $dbc->prepare($query);
            $sql->bindParam(":serial", $serial);
            $sql->execute();
            return $sql->rowCount();
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
        }
    }
    // Query database
    function dbQueryProducts() {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT * FROM products";
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
}
