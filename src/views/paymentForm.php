<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link rel="stylesheet" href="/assets/css/style.css"> <!-- Optional CSS -->
    <script src="https://js.stripe.com/v3/"></script> <!-- Stripe.js -->
</head>
<body>
<div class="container">
    <h1>Make a Payment</h1>
    <form id="paymentForm">
        <label for="amount">Amount (€):</label>
        <input type="number" id="amount" name="amount" step="0.01" required>

        <label for="user_id">User ID:</label>
        <input type="number" id="user_id" name="user_id" required>

        <button type="submit" id="payButton">Pay Now</button>
    </form>

    <div id="paymentResult" style="margin-top: 20px;"></div>

    <hr>

    <h2>Payment History</h2>
    <form id="historyForm">
        <label for="history_user_id">User ID:</label>
        <input type="number" id="history_user_id" name="user_id" required>
        <button type="submit">Get Payment History</button>
    </form>

    <div id="paymentHistory" style="margin-top: 20px;"></div>
</div>

<script>
    const stripe = Stripe('YOUR_STRIPE_PUBLISHABLE_KEY'); // Replace with your actual publishable key

    // Handle Payment Form Submission
    document.getElementById('paymentForm').addEventListener('submit', async function (event) {
        event.preventDefault();

        const amount = document.getElementById('amount').value;
        const userId = document.getElementById('user_id').value;

        try {
            const response = await fetch('/create-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ amount, user_id: userId })
            });

            const data = await response.json();

            if (data.error) {
                document.getElementById('paymentResult').innerText = `Error: ${data.error}`;
            } else {
                const clientSecret = data.clientSecret;

                // Confirm the payment with Stripe
                const result = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: {
                            // Example of collecting card details
                            // Replace with your Stripe Elements integration
                            number: '4242424242424242',
                            exp_month: 12,
                            exp_year: 2024,
                            cvc: '123',
                        }
                    }
                });

                if (result.error) {
                    document.getElementById('paymentResult').innerText = `Payment Failed: ${result.error.message}`;
                } else if (result.paymentIntent.status === 'succeeded') {
                    document.getElementById('paymentResult').innerText = 'Payment Successful!';
                }
            }
        } catch (error) {
            document.getElementById('paymentResult').innerText = `Error: ${error.message}`;
        }
    });

    // Handle Payment History Form Submission
    document.getElementById('historyForm').addEventListener('submit', async function (event) {
        event.preventDefault();

        const userId = document.getElementById('history_user_id').value;

        try {
            const response = await fetch(`/get-payments?user_id=${userId}`, {
                method: 'GET',
            });

            const data = await response.json();

            if (data.error) {
                document.getElementById('paymentHistory').innerText = `Error: ${data.error}`;
            } else if (data.length === 0) {
                document.getElementById('paymentHistory').innerText = 'No payments found.';
            } else {
                const historyHtml = data.map(payment => `
                        <div>
                            <p><strong>Payment ID:</strong> ${payment.id}</p>
                            <p><strong>Amount:</strong> €${payment.amount}</p>
                            <p><strong>Currency:</strong> ${payment.currency}</p>
                            <p><strong>Status:</strong> ${payment.status}</p>
                            <p><strong>Created At:</strong> ${payment.created_at}</p>
                            <hr>
                        </div>
                    `).join('');

                document.getElementById('paymentHistory').innerHTML = historyHtml;
            }
        } catch (error) {
            document.getElementById('paymentHistory').innerText = `Error: ${error.message}`;
        }
    });
</script>
</body>
</html>
