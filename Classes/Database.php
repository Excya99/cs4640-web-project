<?php

class Database {
    private $mysqli;

    public function __construct() {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->mysqli = new mysqli(Config::$db["host"], Config::$db["user"], Config::$db["pass"], Config::$db["database"]);
    }

    // Prepared statement to protect against SQL injection attacks
    public function query($query, $bparam=null, ...$params) {
        $statement = $this->mysqli->prepare($query);
        if ($bparam != null) {
            $statement->bind_param($bparam, ...$params);
        }
        if (!$statement->execute()) {
            return false;
        }
        if (($result = $statement->get_result()) !== false) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return true;
    }
}