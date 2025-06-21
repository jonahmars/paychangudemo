<?php
// verify_transaction.php

ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

require 'config.php'; // Make sure this file connects to your MySQL

$secret_key = 'SEC-C4QlSA5bdFUonlygmGFD0Zuc6CI53Aqa';

if (!isset($_GET['tx_ref'])) {
    echo '<div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center p-4">
        <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full text-center">
            <div class="text-red-500 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Transaction reference missing</h1>
            <p class="text-gray-600 mb-6">Please check your payment link and try again.</p>
            <a href="index.php" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-300 inline-block">Go back</a>
        </div>
    </div>';
    exit;
}

$tx_ref = $_GET['tx_ref'];
$url = "https://api.paychangu.com/verify-payment/$tx_ref";

// Prepare cURL
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $secret_key",
        "Accept: application/json"
    ],
    CURLOPT_SSL_VERIFYPEER => false
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

if ($curl_error) {
    exit('<div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
        <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
            <h2 class="text-xl font-bold text-red-600 mb-4">cURL Error</h2>
            <p class="text-gray-700">' . htmlspecialchars($curl_error) . '</p>
        </div>
    </div>');
}

$result = json_decode($response, true);

if ($http_code !== 200 || $result['status'] !== 'success') {
    echo '<div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center p-4">
        <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
            <div class="text-red-500 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-4 text-center">Transaction verification failed</h1>
            <div class="bg-gray-50 p-4 rounded-md overflow-x-auto">
                <pre class="text-xs text-gray-600">' . print_r($result, true) . '</pre>
            </div>
            <a href="index.php" class="mt-6 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-300 inline-block w-full text-center">Return Home</a>
        </div>
    </div>';
    exit;
}

$data = $result['data'];

// 🟢 Display details
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: "Inter", sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-8 text-center">
                    <div class="flex justify-center mb-4">
                        <div class="bg-white p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <h1 class="text-2xl font-bold text-white mb-2">Payment Successful</h1>
                    <p class="text-green-100">Transaction reference: ' . htmlspecialchars($data['tx_ref']) . '</p>
                </div>
                <div class="px-6 py-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Payment Details</h2>
                            <div class="space-y-3">
                                <div class="flex justify-between">
    <span class="text-gray-600">Amount:</span>
    <span class="font-medium">MWK ' . number_format($data['amount'], 0) . '</span>
</div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="font-medium text-green-600">' . htmlspecialchars(ucfirst($data['status'])) . '</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Payment Type:</span>
                                    <span class="font-medium">' . htmlspecialchars(ucfirst($data['type'])) . '</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Channel:</span>
                                    <span class="font-medium">' . htmlspecialchars($data['authorization']['channel'] ?? '-') . '</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Date:</span>
                                    <span class="font-medium">' . htmlspecialchars($data['authorization']['completed_at'] ?? '-') . '</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h2>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium">' . htmlspecialchars($data['customer']['email'] ?? '-') . '</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Name:</span>
                                    <span class="font-medium">' . htmlspecialchars($data['customer']['name'] ?? ($data['customer']['first_name'] ?? '-')) . '</span>
                                </div>
                            </div>
                        </div>
                    </div>';

try {
    $check = $pdo->prepare("SELECT COUNT(*) FROM transactions WHERE tx_ref = ?");
    $check->execute([$data['tx_ref']]);

    if ($check->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO transactions 
            (tx_ref, customer_email, amount, currency, status, payment_type, channel, reference, payment_date) 
            VALUES 
            (:tx_ref, :email, :amount, :currency, :status, :type, :channel, :reference, :payment_date)");

        $stmt->execute([
            ':tx_ref' => $data['tx_ref'],
            ':email' => $data['customer']['email'] ?? '',
            ':amount' => $data['amount'],
            ':currency' => $data['currency'],
            ':status' => $data['status'],
            ':type' => $data['type'],
            ':channel' => $data['authorization']['channel'] ?? 'Unknown',
            ':reference' => $data['reference'],
            ':payment_date' => $data['authorization']['completed_at'] ?? date('Y-m-d H:i:s')
        ]);

        echo '<div class="bg-green-100 text-green-800 border border-green-200 rounded-md p-4 mb-6">✅ Payment saved to database.</div>';
    } else {
        echo '<div class="bg-blue-100 text-blue-800 border border-blue-200 rounded-md p-4 mb-6">ℹ️ Payment already recorded.</div>';
    }
} catch (PDOException $e) {
    echo '<div class="bg-red-100 text-red-800 border border-red-200 rounded-md p-4 mb-6">❌ DB Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
echo '  <div class="flex justify-end mt-6 space-x-4">
            <a href="index.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">Return Home</a>
            <button onclick="window.print()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-md">Print Receipt</button>
            <button onclick="generatePDF()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md">Generate PDF</button>
        </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PDF Generation Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        function generatePDF() {
            const { jsPDF } = window.jspdf;
            const receipt = document.body;

            html2canvas(receipt).then(canvas => {
                const imgData = canvas.toDataURL("image/png");
                const pdf = new jsPDF("p", "mm", "a4");
                const imgProps = pdf.getImageProperties(imgData);
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
                pdf.save("payment_receipt_' . htmlspecialchars($data['tx_ref']) . '.pdf");
            });
        }
    </script>
</body>
</html>';
