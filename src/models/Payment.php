<?php

require_once 'C:\xampp\htdocs\web-project\config\paymentsConfig.php'

?>

<script src="https://js.stripe.com/v3/"></script>
<script src="/public/js/checkout.js" STRIPE_PUBLISHABLE_KEY="<?php echo STRIPE_PUBLISHABLE_KEY; ?>" defer></script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
</head>
<body>
<h1>Premium Subscription</h1>
<p>Subscribe for just €25 and enjoy exclusive features!</p>
<button id="checkout-button">Pay Now</button>
</body>
</html>

