<?php
// session_start() handled by db.php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/mailer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = null;
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $order_type = $_POST['order_type'] ?? 'Delivery';
    $table_number = filter_var($_POST['table_number'] ?? '', FILTER_SANITIZE_STRING);
    
    if ($order_type === 'Dine In') {
        $address = 'Dine In' . ($table_number ? ' - ' . $table_number : '');
    } else {
        $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    }
    
    $payment_method = $_POST['payment_method'];
    
    $subtotal = $_POST['subtotal'];
    $gst = $_POST['gst'];
    $delivery = $_POST['delivery'];
    $total = $_POST['total'];

    try {
        $pdo->beginTransaction();
        
        // Insert Order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, order_type, name, email, phone, address, subtotal, gst, delivery_charge, total, payment_method, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->execute([$user_id, $order_type, $name, $email, $phone, $address, $subtotal, $gst, $delivery, $total, $payment_method]);
        $order_id = $pdo->lastInsertId();
        
        // Insert real cart items
        if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach($_SESSION['cart'] as $item_id => $item) {
                $stmt_item->execute([$order_id, $item_id, $item['quantity'], $item['price']]);
            }
            // Clear cart
            unset($_SESSION['cart']);
        } else {
            throw new Exception("Cart is empty");
        }
        
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
        
        echo "<script>alert('Order placed successfully! Order ID: #$order_id'); window.location.href='../index.php';</script>";
        
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo "<script>alert('Error placing order: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    }
}
?>
