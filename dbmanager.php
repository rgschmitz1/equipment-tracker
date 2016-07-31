<?php
abstract class DbManager {
    // Set database connect variable
    protected function dbConnect() {
        $dbc = new PDO('mysql:host=localhost;dbname=mfgtest', 'root', '')
            or exit('Error connecting to MySQL server.');
        return $dbc;
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
