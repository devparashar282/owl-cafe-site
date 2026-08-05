<?php 
require_once __DIR__ . '/includes/db.php';
require_once 'includes/header.php'; 

// Guest checkout enabled - no login required

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
        
        <form id="orderForm">
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
                        <h4 class="mb-4 text-golden">Order Details</h4>
                        
                        <div class="mb-4">
                            <label class="form-label theme-text-muted">Order Type</label>
                            <div class="d-flex gap-3">
                                <div class="form-check custom-radio flex-grow-1 border rounded p-2 theme-border text-center">
                                    <input class="form-check-input float-none ms-0 mb-2" type="radio" name="order_type" id="typeDelivery" value="Delivery" checked>
                                    <label class="form-check-label d-block theme-text" for="typeDelivery">
                                        <i class="fas fa-motorcycle fs-4 mb-1 d-block text-golden"></i> Delivery
                                    </label>
                                </div>
                                <div class="form-check custom-radio flex-grow-1 border rounded p-2 theme-border text-center">
                                    <input class="form-check-input float-none ms-0 mb-2" type="radio" name="order_type" id="typeDineIn" value="Dine In">
                                    <label class="form-check-label d-block theme-text" for="typeDineIn">
                                        <i class="fas fa-utensils fs-4 mb-1 d-block text-golden"></i> Dine In
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label theme-text">Full Name *</label>
                                <input type="text" name="name" class="form-control bg-transparent theme-border theme-text" placeholder="Enter your full name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label theme-text">Email Address *</label>
                                <input type="email" name="email" class="form-control bg-transparent theme-border theme-text" placeholder="For order invoice" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label theme-text-muted">Phone Number</label>
                                <input type="tel" name="phone" class="form-control bg-transparent theme-border theme-text" required>
                            </div>
                            <div class="col-12" id="deliveryAddressGroup">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label theme-text-muted mb-0">Delivery Address</label>
                                    <button type="button" class="btn btn-sm btn-outline-golden" id="btnCurrentLocation">
                                        <i class="fas fa-map-marker-alt me-1"></i> Use Current Location
                                    </button>
                                </div>
                                <textarea name="address" id="deliveryAddress" class="form-control bg-transparent theme-border theme-text" rows="3" required></textarea>
                            </div>
                            <div class="col-12" id="tableNumberGroup" style="display: none;">
                                <label class="form-label theme-text-muted">Table Number (Optional)</label>
                                <input type="text" name="table_number" class="form-control bg-transparent theme-border theme-text" placeholder="e.g. Table 4">
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
                            <span id="deliveryChargeDisplay">₹<?= number_format($delivery, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="mb-0">Total Amount</h5>
                            <h5 class="text-gradient mb-0" id="totalAmountDisplay">₹<?= number_format($total, 2) ?></h5>
                        </div>
                        
                        <!-- Hidden inputs for backend processing -->
                        <input type="hidden" name="subtotal" value="<?= $subtotal ?>">
                        <input type="hidden" name="gst" value="<?= $gst ?>">
                        <input type="hidden" name="delivery" id="hiddenDeliveryInput" value="<?= $delivery ?>">
                        <input type="hidden" name="total" id="hiddenTotalInput" value="<?= $total ?>">
                        
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
                                    <i class="fas fa-qrcode text-info me-2"></i> UPI / QR Code (Razorpay)
                                </label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="form-check custom-radio">
                                <input class="form-check-input" type="radio" name="payment_method" id="payCard" value="Card">
                                <label class="form-check-label theme-text" for="payCard">
                                    <i class="fas fa-credit-card text-warning me-2"></i> Credit / Debit Card (Razorpay)
                                </label>
                            </div>
                        </div>
                        
                        <button type="button" id="placeOrderBtn" class="btn btn-premium w-100 py-3 fs-5" <?= $total > 0 ? '' : 'disabled' ?>>Place Order <i class="fas fa-arrow-right ms-2"></i></button>
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

    // Dine In vs Delivery Toggle Logic
    const typeDelivery = document.getElementById('typeDelivery');
    const typeDineIn = document.getElementById('typeDineIn');
    const deliveryAddressGroup = document.getElementById('deliveryAddressGroup');
    const tableNumberGroup = document.getElementById('tableNumberGroup');
    const deliveryAddressInput = document.getElementById('deliveryAddress');
    const deliveryChargeDisplay = document.getElementById('deliveryChargeDisplay');
    const totalAmountDisplay = document.getElementById('totalAmountDisplay');
    const hiddenDeliveryInput = document.getElementById('hiddenDeliveryInput');
    const hiddenTotalInput = document.getElementById('hiddenTotalInput');
    
    // PHP variables passed to JS
    const subtotal = <?= $subtotal ?>;
    const gst = <?= $gst ?>;
    const baseDelivery = <?= $delivery ?>;
    
    function updateOrderType() {
        if(typeDineIn.checked) {
            deliveryAddressGroup.style.display = 'none';
            deliveryAddressInput.removeAttribute('required');
            tableNumberGroup.style.display = 'block';
            
            // Update Totals (Remove delivery fee)
            deliveryChargeDisplay.innerText = '₹0.00';
            hiddenDeliveryInput.value = 0;
            const newTotal = subtotal + gst;
            totalAmountDisplay.innerText = '₹' + newTotal.toFixed(2);
            hiddenTotalInput.value = newTotal;
        } else {
            deliveryAddressGroup.style.display = 'block';
            deliveryAddressInput.setAttribute('required', 'required');
            tableNumberGroup.style.display = 'none';
            
            // Update Totals (Add delivery fee back)
            deliveryChargeDisplay.innerText = '₹' + baseDelivery.toFixed(2);
            hiddenDeliveryInput.value = baseDelivery;
            const newTotal = subtotal + gst + baseDelivery;
            totalAmountDisplay.innerText = '₹' + newTotal.toFixed(2);
            hiddenTotalInput.value = newTotal;
        }
    }
    
    if(typeDelivery && typeDineIn) {
        typeDelivery.addEventListener('change', updateOrderType);
        typeDineIn.addEventListener('change', updateOrderType);
    }
    
    // Geolocation logic
    const btnCurrentLocation = document.getElementById('btnCurrentLocation');
    if(btnCurrentLocation) {
        btnCurrentLocation.addEventListener('click', function() {
            if (navigator.geolocation) {
                // Show loading state
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Locating...';
                this.disabled = true;

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;
                        
                        // Use OpenStreetMap Nominatim for Reverse Geocoding
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                            .then(response => response.json())
                            .then(data => {
                                if(data && data.display_name) {
                                    deliveryAddressInput.value = data.display_name;
                                } else {
                                    deliveryAddressInput.value = `Lat: ${lat}, Lon: ${lon}`;
                                    alert("Location found, but could not resolve to a street address.");
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching address:', error);
                                deliveryAddressInput.value = `Lat: ${lat}, Lon: ${lon}`;
                            })
                            .finally(() => {
                                // Restore button state
                                this.innerHTML = originalHtml;
                                this.disabled = false;
                            });
                    },
                    (error) => {
                        console.error('Geolocation error:', error);
                        alert("Unable to retrieve your location. Please check your browser permissions.");
                        this.innerHTML = originalHtml;
                        this.disabled = false;
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        });
    }
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    if(placeOrderBtn) {
        placeOrderBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const form = document.getElementById('orderForm');
            if(!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            
            if (data.payment_method === 'Cash') {
                // Submit normally for Cash
                form.action = 'php/process_order.php';
                form.method = 'POST';
                form.submit();
                return;
            }
            
            // For Razorpay
            placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            placeOrderBtn.disabled = true;
            
            fetch('php/create_razorpay_order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(rzpData => {
                if(rzpData.error) {
                    alert(rzpData.error);
                    placeOrderBtn.innerHTML = 'Place Order <i class="fas fa-arrow-right ms-2"></i>';
                    placeOrderBtn.disabled = false;
                    return;
                }
                
                var options = {
                    "key": rzpData.key,
                    "amount": rzpData.amount,
                    "currency": rzpData.currency,
                    "name": "Owl Cafe",
                    "description": "Food Order Payment",
                    "image": "assets/images/logo.jpg",
                    "order_id": rzpData.order_id,
                    "handler": function (response){
                        // Verify Payment
                        placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
                        
                        data.razorpay_payment_id = response.razorpay_payment_id;
                        data.razorpay_order_id = response.razorpay_order_id;
                        data.razorpay_signature = response.razorpay_signature;
                        
                        fetch('php/verify_payment.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(data)
                        })
                        .then(res => res.json())
                        .then(verifyResult => {
                            if(verifyResult.success) {
                                window.location.href = 'my_orders.php';
                            } else {
                                alert('Payment verification failed: ' + (verifyResult.error || 'Unknown Error'));
                                placeOrderBtn.innerHTML = 'Place Order <i class="fas fa-arrow-right ms-2"></i>';
                                placeOrderBtn.disabled = false;
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('An error occurred during verification');
                            placeOrderBtn.innerHTML = 'Place Order <i class="fas fa-arrow-right ms-2"></i>';
                            placeOrderBtn.disabled = false;
                        });
                    },
                    "prefill": {
                        "name": data.name,
                        "contact": data.phone
                    },
                    "theme": {
                        "color": "#d4af37"
                    }
                };
                var rzp1 = new Razorpay(options);
                rzp1.on('payment.failed', function (response){
                    alert(response.error.description);
                    placeOrderBtn.innerHTML = 'Place Order <i class="fas fa-arrow-right ms-2"></i>';
                    placeOrderBtn.disabled = false;
                });
                rzp1.open();
            })
            .catch(err => {
                console.error(err);
                alert('Failed to initialize payment gateway.');
                placeOrderBtn.innerHTML = 'Place Order <i class="fas fa-arrow-right ms-2"></i>';
                placeOrderBtn.disabled = false;
            });
        });
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
< s c r i p t   s r c = " h t t p s : / / c h e c k o u t . r a z o r p a y . c o m / v 1 / c h e c k o u t . j s " > < / s c r i p t > 
 
 