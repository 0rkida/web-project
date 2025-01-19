<?php
require 'C:\xampp\htdocs\web-project\vendor\autoload.php'; // Include Stripe PHP SDK
require 'C:\xampp\htdocs\web-project\config\paymentsConfig.php';

php\lib\Stripe::setApiKey(STRIPE_SECRET_KEY);

header('Content-Type: application/json');

try {
    $session = php\lib\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => $currency,
                'product_data' => [
                    'name' => $itemName,
                ],
                'unit_amount' => $itemPrice * 100, // Convert to cents
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'https://yourdomain.com/success.html',
        'cancel_url' => 'https://yourdomain.com/cancel.html',
    ]);

    echo json_encode(['id' => $session->id]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

