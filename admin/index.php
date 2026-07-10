<?php
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Fetch basic stats
try {
    $stmt1 = $pdo->query("SELECT COUNT(*) FROM orders");
    $total_orders = $stmt1->fetchColumn();
    
    $stmt2 = $pdo->query("SELECT COUNT(*) FROM bookings");
    $total_bookings = $stmt2->fetchColumn();
    
    $stmt3 = $pdo->query("SELECT COUNT(*) FROM users");
    $total_users = $stmt3->fetchColumn();
    
    $stmt4 = $pdo->query("SELECT SUM(total) FROM orders WHERE status = 'Delivered'");
    $total_revenue = $stmt4->fetchColumn() ?? 0;

    // Fetch recent orders
    $stmt5 = $pdo->query("SELECT o.id, u.name, o.total, o.status, o.created_at FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5");
    $recent_orders = $stmt5->fetchAll();

} catch(PDOException $e) {
    $total_orders = 0; $total_bookings = 0; $total_users = 0; $total_revenue = 0;
    $recent_orders = [];
}
?>

<main id="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 theme-text">Dashboard Overview</h2>
        <div class="d-flex align-items-center">
            <span class="me-3 theme-text">Admin</span>
            <img src="https://ui-avatars.com/api/?name=Admin&background=d4af37&color=000" class="rounded-circle shadow-sm" width="40" alt="Admin">
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row gy-4 mb-5">
        <div class="col-md-3">
            <div class="admin-card d-flex align-items-center justify-content-between h-100">
                <div>
                    <p class="theme-text-muted mb-1 small text-uppercase fw-bold">Total Orders</p>
                    <h3 class="theme-text mb-0"><?= $total_orders ?></h3>
                </div>
                <div class="fs-1 text-golden opacity-50"><i class="fas fa-shopping-bag"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card d-flex align-items-center justify-content-between h-100">
                <div>
                    <p class="theme-text-muted mb-1 small text-uppercase fw-bold">Total Bookings</p>
                    <h3 class="theme-text mb-0"><?= $total_bookings ?></h3>
                </div>
                <div class="fs-1 text-golden opacity-50"><i class="fas fa-calendar-check"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card d-flex align-items-center justify-content-between h-100">
                <div>
                    <p class="theme-text-muted mb-1 small text-uppercase fw-bold">Total Revenue</p>
                    <h3 class="theme-text mb-0">₹<?= number_format($total_revenue, 2) ?></h3>
                </div>
                <div class="fs-1 text-golden opacity-50"><i class="fas fa-wallet"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card d-flex align-items-center justify-content-between h-100">
                <div>
                    <p class="theme-text-muted mb-1 small text-uppercase fw-bold">Total Customers</p>
                    <h3 class="theme-text mb-0"><?= $total_users ?></h3>
                </div>
                <div class="fs-1 text-golden opacity-50"><i class="fas fa-users"></i></div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="admin-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="theme-text mb-0">Recent Orders</h5>
            <a href="orders.php" class="btn btn-sm btn-outline-golden">View All</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($recent_orders)): ?>
                    <tr><td colspan="5" class="text-center py-4 theme-text-muted">No recent orders found.</td></tr>
                    <?php else: foreach($recent_orders as $order): ?>
                    <tr>
                        <td>#ORD-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                        <td><?= htmlspecialchars($order['name'] ?? 'Guest') ?></td>
                        <td><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td>
                        <td>₹<?= number_format($order['total'], 2) ?></td>
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
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<?php require_once 'includes/footer.php'; ?>
