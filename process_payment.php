
<?php
// Show errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Your real PayChangu secret key
$secret_key = 'SEC-C4QlSA5bdFUonlygmGFD0Zuc6CI53Aqa';

// Use localhost or ngrok base URL
//$base_url = 'http://localhost/paychangudemo';
$base_url = 'https://b73f-102-70-95-221.ngrok-free.app/paychangudemo';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = intval($_POST['amount']);
    $email = trim($_POST['email']);
    $name = trim($_POST['name']);

    if ($amount <= 0 || !$email || !$name) {
        exit('❌ Missing or invalid input.');
    }

    $amountTambala = $amount; // keep as-is, no conversion


    $data = [
        "amount" => $amountTambala,
        "currency" => "MWK",
        "email" => $email,
        "first_name" => $name,
        "last_name" => "",
        "callback_url" => "$base_url/thankyou.php",
        "return_url" => "$base_url/thankyou.php",
        "tx_ref" => uniqid('TJ'),
        "customization" => [
            "title" => "Real Payment",
            "description" => "Payment for Order #" . time()
        ],
        "meta" => [
            "customer_name" => $name
        ]
    ];

    $ch = curl_init("https://api.paychangu.com/payment");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $secret_key",
            "Accept: application/json",
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_SSL_VERIFYPEER => false
    ]);

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        exit("❌ cURL Error: $error");
    }

    $result = json_decode($response, true);

    if ($status !== 200 && $status !== 201) {
        echo "<h3>❌ Request failed – HTTP $status</h3>";
        echo "<pre>" . htmlspecialchars(json_encode($result, JSON_PRETTY_PRINT)) . "</pre>";
        exit;
    }

    if (isset($result['data']['checkout_url'])) {
        header("Location: " . $result['data']['checkout_url']);
        exit;
    }

    exit("❌ Unexpected response structure: " . htmlspecialchars($response));
} else {
    http_response_code(405);
    exit("❌ Method Not Allowed");
}
