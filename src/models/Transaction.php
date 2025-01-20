<?php

namespace src\models;
use mysqli;
require_once __DIR__ . '/../../database/PaymentDatabase.php';
use PaymentDatabase;

class Transaction
{
    private $db;

    public function __construct()
    {
        $this->db = new PaymentDatabase;
    }

    public function addTransaction($data)
    {
        // Prepare Query
        $this->db->query('INSERT INTO transactions (id, customer_id, product, amount, currency, status) VALUES(:id, :customer_id, :product, :amount, :currency, :status)');

        // Bind Values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':customer_id', $data['customer_id']);
        $this->db->bind(':product', $data['product']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':currency', $data['currency']);
        $this->db->bind(':status', $data['status']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getTransactions()
    {
        $this->db->query('SELECT * FROM transactions ORDER BY created_at DESC');

        $results = $this->db->resultset();

        return $results;
    }

    public static function create($data) {
        $db = new PaymentDatabase();

        // Prepare SQL query to insert transaction data
        $db->query("INSERT INTO transactions (id, customer_id, product, amount, currency, status) VALUES (?, ?, ?, ?, ?, ?)");
        $db->bind(1, $data['id']);
        $db->bind(2, $data['customer_id']);
        $db->bind(3, $data['product']);
        $db->bind(4, $data['amount']);
        $db->bind(5, $data['currency']);
        $db->bind(6, $data['status']);

        // Execute the query
        if ($db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}