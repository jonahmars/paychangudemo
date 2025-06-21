<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
require_once 'config.php'; // contains $pdo

// PayChangu secret keys
$secret_key = 'SEC-C4QlSA5bdFUonlygmGFD0Zuc6CI53Aqa';
$webhook_secret = 'mySuperSecretWebhookKey2024!'; // replace with the actual webhook secret

// Ensure POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit;
}

// Get payload and headers
$payload = file_get_contents('php://input');
$headers = getallheaders();

// Verify webhook signature
$computedSignature = hash_hmac('sha256', $payload, $webhook_secret);
if (!isset($headers['Signature']) || $headers['Signature'] !== $computedSignature) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Invalid webhook signature']);
    exit;
}

// Decode JSON data
$data = json_decode($payload, true);

// Check status
if (isset($data['status']) && $data['status'] === 'success') {
    $tx = $data['data'] ?? [];

    // Extract fields
    $tx_ref     = $tx['tx_ref'] ?? '';
    $reference  = $tx['reference'] ?? '';
    $amount     = $tx['amount'] ?? 0;
    $currency   = $tx['currency'] ?? '';
    $status     = $tx['status'] ?? '';
    $type       = $tx['type'] ?? '';
    $completed  = $tx['authorization']['completed_at'] ?? date('Y-m-d H:i:s');

    $email      = $tx['customer']['email'] ?? 'unknown';
    $first_name = $tx['customer']['first_name'] ?? '';
    $last_name  = $tx['customer']['last_name'] ?? '';
    $full_name  = trim("$first_name $last_name");

    try {
        $stmt = $pdo->prepare("INSERT IGNORE INTO transactions 
            (customer_email, customer_name, transaction_date, status, amount, type, reference, tx_ref)
            VALUES (:email, :name, :date, :status, :amount, :type, :reference, :tx_ref)");

        $stmt->execute([
            ':email'     => $email,
            ':name'      => $full_name,
            ':date'      => $completed,
            ':status'    => $status,
            ':amount'    => $amount,
            ':type'      => $type,
            ':reference' => $reference,
            ':tx_ref'    => $tx_ref
        ]);

        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Payment saved']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(200);
    echo json_encode(['status' => 'ignored', 'message' => 'Not a successful payment']);
}
