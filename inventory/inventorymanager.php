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

    // Unclaim all products for specific user
    function dbUnclaimAll($id) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "UPDATE products SET user_id='1' WHERE user_id=:id";
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
    // Update product
    function dbModifyProduct($product, $description, $serial) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "UPDATE products SET product=:product, description=:description, serial=:serial";
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
    }
    // Create new product
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
    }
    // Delete product
    function dbDeleteProduct($serial) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "DELETE FROM products WHERE serial=:serial";
        try {
            $sql = $dbc->prepare($query);
            $sql->bindParam(":serial", $serial);
            $sql->execute();
            return true;
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
            return false;
        }
    }
    // Check for duplicate product
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
    // Query one product by id
    function dbFetchProduct($id) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT product, description, serial FROM products WHERE id=:id";
        try {
            $sql = $dbc->prepare($query);
            $sql->bindParam(":id", $id);
            $sql->execute();
            $results = $sql->fetch(PDO::FETCH_ASSOC);
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
        }
        return $results;
    }
    // Query products by user_id
    function dbQueryUserProducts() {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if (!isset($_SESSION))
            session_start();
        $query = "SELECT a.*, b.username FROM products a, users b WHERE a.user_id=b.id AND user_id=" . $_SESSION['xes_userid'];
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
    // Query all products
    function dbQueryProducts() {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT a.*, b.username FROM products a, users b WHERE a.user_id=b.id";
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
    // Claim product
    function dbClaimProduct($id, $user_id) {
        $dbc = $this->dbConnect();
        $query = "UPDATE products SET user_id=:user_id WHERE id=:id";
        $query = $db->prepare($sql);
        $query->bindParam(":id", $id);
        $query->bindParam(":user_id", $user_id);
        $query->execute();
        return $query->rowCount();  //return the # of rows affected
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
