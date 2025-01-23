<?php
require_once('vendor/autoload.php');

$private_key = "sk_test_51QjjrjJ4WpQ4jf1LRrpPUfzCMjKj2Wl1tUQRKVMV1878nClyKiNqoneW1P2WlX83rw3nqqUeK9d8XjuZyw0gDDig00GxwwT47z";
$public_key = "pk_test_51QjjrjJ4WpQ4jf1LF7BW0dZl96ZM8VTtf4wsaCfvTc3FkWSk7s7NxQR2WFVxzSREr5FSm8HzDB8OTvUKBjY5bBE100xoEAu1fF";
$stripe_account = "Test";
$businessName = "Test";
$company_name = "Test";

/**
 * Inicializimi i Stripe
 */
\Stripe\Stripe::setApiKey($private_key);
// \Stripe\Stripe::setMaxNetworkRetries(2);

$stripe = new \Stripe\StripeClient($private_key);