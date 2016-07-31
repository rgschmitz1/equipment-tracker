<?php
require_once('../dbmanager.php');
class InventoryManager extends DbManager {
    // Unclaim all products for specific user
    function dbClaimProduct($id, $user) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "UPDATE products SET user_id=:user WHERE id=:id";
        try {
            $sql = $dbc->prepare($query);
            $sql->bindParam(":id", $id);
            $sql->bindParam(":user", $user);
            $sql->execute();
            return true;
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
            return false;
        }
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
    function dbModifyProduct($product, $description, $serial, $id) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "UPDATE products SET product=:product, description=:description, serial=:serial WHERE id=:id";
        try {
            $sql = $dbc->prepare($query);
            $sql->bindParam(":product", $product);
            $sql->bindParam(":description", $description);
            $sql->bindParam(":serial", $serial);
            $sql->bindParam(":id", $id);
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
    function dbDeleteProduct($id) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "DELETE FROM products WHERE id=:id";
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
    // Query products
    function dbQueryProducts($keyword, $userid) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT a.*, b.username FROM products a, users b WHERE a.user_id=b.id";
        if (!empty($keyword))
            $query = "$query AND (serial LIKE :keyword1 OR product LIKE :keyword2 OR description LIKE :keyword3)";
        if (!empty($userid))
            $query = "$query AND user_id=:userid";
        $query = "$query ORDER BY serial";
        try {
            $sql = $dbc->prepare($query);
            if (!empty($keyword)) {
                $keyword = '%' . $keyword . '%';
                $sql->bindParam(':keyword1', $keyword);
                $sql->bindParam(':keyword2', $keyword);
                $sql->bindParam(':keyword3', $keyword);
            }
            if (!empty($userid)) {
                $sql->bindParam(':userid', $userid);
            }
            $sql->execute();
            $results = $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
        }
        return $results;
    }
}
