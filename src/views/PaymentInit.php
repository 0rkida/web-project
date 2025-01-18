<?php

require_once 'C:\xampp\htdocs\web-project\config\paymentsConfig.php';
include_once 'C:\xampp\htdocs\web-project\src\db.php';
include_once 'C:\xampp\htdocs\web-project\config\db_connection.php';
require_once 'C:\xampp\htdocs\web-project\vendor\stripe-php-master\init.php';

$stripe = new php\lib\StripeClient(STRIPE_SECRET_KEY);

$jsonStr = file_get_contents('php://input');
$jsonObj = json_decode($jsonStr);

if($jsonObj->request_type == 'create_payment_intent'){
    // Define item price and convert to cents
    $itemPriceCents = round($itemPrice*100);
    try {
        // Create PaymentIntent with amount and currency
        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => $itemPriceCents,
            'currency' => $currency,
            'description' => $itemName,
            'payment_method_types' => [
                'card'
            ]
            /*'automatic_payment_methods' => [
                'enabled' => true
            ]*/
        ]);

        $output = [
            'id' => $paymentIntent->id,
            'clientSecret' => $paymentIntent->client_secret
        ];

        echo json_encode($output);
    } catch (Error $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}elseif($jsonObj->request_type == 'create_customer'){
    $payment_intent_id = !empty($jsonObj->payment_intent_id)?$jsonObj->payment_intent_id:'';
    $name = !empty($jsonObj->name)?$jsonObj->name:'';
    $email = !empty($jsonObj->email)?$jsonObj->email:'';

    // Check if PaymentIntent already has a customer
    if(!empty($payment_intent_id)){
        $paymentIntent = $stripe->paymentIntents->retrieve($payment_intent_id);
        if(!empty($paymentIntent->customer)){
            $customer_id = $paymentIntent->customer;
        }
    }

    // Add customer to stripe if not created already
    if(empty($customer_id)){
        try {
            $customer = $stripe->customers->create([
                'name' => $name,
                'email' => $email
            ]);
            $customer_id = $customer->id;
        }catch(Error $e) {
            $api_error = $e->getMessage();
        }
    }

    if(empty($api_error) && !empty($customer_id)){
        try {
            // Update PaymentIntent with the customer ID
            $paymentIntent = $stripe->paymentIntents->update($payment_intent_id, [
                'customer' => $customer_id
            ]);
        } catch (Error $e) {
            $api_error = $e->getMessage();
        }

        if(empty($api_error) && $paymentIntent){
            $output = [
                'id' => $payment_intent_id,
                'customer_id' => $customer_id
            ];
            echo json_encode($output);
        }else{
            http_response_code(500);
            echo json_encode(['error' => $api_error]);
        }
    }else{
        http_response_code(500);
        echo json_encode(['error' => $api_error]);
    }
}elseif($jsonObj->request_type == 'payment_insert'){
    $payment_intent = !empty($jsonObj->payment_intent)?$jsonObj->payment_intent:'';
    $customer_id = !empty($jsonObj->customer_id)?$jsonObj->customer_id:'';

    // Retrieve customer info
    try {
        $customer = $stripe->customers->retrieve($customer_id);
    }catch(Error $e) {
        $api_error = $e->getMessage();
    }

    // Check whether the charge was successful
    if(!empty($payment_intent) && $payment_intent->status == 'succeeded'){
        // Transaction details
        $transaction_id = $payment_intent->id;
        $paid_amount = $payment_intent->amount;
        $paid_amount = ($paid_amount/100);
        $paid_currency = $payment_intent->currency;
        $payment_status = $payment_intent->status;

        $customer_name = $customer_email = '';
        if(!empty($customer)){
            $customer_name = !empty($customer->name)?$customer->name:'';
            $customer_email = !empty($customer->email)?$customer->email:'';
        }

        // Check if any transaction data exists already with the same TXN ID
        $sqlQ = "SELECT id FROM payments WHERE txn_id = ?";
        $stmt = $db->prepare($sqlQ);
        $stmt->bind_param("s", $transaction_id);
        $stmt->execute();
        $stmt->bind_result($row_id);
        $stmt->fetch();

        $payment_id = 0;
        if(!empty($row_id)){
            $payment_id = $row_id;
        }else{
            // Insert transaction data into the database
            $sqlQ = "INSERT INTO payments (customer_name,customer_email,item_name,item_price,paid_amount,paid_amount_currency,txn_id,payment_status,created) VALUES (?,?,?,?,?,?,?,?,NOW())";
            $stmt = $db->prepare($sqlQ);
            $stmt->bind_param("sssdsdsss", $customer_name, $customer_email, $itemName, $itemPrice, $currency, $paid_amount, $paid_currency, $transaction_id, $payment_status);
            $insert = $stmt->execute();

            if($insert){
                $payment_id = $stmt->insert_id;
            }
        }

        $output = [
            'payment_txn_id' => base64_encode($transaction_id)
        ];
        echo json_encode($output);
    }else{
        http_response_code(500);
        echo json_encode(['error' => 'Transaction has been failed!']);
    }
}

?>

