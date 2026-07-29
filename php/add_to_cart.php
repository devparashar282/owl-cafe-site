<?php
// session_start() handled by db.php
require_once dirname(__DIR__) . '/includes/db.php';

header('Content-Type: application/json');

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['menu_id'])) {
    $menu_id = (int)$_POST['menu_id'];
    
    // Check if item exists in DB
    $stmt = $pdo->prepare("SELECT id, name, price, image FROM menu WHERE id = ?");
    $stmt->execute([$menu_id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($item) {
        // Add to cart session or increment quantity
        if (isset($_SESSION['cart'][$menu_id])) {
            $_SESSION['cart'][$menu_id]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$menu_id] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'image' => $item['image'],
                'quantity' => 1
            ];
        }
        
        // Calculate total cart items count
        $cart_count = 0;
        foreach($_SESSION['cart'] as $cart_item) {
            $cart_count += $cart_item['quantity'];
        }
        
        echo json_encode(['success' => true, 'cart_count' => $cart_count]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Item not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
