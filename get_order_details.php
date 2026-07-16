<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if (!isset($_GET['order_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing order ID']);
    exit;
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

try {
    // Verify the order belongs to the user
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        http_response_code(404);
        echo json_encode(['error' => 'Order not found']);
        exit;
    }

    // Fetch order items
    $stmt = $pdo->prepare("SELECT oi.quantity, oi.price, m.name 
                           FROM order_items oi 
                           JOIN menu m ON oi.menu_id = m.id 
                           WHERE oi.order_id = ?");
    $stmt->execute([$order_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Add formatted date
    $order['formatted_date'] = date('d M Y, h:i A', strtotime($order['created_at']));
    $order['formatted_id'] = '#ORD-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT);

    echo json_encode([
        'order' => $order,
        'items' => $items
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
