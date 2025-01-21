<?php

namespace src\models;
use mysqli;
require_once ('C:\xampp\htdocs\web-project\config\paymentsConfig.php');
require_once __DIR__ . '/../../database/PaymentDatabase.php';
use PaymentDatabase;

class Customer
{
    private $db;

    public function __construct()
    {
        $this->db = new PaymentDatabase;
    }

    public function addCustomer($data)
    {
        // Prepare Query
        $this->db->query('INSERT INTO customers (id, first_name, last_name, email) VALUES(:id, :first_name, :last_name, :email)');

        // Bind Values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':email', $data['email']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getCustomers()
    {
        $this->db->query('SELECT * FROM customers ORDER BY created_at DESC');

        $results = $this->db->resultset();

        return $results;
    }

    public static function create($data) {
        $db = new PaymentDatabase();

        // Prepare SQL query to insert customer data
        $db->query("INSERT INTO customers (id, first_name, last_name, email) VALUES (?, ?, ?, ?)");
        $db->bind(1, $data['id']);
        $db->bind(2, $data['first_name']);
        $db->bind(3, $data['last_name']);
        $db->bind(4, $data['email']);

        // Execute the query
        if ($db->execute()) {
            return true;
        } else {
            return false;
        }
    }


}