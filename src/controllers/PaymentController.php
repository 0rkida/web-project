<?php

namespace App\controllers;

use AllowDynamicProperties;
require_once __DIR__ . '/../models/Payment.php';
use App\models\Payment;
use Exception;

#[AllowDynamicProperties]
class PaymentController
{
    private Payment $payment;

    public function __construct($dbConnection)
    {
        $this->payment = new Payment($dbConnection);
    }

    public function initializePayment(): void
    {
        try {
            // Process the input from paymentInit.php
            $jsonStr = file_get_contents('php://input');
            $jsonObj = json_decode($jsonStr);

            if ($jsonObj->request_type === 'create_payment_intent') {
                $amount = round($this->payment->itemPrice * 100); // Convert amount to cents
                $paymentData = $this->payment->createPaymentIntent($amount, $this->payment->currency, $this->payment->itemName);

                echo json_encode($paymentData);
            } elseif ($jsonObj->request_type === 'create_customer') {
                $output = $this->payment->createCustomer($jsonObj);
                echo json_encode($output);
            } elseif ($jsonObj->request_type === 'payment_insert') {
                $output = $this->payment->insertPayment($jsonObj);
                echo json_encode($output);
            } else {
                throw new Exception("Invalid request type.");
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getPaymentStatus(): void
    {
        try {
            // Validate the payment ID
            $paymentId = !empty($_GET['pid']) ? base64_decode($_GET['pid']) : null;

            if (!$paymentId) {
                header("Location: payment.php");
                exit;
            }

            $paymentDetails = $this->payment->getPaymentDetails($paymentId);

            if ($paymentDetails) {
                $status = 'success';
                $statusMsg = 'Your Payment has been Successful!';
                include __DIR__ . '/../views/paymentStatus.php';
            } else {
                throw new Exception("Transaction has been failed!");
            }
        } catch (Exception $e) {
            $status = 'error';
            $statusMsg = $e->getMessage();
            include __DIR__ . '/../views/paymentStatus.php';
        }
    }
}
