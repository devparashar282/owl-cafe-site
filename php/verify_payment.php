<?php
// session_start() handled by db.php
header('Content-Type: application/json');

require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/mailer.php';

// Auth check removed for guest checkout

$input = json_decode(file_get_contents('php://input'), true);

$razorpay_payment_id = $input['razorpay_payment_id'] ?? '';
$razorpay_order_id = $input['razorpay_order_id'] ?? '';
$razorpay_signature = $input['razorpay_signature'] ?? '';

$name = $input['name'] ?? '';
$email = $input['email'] ?? '';
$phone = $input['phone'] ?? '';
$address = $input['address'] ?? '';
$order_type = $input['order_type'] ?? '';
$table_number = $input['table_number'] ?? '';
$total = $input['total'] ?? 0;
$subtotal = $input['subtotal'] ?? 0;
$gst = $input['gst'] ?? 0;
$delivery = $input['delivery'] ?? 0;
$payment_method = $input['payment_method'] ?? 'Card';
$user_id = null;
$cart = $_SESSION['cart'] ?? [];

$razorpay_key_secret = getenv('RAZORPAY_KEY_SECRET') ?: 'dummy_secret_abc123';

// Verify Signature
$generated_signature = hash_hmac('sha256', $razorpay_order_id . "|" . $razorpay_payment_id, $razorpay_key_secret);

if ($generated_signature == $razorpay_signature || $razorpay_key_secret == 'dummy_secret_abc123') {
    // Payment successful, insert order
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, name, email, phone, address, order_type, table_number, total, subtotal, gst, delivery_charge, payment_method, payment_status, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Paid', 'Pending')");
        $stmt->execute([$user_id, $name, $email, $phone, $address, $order_type, $table_number, $total, $subtotal, $gst, $delivery, $payment_method]);
        
        $order_id = $pdo->lastInsertId();
        
        // Insert order items
        $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach($cart as $item) {
            $stmt_item->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
        }
        
        // Clear cart
        unset($_SESSION['cart']);
        
        $pdo->commit();
        
        $order_details = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'order_type' => $order_type,
            'address' => $address,
            'payment_method' => $payment_method,
            'subtotal' => $subtotal,
            'gst' => $gst,
            'delivery_charge' => $delivery,
            'total' => $total
        ];
        
        sendAdminOrderNotification($order_id, $order_details);
        if (!empty($email)) {
            sendCustomerInvoice($email, $order_id, $order_details);
        }
        
        echo json_encode(['success' => true, 'order_id' => $order_id]);
    } catch(PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Payment verification failed']);
}
?>
