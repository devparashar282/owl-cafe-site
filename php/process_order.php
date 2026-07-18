<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $payment_method = $_POST['payment_method'];
    
    $subtotal = $_POST['subtotal'];
    $gst = $_POST['gst'];
    $delivery = $_POST['delivery'];
    $total = $_POST['total'];

    try {
        $pdo->beginTransaction();
        
        // Insert Order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, name, phone, address, subtotal, gst, delivery_charge, total, payment_method, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->execute([$user_id, $name, $phone, $address, $subtotal, $gst, $delivery, $total, $payment_method]);
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
        
        echo "<script>alert('Order placed successfully! Order ID: #$order_id'); window.location.href='../index.php';</script>";
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "<script>alert('Error placing order: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    }
} else {
    header("Location: ../login.php");
}
?>
