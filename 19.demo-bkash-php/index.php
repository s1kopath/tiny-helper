<?php

// STEP 0: Configuration
$app_key = '4f6o0cjiki2rfm34kfdadl1eqq';
$app_secret = '2is7hdktrekvrbljjh44ll3d9l1dtjo4pasmjvs5vl5qr3fug4b';
$username = 'sandboxTokenizedUser02';
$password = 'sandboxTokenizedUser02@12345';

$base_url = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta';


// === HANDLE FORM SUBMISSION ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];

    // STEP 1: Generate Token
    function getToken($base_url, $app_key, $app_secret, $username, $password)
    {
        $url = "$base_url/tokenized/checkout/token/grant";
        $headers = [
            "Content-Type: application/json",
            "username: $username",
            "password: $password"
        ];
        $body = json_encode([
            "app_key" => $app_key,
            "app_secret" => $app_secret
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($res, true);
        return $data['id_token'] ?? null;
    }

    // STEP 2: Create Payment
    function createPayment($base_url, $token, $app_key, $amount)
    {
        $url = "$base_url/tokenized/checkout/create";
        $headers = [
            "Content-Type: application/json",
            "authorization: $token",
            "x-app-key: $app_key"
        ];
        $body = json_encode([
            "mode" => "0011",
            "payerReference" => "CUSTOMER001",
            "callbackURL" => "https://example.com/callback", // Optional now
            "amount" => $amount,
            "currency" => "BDT",
            "intent" => "sale",
            "merchantInvoiceNumber" => "Inv" . time()
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);

        return json_decode($res, true);
    }

    $token = getToken($base_url, $app_key, $app_secret, $username, $password);
    if (!$token) {
        echo "❌ Failed to get token.";
        exit;
    }

    $payment = createPayment($base_url, $token, $app_key, $amount);
    if (!isset($payment['paymentID']) || !isset($payment['bkashURL'])) {
        echo "❌ Payment creation failed.";
        echo "<pre>" . print_r($payment, true) . "</pre>";
        exit;
    }

    // Redirect to bKash payment page
    header("Location: " . $payment['bkashURL']);
    exit;
}
?>

<!-- === FRONTEND FORM === -->
<!DOCTYPE html>
<html>

<head>
    <title>bKash Test Payment</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #d10263;
            /* bKash primary magenta color */
            margin-bottom: 24px;
        }

        label {
            display: block;
            font-size: 14px;
            color: #333;
            margin-bottom: 6px;
            text-align: left;
        }

        input[type="number"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        button {
            background-color: #d10263;
            color: #fff;
            border: none;
            padding: 14px 20px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #b10055;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
                margin: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Pay with bKash</h2>
        <form method="post">
            <label for="amount">Enter Amount (BDT)</label>
            <input type="number" name="amount" id="amount" required pattern="\d+(\.\d{1,2})?" />
            <button type="submit">Pay Now</button>
        </form>
        <div class="footer">Powered by bKash Sandbox</div>
    </div>
</body>

</html>