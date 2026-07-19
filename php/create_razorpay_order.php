<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

// Fetch total amount from POST data
$input = json_decode(file_get_contents('php://input'), true);
$amount = $input['amount'] ?? 0;

if ($amount <= 0) {
    echo json_encode(['error' => 'Invalid amount']);
    exit;
}

$razorpay_key_id = getenv('RAZORPAY_KEY_ID') ?: 'rzp_test_dummykey123';
$razorpay_key_secret = getenv('RAZORPAY_KEY_SECRET') ?: 'dummy_secret_abc123';

// Amount must be in paise (1 INR = 100 paise)
$amount_in_paise = round($amount * 100);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'amount' => $amount_in_paise,
    'currency' => 'INR',
    'receipt' => 'rcptid_' . uniqid()
]));
curl_setopt($ch, CURLOPT_USERPWD, $razorpay_key_id . ':' . $razorpay_key_secret);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(['error' => 'Request Error:' . curl_error($ch)]);
    exit;
}
curl_close($ch);

$result = json_decode($response, true);
if (isset($result['id'])) {
    echo json_encode([
        'success' => true,
        'order_id' => $result['id'],
        'amount' => $result['amount'],
        'currency' => $result['currency'],
        'key' => $razorpay_key_id
    ]);
} else {
    echo json_encode(['error' => 'Failed to create Razorpay Order. ' . ($result['error']['description'] ?? '')]);
}
?>
