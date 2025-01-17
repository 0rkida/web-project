<?php

namespace App\models;

use Stripe\StripeClient;

class Payment
{
    private $db;
    private $stripe;
    public $itemName = "Premium Subscription";
    public $itemPrice = 25.00; // Price in dollars
    public $currency = "EUR";

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
        $this->stripe = new StripeClient(STRIPE_SECRET_KEY);
    }

    /**
     * Create a new payment intent using Stripe.
     */
    public function createPaymentIntent($amount, $currency, $description): array
    {
        try {
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $amount,
                'currency' => $currency,
                'description' => $description,
                'payment_method_types' => ['card'],
            ]);

            return [
                'id' => $paymentIntent->id,
                'clientSecret' => $paymentIntent->client_secret,
            ];
        } catch (\Exception $e) {
            throw new \Exception("Error creating PaymentIntent: " . $e->getMessage());
        }
    }

    /**
     * Create a Stripe customer and update the payment intent with customer info.
     */
    public function createCustomer($jsonObj): array
    {
        $paymentIntentId = $jsonObj->payment_intent_id ?? null;
        $name = $jsonObj->name ?? '';
        $email = $jsonObj->email ?? '';

        try {
            // Retrieve existing PaymentIntent
            $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentIntentId);

            if (!empty($paymentIntent->customer)) {
                return ['customer_id' => $paymentIntent->customer];
            }

            // Create a new customer
            $customer = $this->stripe->customers->create([
                'name' => $name,
                'email' => $email,
            ]);

            // Update the PaymentIntent with the customer ID
            $this->stripe->paymentIntents->update($paymentIntentId, [
                'customer' => $customer->id,
            ]);

            return [
                'id' => $paymentIntentId,
                'customer_id' => $customer->id,
            ];
        } catch (\Exception $e) {
            throw new \Exception("Error creating customer: " . $e->getMessage());
        }
    }

    /**
     * Insert payment details into the database after transaction success.
     */
    public function insertPayment($jsonObj): array
    {
        $paymentIntentId = $jsonObj->payment_intent ?? null;
        $customerId = $jsonObj->customer_id ?? null;

        try {
            // Retrieve PaymentIntent details from Stripe
            $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentIntentId);

            if ($paymentIntent->status !== 'succeeded') {
                throw new \Exception("Transaction failed!");
            }

            // Get customer details
            $customer = $this->stripe->customers->retrieve($customerId);

            // Transaction details
            $transactionId = $paymentIntent->id;
            $paidAmount = $paymentIntent->amount / 100; // Convert cents to dollars
            $paidCurrency = $paymentIntent->currency;
            $paymentStatus = $paymentIntent->status;

            $customerName = $customer->name ?? '';
            $customerEmail = $customer->email ?? '';

            // Check if transaction already exists
            $query = "SELECT id FROM payments WHERE txn_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("s", $transactionId);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($txn_id);
                $stmt->fetch();
            } else {
                // Insert new transaction into the database
                $query = "INSERT INTO payments (customer_name, customer_email, item_name, item_price, paid_amount, paid_amount_currency, txn_id, payment_status, created) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param(
                    "sssdsdss",
                    $customerName,
                    $customerEmail,
                    $this->itemName,
                    $this->itemPrice,
                    $paidAmount,
                    $paidCurrency,
                    $transactionId,
                    $paymentStatus
                );

                if (!$stmt->execute()) {
                    throw new \Exception("Failed to insert payment record.");
                }

                $paymentId = $stmt->insert_id;
            }

            return [
                'payment_txn_id' => base64_encode($transactionId),
            ];
        } catch (\Exception $e) {
            throw new \Exception("Error processing payment: " . $e->getMessage());
        }
    }

    /**
     * Retrieve payment details from the database.
     */
    public function getPaymentDetails($transactionId): ?array
    {
        try {
            $query = "SELECT id, txn_id, paid_amount, paid_amount_currency, payment_status, customer_name, customer_email 
                      FROM payments 
                      WHERE txn_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("s", $transactionId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }

            return null;
        } catch (\Exception $e) {
            throw new \Exception("Error retrieving payment details: " . $e->getMessage());
        }
    }
}
