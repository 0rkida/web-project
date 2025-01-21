<?php

require_once ('C:\xampp\htdocs\web-project\config\paymentsConfig.php');
class PaymentDatabase {
    private $host = DB_HOST;
    private $user = DB_USERNAME; // Changed from DB_USER to DB_USERNAME
    private $pass = DB_PASSWORD; // Changed from DB_PASS to DB_PASSWORD
    private $dbname = DB_NAME;

    private $conn;
    private $stmt;
    private $error;

    public function __construct() {
        // Create a new MySQLi connection
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);

        // Check for connection errors
        if ($this->conn->connect_error) {
            $this->error = $this->conn->connect_error;
            die('Connection failed: ' . $this->error);
        }
    }

    // Prepare statement with query
    public function query($query) {
        $this->stmt = $this->conn->prepare($query);
        if (!$this->stmt) {
            die('Query preparation failed: ' . $this->conn->error);
        }
    }

    // Bind values
    public function bind($param, $value, $type = 's') {
        if (!isset($this->stmt)) {
            die('Statement not prepared.');
        }
        $this->stmt->bind_param($type, $value);
    }

    // Execute the prepared statement
    public function execute() {
        if (!$this->stmt->execute()) {
            die('Execution failed: ' . $this->stmt->error);
        }
        return true;
    }

    // Get result set as array of objects
    public function resultset() {
        $this->execute();
        $result = $this->stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get single record as object
    public function single() {
        $this->execute();
        $result = $this->stmt->get_result();
        return $result->fetch_object();
    }

    // Get record row count
    public function rowCount() {
        $this->execute();
        $result = $this->stmt->get_result();
        return $result->num_rows;
    }

    // Returns the last inserted ID
    public function lastInsertId() {
        return $this->conn->insert_id;
    }

    // Close the statement and connection
    public function close() {
        if ($this->stmt) {
            $this->stmt->close();
        }
        $this->conn->close();
    }
}
?>
