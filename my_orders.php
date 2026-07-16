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
                                            <button class="btn btn-sm btn-outline-golden view-order-btn" data-id="<?= $order['id'] ?>">View Details</button>
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

<!-- Order Details Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content glass-card border-golden">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title theme-text glow-text"><i class="fas fa-receipt me-2 text-golden"></i> Order Invoice</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="orderModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-golden" role="status"></div>
                    <p class="mt-2 theme-text-muted">Loading invoice details...</p>
                </div>
            </div>
            <div class="modal-footer border-top border-secondary">
                <button type="button" class="btn btn-outline-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-premium rounded-pill px-4" onclick="window.print()"><i class="fas fa-print me-1"></i> Print Receipt</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const orderModal = new bootstrap.Modal(document.getElementById('orderModal'));
    const modalBody = document.getElementById('orderModalBody');

    document.querySelectorAll('.view-order-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-id');
            orderModal.show();
            
            modalBody.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-golden" role="status"></div>
                    <p class="mt-2 theme-text-muted">Loading invoice details...</p>
                </div>`;

            fetch(`get_order_details.php?order_id=${orderId}`)
                .then(res => res.json())
                .then(data => {
                    if(data.error) {
                        modalBody.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                        return;
                    }

                    const order = data.order;
                    const items = data.items;
                    
                    let itemsHtml = '';
                    items.forEach(item => {
                        itemsHtml += `
                            <tr>
                                <td class="text-light">${item.name}</td>
                                <td class="text-center text-light">${item.quantity}</td>
                                <td class="text-end text-light">₹${parseFloat(item.price).toFixed(2)}</td>
                                <td class="text-end text-golden">₹${(parseFloat(item.price) * parseInt(item.quantity)).toFixed(2)}</td>
                            </tr>
                        `;
                    });

                    modalBody.innerHTML = `
                        <div class="row mb-4">
                            <div class="col-sm-6">
                                <h6 class="theme-text-muted mb-1">Billed To:</h6>
                                <p class="text-light fw-bold mb-0">${order.name}</p>
                                <p class="text-light small mb-0">${order.phone}</p>
                                <p class="text-light small mb-0">${order.address}</p>
                            </div>
                            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                <h6 class="theme-text-muted mb-1">Invoice Details:</h6>
                                <p class="text-light mb-0"><strong>Receipt ID:</strong> ${order.formatted_id}</p>
                                <p class="text-light mb-0"><strong>Date:</strong> ${order.formatted_date}</p>
                                <p class="text-light mb-0"><strong>Payment Method:</strong> ${order.payment_method}</p>
                                <p class="text-light mb-0"><strong>Status:</strong> <span class="text-golden">${order.status}</span></p>
                            </div>
                        </div>
                        
                        <div class="table-responsive mb-4">
                            <table class="table table-borderless table-sm">
                                <thead>
                                    <tr class="border-bottom border-secondary">
                                        <th class="text-golden fw-normal pb-2">Item Description</th>
                                        <th class="text-center text-golden fw-normal pb-2">Qty</th>
                                        <th class="text-end text-golden fw-normal pb-2">Price</th>
                                        <th class="text-end text-golden fw-normal pb-2">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${itemsHtml}
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="row justify-content-end">
                            <div class="col-sm-6 col-md-5">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="theme-text-muted">Subtotal</span>
                                    <span class="text-light">₹${parseFloat(order.subtotal).toFixed(2)}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="theme-text-muted">GST (5%)</span>
                                    <span class="text-light">₹${parseFloat(order.gst).toFixed(2)}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3 border-bottom border-secondary pb-3">
                                    <span class="theme-text-muted">Delivery</span>
                                    <span class="text-light">₹${parseFloat(order.delivery_charge).toFixed(2)}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-golden fw-bold fs-5">Total Amount</span>
                                    <span class="text-golden fw-bold fs-4">₹${parseFloat(order.total).toFixed(2)}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-5 pt-3 border-top border-secondary">
                            <p class="theme-text-muted small mb-0">Thank you for dining with Owl Cafe!</p>
                            <p class="text-golden small fw-bold">Wise Eats. Great Treats.</p>
                        </div>
                    `;
                })
                .catch(err => {
                    modalBody.innerHTML = `<div class="alert alert-danger">Failed to load order details. Please try again later.</div>`;
                });
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
