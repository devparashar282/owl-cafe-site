<?php
session_start();
require_once 'includes/db.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user orders
try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    $orders = [];
}

require_once 'includes/header.php';
?>

<!-- Page Header -->
<section class="section-padding theme-bg-sec" style="padding-top: 150px; padding-bottom: 50px;">
    <div class="container text-center" data-aos="fade-up">
        <h1 class="text-gradient mb-3">My Orders</h1>
        <p class="theme-text-muted max-w-2xl mx-auto">Track and view your past orders with Owl Cafe.</p>
    </div>
</section>

<!-- Orders Content -->
<section class="section-padding" style="min-height: 50vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="glass-card p-4 p-md-5" data-aos="fade-up">
                    
                    <?php if(empty($orders)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-bag fa-4x text-golden mb-4 opacity-50"></i>
                            <h4 class="theme-text">No orders yet</h4>
                            <p class="theme-text-muted">Looks like you haven't placed any orders yet. Discover our premium menu!</p>
                            <a href="menu.php" class="btn btn-premium mt-3">Browse Menu</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-borderless text-white align-middle">
                                <thead>
                                    <tr style="border-bottom: 1px solid rgba(212, 175, 55, 0.3);">
                                        <th class="text-golden fw-normal pb-3">Order ID</th>
                                        <th class="text-golden fw-normal pb-3">Date</th>
                                        <th class="text-golden fw-normal pb-3">Total Amount</th>
                                        <th class="text-golden fw-normal pb-3">Payment</th>
                                        <th class="text-golden fw-normal pb-3">Status</th>
                                        <th class="text-golden fw-normal pb-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($orders as $order): ?>
                                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                        <td class="py-4">#ORD-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                        <td class="py-4"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td>
                                        <td class="py-4">₹<?= number_format($order['total'], 2) ?></td>
                                        <td class="py-4"><?= $order['payment_method'] ?></td>
                                        <td class="py-4">
                                            <?php 
                                            $badgeClass = 'bg-secondary';
                                            if($order['status'] == 'Pending') $badgeClass = 'bg-warning text-dark';
                                            if($order['status'] == 'Processing') $badgeClass = 'bg-info text-dark';
                                            if($order['status'] == 'Delivered') $badgeClass = 'bg-success';
                                            if($order['status'] == 'Cancelled') $badgeClass = 'bg-danger';
                                            ?>
                                            <span class="badge <?= $badgeClass ?>"><?= $order['status'] ?></span>
                                        </td>
                                        <td class="py-4">
                                            <button class="btn btn-sm btn-outline-light" onclick="alert('Order details coming soon!')">View Details</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
