<?php
include 'sessionManager.php';
// payment_form.php
require_once 'C:\xampp\htdocs\web-project\config\paymentsConfig.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
<h1>Premium Subscription</h1>
<p>Enjoy premium features for just $10 per month.</p>
<form id="payment-form">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <button id="pay-button" type="button">Pay Now</button>
</form>

<div id="payment-status" style="display:none;">
    <h2>Payment Status</h2>
    <p id="status-message"></p>
</div>

<script>
    document.getElementById("pay-button").addEventListener("click", async () => {
        const name = document.getElementById("name").value;
        const email = document.getElementById("email").value;

        if (!name || !email) {
            alert("Please enter your name and email.");
            return;
        }

        // Create a payment intent via the backend
        try {
            const response = await fetch('PaymentInit.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ request_type: 'create_payment_intent' })
            });
            const data = await response.json();

            if (data.clientSecret) {
                // Initialize Stripe
                const stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');
                const result = await stripe.confirmCardPayment(data.clientSecret, {
                    payment_method: {
                        card: { token: "tok_visa" }, // Simulated card (replace with real card in production)
                        billing_details: { name, email }
                    }
                });

                if (result.error) {
                    document.getElementById("payment-status").style.display = "block";
                    document.getElementById("status-message").textContent = result.error.message;
                } else {
                    // Payment succeeded
                    document.getElementById("payment-status").style.display = "block";
                    document.getElementById("status-message").textContent = "Payment successful!";

                    // Insert payment into the database
                    await fetch('PaymentInit.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            request_type: 'payment_insert',
                            payment_intent: result.paymentIntent.id,
                            customer_id: result.paymentIntent.customer,
                        })
                    });
                }
            }
        } catch (error) {
            console.error("Error processing payment:", error);
        }
    });
</script>
</body>
</html>
