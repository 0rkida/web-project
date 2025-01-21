<?php

namespace src\controllers;

require_once __DIR__ . '/../models/Customer.php';
require_once __DIR__ . '/../models/Transaction.php';

use src\models\Customer;
use src\models\Transaction;

class PaymentController
{
    private $customerModel;
    private $transactionModel;

    public function __construct()
    {
        $this->customerModel = new Customer();
        $this->transactionModel = new Transaction();
    }

    public function getView(): void {

        $customers = $this->customerModel->getCustomers();
        $transactions = $this->transactionModel->getTransactions();
        require_once 'C:\xampp\htdocs\web-project\payment\stripe\index.php'; // Vendosni rrugën e saktë për skedarin register.html
    }

    public function postPayment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize payment data
            $data = [
                'id' => uniqid(),
                'customer_id' => htmlspecialchars($_POST['customer_id']),
                'product' => htmlspecialchars($_POST['product']),
                'amount' => htmlspecialchars($_POST['amount']),
                'currency' => htmlspecialchars($_POST['currency']),
                'status' => 'Completed', // Assume success for now
            ];

            // Save transaction to database
            if ($this->transactionModel->addTransaction($data)) {
                header('Location: /success'); // Redirect to success page
                exit();
            } else {
                die('Error: Payment processing failed.');
            }
        } else {
            die('Error: Invalid request method.');
        }
    }
}