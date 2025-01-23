<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'stripe_initialization.php'; // Include the Stripe PHP library
    require_once 'C:/xampp/htdocs/web-project/config/db_connection.php'; // Include your database connection file

    \Stripe\Stripe::setApiKey('sk_test_51QjjrjJ4WpQ4jf1LRrpPUfzCMjKj2Wl1tUQRKVMV1878nClyKiNqoneW1P2WlX83rw3nqqUeK9d8XjuZyw0gDDig00GxwwT47z');

    $paymentMethod = $_POST['payment_method'];
    $cardholderName = $_POST['cardholder_name'];
    $user_id = $_POST['user_id']; // Assuming you pass user_id from the frontend

    // Create a new customer
    $customer = \Stripe\Customer::create([
        'name' => $cardholderName,
        'payment_method' => $paymentMethod,
        'invoice_settings' => [
            'default_payment_method' => $paymentMethod,
        ],
    ]);

    // Retrieve payment method details
    $paymentMethodDetails = \Stripe\PaymentMethod::retrieve($paymentMethod);

    // Save card information to stripe_cards table
    $active = 'yes';
    $default_card = 'yes';
    $testing = 'no';
    $ip = $_SERVER['REMOTE_ADDR'];
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    $stmt = $conn->prepare("INSERT INTO stripe_cards (user_id, cardholder_name, active, payment_method, card_country, card_brand, card_last4, card_exp_month, card_exp_year, default_card, testing, ip, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssssssss",
        $user_id,
        $cardholderName,
        $active,
        $paymentMethod,
        $paymentMethodDetails->card->country,
        $paymentMethodDetails->card->brand,
        $paymentMethodDetails->card->last4,
        $paymentMethodDetails->card->exp_month,
        $paymentMethodDetails->card->exp_year,
        $default_card,
        $testing,
        $ip,
        $created_at,
        $updated_at
    );
    $stmt->execute();

    // Save transaction details to transactions_details table
    $transaction_id = 'txn_' . uniqid(); // Generate a unique transaction ID for demo purposes
    $payment_intent_id = ''; // No payment_intent_id for this demo
    $charge_id = ''; // No charge_id for this demo
    $amount = '0.00'; // Default amount for this demo
    $currency = 'USD'; // Default currency for this demo
    $converted_amount = '0.00'; // Default converted amount for this demo
    $converted_currency = 'USD'; // Default converted currency for this demo
    $balance_description = ''; // No balance description for this demo
    $exchange_rate = '1.00'; // Default exchange rate for this demo
    $available_on = date('Y-m-d H:i:s'); // Default available_on for this demo
    $payment_fee = '0.00'; // Default payment_fee for this demo
    $payment_net = '0.00'; // Default payment_net for this demo
    $status = 'success'; // Assuming status is success

    $stmt = $conn->prepare("INSERT INTO transactions_details (user_id, payment_method, payment_intent_id, transaction_id, charge_id, amount, currency, converted_amount, converted_currency, balance_description, exchange_rate, available_on, payment_fee, payment_net, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssssssssss",
        $user_id,
        $paymentMethod,
        $payment_intent_id,
        $transaction_id,
        $charge_id,
        $amount,
        $currency,
        $converted_amount,
        $converted_currency,
        $balance_description,
        $exchange_rate,
        $available_on,
        $payment_fee,
        $payment_net,
        $status,
        $created_at
    );
    $stmt->execute();

    echo json_encode(['status' => 200, 'message' => 'Payment successful']);
}
?>
