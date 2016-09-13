<?php
require_once('../dbmanager.php');
class InventoryManager extends DbManager {
    // Claim product for a specific user
    function dbClaimProduct($id, $user) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "INSERT INTO claim_history (`user_id`, `product_id`, `claim_date`) VALUES (:user, :id, NOW())";
        try {
            $sql = $dbc->prepare($query);
            $sql->bindParam(":id", $id);
            $sql->bindParam(":user", $user);
            $sql->execute();
            $lastid = $dbc->lastInsertId();
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
            return false;
        }
        $query = "UPDATE products SET last_claim_id=$lastid WHERE id=:id";
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
    // Authorize user checkouts
    function dbAuthorizeClaim($id) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "UPDATE claim_history SET approved='1' WHERE id=:id";
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
    // Unclaim all products for a specific user (this will delete all users claim history)
    function dbUnclaimAll($id) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "DELETE FROM claim_history WHERE user_id=:id";
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
    // Modify product information
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
            return false;
        }
    }
    // Add new product
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
            // Create first claim entry for new product
            return $this->dbClaimProduct($dbc->lastInsertId(), '1');
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
            return false;
        }
    }
    // Delete product
    function dbDeleteProduct($id) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "DELETE a, b FROM products a INNER JOIN claim_history b ON a.id=b.product_id WHERE a.id=:id";
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
            return false;
        }
    }
    // Query all claim history by id
    function dbFetchClaimHistoryById($id) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT a.approved, a.claim_date, b.username FROM claim_history a, users b WHERE a.user_id=b.id AND a.product_id=:id ORDER BY a.id DESC";
        try {
            $sql = $dbc->prepare($query);
            $sql->bindParam(":id", $id);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
            return false;
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
            return $sql->fetch(PDO::FETCH_ASSOC);
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
            return false;
        }
    }
    // Query unapproved products
    function dbQueryUnapprovedProducts() {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT a.serial, a.description, b.id as claim_id, b.user_id, b.claim_date, c.username
                  FROM products a, claim_history b, users c
                  WHERE a.last_claim_id=b.id AND b.user_id=c.id
                  AND NOT b.user_id=1 AND b.approved IS NULL ORDER BY a.serial";
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
    // Query products
    function dbQueryProducts($user, $claimed) {
        $dbc = $this->dbConnect();
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT a.id AS product_id, a.product, a.description, a.serial, b.claim_date, b.user_id, c.username
                  FROM products AS a
                  INNER JOIN claim_history AS b ON a.last_claim_id=b.id";
        if (!empty($user))
            $query .= " AND b.user_id=:user";
        if (!empty($claimed))
            $query .= " AND NOT b.user_id=1";
        $query .= " INNER JOIN users AS c ON b.user_id=c.id";
        try {
            $sql = $dbc->prepare($query);
            if (!empty($user)) {
                $sql->bindParam(':user', $user);
            }
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
            return false;
        }
    }
    // Query valid products from prod_tracking.TagInfo
    function dbXesappsProducts() {
        $dbc = new PDO('mysql:host=db;dbname=xesapps', 'xes', 'xes-inc')
            or exit('Error connecting to MySQL server.');
        $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT DISTINCT(Product) AS Product FROM prod_tracking.TagInfo WHERE Company = 'Manufacturing - Madison' ORDER BY Product ASC";
        try {
            $sql = $dbc->prepare($query);
            $sql->execute();
            $results = $sql->fetchAll(PDO::FETCH_NUM);
        } catch(Exception $ex) {
            echo "what the heck<br />";
            echo $ex->getMessage();
            return false;
        }
        $dbc = null;
        return $results;
    }
}
