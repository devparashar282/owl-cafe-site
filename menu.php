<?php 
require_once 'includes/db.php';
require_once 'includes/header.php'; 

// Fetch categories
$stmt = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $stmt->fetchAll();

// Fetch menu items
$stmt = $pdo->query("SELECT m.*, c.name as category_name FROM menu m JOIN categories c ON m.category_id = c.id ORDER BY m.category_id ASC, m.name ASC");
$menu_items = $stmt->fetchAll();
?>

<!-- Page Header -->
<section class="section-padding theme-bg-sec" style="padding-top: 150px; padding-bottom: 50px;">
    <div class="container text-center" data-aos="fade-up">
        <h1 class="theme-text glow-text display-1" style="font-family: 'Playfair Display', serif;">Our Exquisite Menu</h1>
        <p class="theme-text-muted max-w-2xl mx-auto fs-5">Discover a world of flavors crafted with passion and the finest ingredients.</p>
        
        <!-- Search -->
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="position-relative">
                    <input type="text" id="menuSearch" class="form-control bg-transparent border-golden theme-text py-3 ps-4 pe-5 rounded-pill" placeholder="Search for coffee, pizza, desserts...">
                    <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-4 text-golden"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Menu Section -->
<section class="section-padding" id="menu-section">
    <div class="container">
        
        <!-- Category Tabs -->
        <ul class="nav nav-pills justify-content-center menu-tabs mb-5 pb-3 border-bottom theme-border" id="menuTabs" role="tablist" data-aos="fade-up" data-aos-delay="100">
            <?php 
            $first_cat = true;
            foreach($categories as $cat): 
            ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?= $first_cat ? 'active' : '' ?>" id="tab-<?= $cat['id'] ?>" data-bs-toggle="pill" data-bs-target="#content-<?= $cat['id'] ?>" type="button" role="tab" aria-selected="<?= $first_cat ? 'true' : 'false' ?>"><?= $cat['name'] ?></button>
            </li>
            <?php 
            $first_cat = false;
            endforeach; 
            ?>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="menuTabContent" data-aos="fade-up" data-aos-delay="200">
            
            <!-- DYNAMIC CATEGORY TABS -->
            <?php 
            $first_pane = true;
            foreach($categories as $cat): 
            ?>
            <div class="tab-pane fade <?= $first_pane ? 'show active' : '' ?>" id="content-<?= $cat['id'] ?>" role="tabpanel" tabindex="0">
            <?php $first_pane = false; ?>
                <div class="row gy-4">
                    <?php 
                    $has_items = false;
                    foreach($menu_items as $item): 
                        if($item['category_id'] == $cat['id']):
                            $has_items = true;
                            $image_val = $item['image'] ?? '';
                            if (!empty($image_val) && strpos($image_val, 'http') === 0) {
                                $img_src = $image_val;
                            } else {
                                $img_src = (!empty($image_val)) ? $base_url . 'assets/images/' . $image_val : $base_url . 'assets/images/premium_coffee_1783449279091.png';
                            }
                            $veg_class = ($item['type'] == 'Veg') ? 'veg-badge' : 'nonveg-badge';
                            $veg_icon = ($item['type'] == 'Veg') ? 'fa-leaf' : 'fa-drumstick-bite';
                    ?>
                    <div class="col-lg-4 col-md-6 menu-item-card" data-title="<?= strtolower($item['name']) ?>">
                        <div class="glass-card h-100 position-relative overflow-hidden card-hover-lift d-flex flex-column">
                            <div class="position-absolute top-0 end-0 m-3 z-2">
                                <span class="badge bg-golden text-black fs-6 py-2 px-3 rounded-pill"><i class="fas fa-star me-1"></i> <?= $item['rating'] ?? '4.5' ?></span>
                            </div>
                            <div class="overflow-hidden rounded-4 mb-4" style="height: 250px;">
                                <img src="<?= $img_src ?>" alt="<?= $item['name'] ?>" class="img-fluid w-100 h-100 object-fit-cover hover-zoom" style="transition: transform 0.5s; cursor: pointer;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-golden fw-bold small text-uppercase" style="letter-spacing: 2px;"><?= $item['category_name'] ?? 'Menu Item' ?></span>
                                <div>
                                    <span class="<?= $veg_class ?>"><i class="fas <?= $veg_icon ?>"></i></span>
                                    <?php if($item['is_special']): ?>
                                        <span class="badge bg-danger ms-1 small">Special</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <h3 class="h4 mb-2 mt-1"><?= $item['name'] ?></h3>
                            <p class="theme-text-muted small flex-grow-1"><?= $item['description'] ?></p>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <span class="fs-3 fw-bold text-gradient">₹<?= $item['price'] ?></span>
                                <button class="btn btn-premium btn-sm px-4 py-2 rounded-pill add-to-cart" data-id="<?= $item['id'] ?>">Add <i class="fas fa-plus ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                    <?php 
                        endif;
                    endforeach; 
                    if(!$has_items):
                        echo "<div class='col-12'><p class='text-center theme-text-muted'>No items found in this category.</p></div>";
                    endif;
                    ?>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>

<!-- Include JS logic for Search and Cart -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Menu Search Filter
    const searchInput = document.getElementById('menuSearch');
    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const activeTabPane = document.querySelector('.tab-pane.active');
            const items = activeTabPane.querySelectorAll('.menu-item-card');
            
            items.forEach(item => {
                const title = item.getAttribute('data-title');
                if (title.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Add to Cart Simulation
    const addToCartBtns = document.querySelectorAll('.add-to-cart');
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const itemId = this.getAttribute('data-id');
            const btnOriginalText = this.innerHTML;
            
            // UI feedback
            this.innerHTML = '<i class="fas fa-check"></i> Added';
            this.classList.replace('btn-premium', 'btn-success');
            
            // Simulated AJAX Cart Add (update cart badge)
            const cartBadge = document.querySelector('.fa-shopping-cart').nextElementSibling;
            if(cartBadge) {
                let currentCount = parseInt(cartBadge.innerText);
                cartBadge.innerText = currentCount + 1;
                
                // Animation for badge
                cartBadge.style.transform = 'scale(1.5)';
                setTimeout(() => {
                    cartBadge.style.transform = 'scale(1) translate(-50%, -50%)';
                }, 300);
            }
            
            setTimeout(() => {
                this.innerHTML = btnOriginalText;
                this.classList.replace('btn-success', 'btn-premium');
            }, 2000);
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
