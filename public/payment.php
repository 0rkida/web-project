<?php
require 'C:\xampp\htdocs\web-project\vendor\autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51QjjrjJ4WpQ4jf1LRrpPUfzCMjKj2Wl1tUQRKVMV1878nClyKiNqoneW1P2WlX83rw3nqqUeK9d8XjuZyw0gDDig00GxwwT47z');

$paymentIntent = \Stripe\PaymentIntent::create([
    'amount' => 1000, // Amount in cents
    'currency' => 'usd',
]);

echo 'Payment Intent Created: ' . $paymentIntent->id;
