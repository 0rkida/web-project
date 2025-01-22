<?php

require_once  'C:\xampp\htdocs\web-project\vendor\autoload.php';
require_once  'C:\xampp\htdocs\web-project\config\paymentsConfig.php';


class Payment {
    private $db;

    public function __construct() {
        $this->db = getDbConnection();
    }

    public function createPaymentIntent($amount, $currency = 'EUR') {
        \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

        try {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount,
                'currency' => $currency,
                'payment_method_types' => ['card'],
            ]);

            return $paymentIntent;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

    }

    public function savePayment(string $paymentIntentId, float $amount, string $currency, string $status, int $userId) {
        $stmt = $this->db->prepare(
            "INSERT INTO payments (user_id, amount, currency, payment_intent_id, status) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('idsss', $userId, $amount, $currency, $paymentIntentId, $status);

        if (!$stmt->execute()) {
            error_log("Error saving payment: " . $stmt->error);
        }

        $stmt->close();
    }


    public function updatePaymentStatus($paymentIntentId, $status) {
        $stmt = $this->db->prepare(
            "UPDATE payments SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE payment_intent_id = ?"
        );
        $stmt->bind_param('ss', $status, $paymentIntentId);

        if (!$stmt->execute()) {
            error_log("Error updating payment status: " . $stmt->error);
        }

        $stmt->close();
    }

    public function getPaymentByIntentId($paymentIntentId) {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE payment_intent_id = ?");
        $stmt->bind_param('s', $paymentIntentId);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $payment = $result->fetch_assoc();
            $stmt->close();
            return $payment;
        } else {
            error_log("Error fetching payment: " . $stmt->error);
            $stmt->close();
            return null;
        }
    }


    public function getPaymentsByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param('i', $userId);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $payments = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $payments;
        } else {
            error_log("Error fetching payments: " . $stmt->error);
            $stmt->close();
            return null;
        }
    }
}
