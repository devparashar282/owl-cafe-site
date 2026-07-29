<?php
// session_start() handled by db.php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['menu_id'])) {
    $menu_id = (int)$_POST['menu_id'];
    
    if (isset($_SESSION['cart'][$menu_id])) {
        unset($_SESSION['cart'][$menu_id]);
        
        $cart_count = 0;
        foreach($_SESSION['cart'] as $cart_item) {
            $cart_count += $cart_item['quantity'];
        }
        
        echo json_encode(['success' => true, 'cart_count' => $cart_count]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Item not in cart']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
