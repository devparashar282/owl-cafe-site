<?php 
require_once 'includes/db.php';
require_once 'includes/header.php'; 

// Require login for ordering
if(!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login to place an order.'); window.location.href='login.php';</script>";
    exit;
}

// Fetch Cart Items from session
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$subtotal = 0;
foreach($cart_items as $item) {
    $subtotal += ($item['price'] * $item['quantity']);
}
$gst = $subtotal * 0.05; // 5% GST
$delivery = $subtotal > 0 ? 50.00 : 0.00; // Only charge delivery if there are items
$total = $subtotal > 0 ? ($subtotal + $gst + $delivery) : 0;
?>

<section class="section-padding theme-bg-sec" style="padding-top: 150px; min-height: 100vh;">
    <div class="container">
        <h2 class="text-gradient mb-5">Checkout Summary</h2>
        
        <form action="php/process_order.php" method="POST">
            <div class="row gy-5">
                <!-- Cart Items & Details -->
                <div class="col-lg-7" data-aos="fade-right">
                    <div class="glass-card p-4 mb-4">
                        <h4 class="mb-4 text-golden">Your Items</h4>
                        
                        <?php if(empty($cart_items)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-shopping-cart fa-3x text-golden opacity-50 mb-3"></i>
                                <h5 class="theme-text">Your cart is empty</h5>
                                <p class="theme-text-muted">Add some delicious items from our menu!</p>
                            </div>
                        <?php else: ?>
                            <?php foreach($cart_items as $item): ?>
                            <div class="d-flex align-items-center mb-3 pb-3 border-bottom theme-border">
                                <img src="<?= $base_url ?>assets/images/<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                <div class="ms-3 flex-grow-1">
                                    <h5 class="mb-1"><?= $item['name'] ?></h5>
                                    <p class="theme-text-muted mb-0">₹<?= number_format($item['price'], 2) ?> x <?= $item['quantity'] ?></p>
                                </div>
                                <div class="text-end">
                                    <h5 class="text-golden mb-0">₹<?= number_format($item['price'] * $item['quantity'], 2) ?></h5>
                                    <a href="#" class="text-danger small remove-from-cart" data-id="<?= $item['id'] ?>"><i class="fas fa-trash"></i> Remove</a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <div class="mt-4">
                            <a href="<?= $base_url ?>menu.php" class="btn btn-outline-light btn-sm"><i class="fas fa-arrow-left me-2"></i> Continue Shopping</a>
                        </div>
                    </div>
                    
                    <div class="glass-card p-4">
                        <h4 class="mb-4 text-golden">Delivery Details</h4>
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label theme-text-muted">Full Name</label>
                                <input type="text" name="name" class="form-control bg-transparent theme-border theme-text" value="<?= $_SESSION['user_name'] ?? '' ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label theme-text-muted">Phone Number</label>
                                <input type="tel" name="phone" class="form-control bg-transparent theme-border theme-text" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label theme-text-muted">Delivery Address</label>
                                <textarea name="address" class="form-control bg-transparent theme-border theme-text" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary & Payment -->
                <div class="col-lg-5" data-aos="fade-left">
                    <div class="glass-card p-4 position-sticky" style="top: 100px;">
                        <h4 class="mb-4 text-golden">Order Summary</h4>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="theme-text-muted">Subtotal</span>
                            <span>₹<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="theme-text-muted">GST (5%)</span>
                            <span>₹<?= number_format($gst, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom theme-border">
                            <span class="theme-text-muted">Delivery Charge</span>
                            <span>₹<?= number_format($delivery, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="mb-0">Total Amount</h5>
                            <h5 class="text-gradient mb-0">₹<?= number_format($total, 2) ?></h5>
                        </div>
                        
                        <!-- Hidden inputs for backend processing -->
                        <input type="hidden" name="subtotal" value="<?= $subtotal ?>">
                        <input type="hidden" name="gst" value="<?= $gst ?>">
                        <input type="hidden" name="delivery" value="<?= $delivery ?>">
                        <input type="hidden" name="total" value="<?= $total ?>">
                        
                        <h5 class="mb-3 mt-4 text-golden">Payment Method</h5>
                        <div class="mb-2">
                            <div class="form-check custom-radio">
                                <input class="form-check-input" type="radio" name="payment_method" id="payCash" value="Cash" checked>
                                <label class="form-check-label theme-text" for="payCash">
                                    <i class="fas fa-money-bill-wave text-success me-2"></i> Cash on Delivery
                                </label>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="form-check custom-radio">
                                <input class="form-check-input" type="radio" name="payment_method" id="payUpi" value="UPI">
                                <label class="form-check-label theme-text" for="payUpi">
                                    <i class="fas fa-qrcode text-info me-2"></i> UPI / QR Code
                                </label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="form-check custom-radio">
                                <input class="form-check-input" type="radio" name="payment_method" id="payCard" value="Card">
                                <label class="form-check-label theme-text" for="payCard">
                                    <i class="fas fa-credit-card text-warning me-2"></i> Credit / Debit Card
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-premium w-100 py-3 fs-5" <?= $total > 0 ? '' : 'disabled' ?>>Place Order <i class="fas fa-arrow-right ms-2"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const removeBtns = document.querySelectorAll('.remove-from-cart');
    removeBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const itemId = this.getAttribute('data-id');
            const row = this.closest('.d-flex');
            
            // Show loading
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Removing';
            
            const formData = new FormData();
            formData.append('menu_id', itemId);
            
            fetch('php/remove_from_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Error removing item');
                    this.innerHTML = '<i class="fas fa-trash"></i> Remove';
                }
            })
            .catch(err => {
                console.error(err);
                alert('An error occurred');
                this.innerHTML = '<i class="fas fa-trash"></i> Remove';
            });
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
