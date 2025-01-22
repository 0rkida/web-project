<?php
namespace App\controllers;

use Payment;

require_once __DIR__ . '/../models/Payment.php';

class PaymentController {
    private $paymentModel;

    public function __construct() {
        $this->paymentModel = new Payment();
    }

    /**
     * Handle POST requests for creating a payment intent.
     */
    public function createPayment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve user input
            $amount = isset($_POST['amount']) ? floatval($_POST['amount']) * 100 : null; // Convert to cents
            $currency = isset($_POST['currency']) ? $_POST['currency'] : 'EUR';
            $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;

            // Validate inputs
            if (!$amount || !$userId) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input. Amount and User ID are required.']);
                return;
            }

            // Create a payment intent
            $paymentIntent = $this->paymentModel->createPaymentIntent($amount, $currency);

            if (isset($paymentIntent['error'])) {
                http_response_code(500);
                echo json_encode(['error' => $paymentIntent['error']]);
                return;
            }

            // Save the payment to the database
            $this->paymentModel->savePayment(
                $paymentIntent->id,
                $amount / 100, // Convert back to base currency
                $currency,
                'pending',
                $userId
            );

            // Return the client secret for Stripe
            echo json_encode(['clientSecret' => $paymentIntent->client_secret]);
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['error' => 'Invalid request method.']);
        }
    }

    /**
     * Handle GET requests to fetch a user's payment history.
     */
    public function getPaymentsByUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Retrieve the user ID from query parameters
            $userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;

            if (!$userId) {
                http_response_code(400);
                echo json_encode(['error' => 'User ID is required.']);
                return;
            }

            // Fetch payment records for the user
            $payments = $this->paymentModel->getPaymentsByUserId($userId);

            if ($payments) {
                echo json_encode($payments);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'No payments found for this user.']);
            }
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['error' => 'Invalid request method.']);
        }
    }

    /**
     * Handle POST requests to update payment status.
     */
    public function updatePaymentStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve user input
            $paymentIntentId = isset($_POST['payment_intent_id']) ? $_POST['payment_intent_id'] : null;
            $status = isset($_POST['status']) ? $_POST['status'] : null;

            // Validate inputs
            if (!$paymentIntentId || !$status) {
                http_response_code(400);
                echo json_encode(['error' => 'Payment Intent ID and Status are required.']);
                return;
            }

            // Update the payment status in the database
            $this->paymentModel->updatePaymentStatus($paymentIntentId, $status);
            echo json_encode(['success' => 'Payment status updated successfully.']);
        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['error' => 'Invalid request method.']);
        }
    }
}
