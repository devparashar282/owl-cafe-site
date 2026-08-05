<?php
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$message = '';

// Handle Status Update
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $order_id]);
        $message = "<div class='alert alert-success'>Order #$order_id status updated to $new_status.</div>";
    } catch(PDOException $e) {
        $message = "<div class='alert alert-danger'>Failed to update status.</div>";
    }
}

// Fetch all orders
try {
    $stmt = $pdo->query("SELECT o.*, u.email as user_email FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
    $orders = $stmt->fetchAll();
} catch(PDOException $e) {
    $orders = [];
}
?>

<main id="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 theme-text">Orders Management</h2>
    </div>

    <?= $message ?>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Info</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Update Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($orders)): ?>
                    <tr><td colspan="7" class="text-center py-4 theme-text-muted">No orders found.</td></tr>
                    <?php else: foreach($orders as $order): ?>
                    <tr>
                        <td class="fw-bold">
                            #ORD-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>
                            <div class="mt-1">
                                <?php if(($order['order_type'] ?? '') == 'Dine In'): ?>
                                    <span class="badge bg-info text-dark"><i class="fas fa-utensils me-1"></i> Dine In</span>
                                <?php else: ?>
                                    <span class="badge bg-primary"><i class="fas fa-motorcycle me-1"></i> Delivery</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div><?= htmlspecialchars($order['name']) ?></div>
                            <small class="theme-text-muted"><?= htmlspecialchars($order['phone']) ?></small>
                        </td>
                        <td>
                            <div><?= date('d M Y', strtotime($order['created_at'])) ?></div>
                            <small class="theme-text-muted"><?= date('h:i A', strtotime($order['created_at'])) ?></small>
                        </td>
                        <td class="fw-bold text-golden">₹<?= number_format($order['total'], 2) ?></td>
                        <td><?= $order['payment_method'] ?></td>
                        <td>
                            <?php 
                            $badge = 'bg-secondary';
                            if($order['status'] == 'Pending') $badge = 'bg-warning text-dark';
                            if($order['status'] == 'Processing') $badge = 'bg-info text-dark';
                            if($order['status'] == 'Delivered') $badge = 'bg-success';
                            if($order['status'] == 'Cancelled') $badge = 'bg-danger';
                            ?>
                            <span class="badge <?= $badge ?>"><?= $order['status'] ?></span>
                        </td>
                        <td>
                            <form action="" method="POST" class="d-flex gap-2">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <select name="status" class="form-select form-select-sm bg-transparent theme-border theme-text" style="width: 130px;">
                                    <option class="text-dark" value="Pending" <?= $order['status']=='Pending'?'selected':'' ?>>Pending</option>
                                    <option class="text-dark" value="Processing" <?= $order['status']=='Processing'?'selected':'' ?>>Processing</option>
                                    <option class="text-dark" value="Delivered" <?= $order['status']=='Delivered'?'selected':'' ?>>Delivered</option>
                                    <option class="text-dark" value="Cancelled" <?= $order['status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-sm btn-outline-golden">Save</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
