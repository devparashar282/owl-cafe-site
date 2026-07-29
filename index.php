<?php 
require_once __DIR__ . '/includes/db.php';
require_once 'includes/media.php';

// Fetch active movie night
try {
    $stmt = $pdo->query("SELECT * FROM movie_nights WHERE status = 'Active' ORDER BY movie_date ASC LIMIT 1");
    $active_movie = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $active_movie = null;
}

// Fetch active daily offers
try {
    $stmt = $pdo->query("SELECT * FROM offers WHERE status = 'Active' ORDER BY created_at DESC");
    $active_offers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $active_offers = [];
}

require_once 'includes/header.php'; 
?>

<!-- Hero Section -->
<section class="hero position-relative" id="home">
    <!-- Carousel Background -->
    <div id="heroCarousel" class="carousel slide carousel-fade position-absolute top-0 start-0 w-100 h-100 z-0" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="4000">
        <div class="carousel-inner w-100 h-100">
            <div class="carousel-item active w-100 h-100">
                <img src="<?= $base_url ?>assets/images/gallery/gallery-1.jpeg" class="d-block w-100 h-100" style="object-fit: cover;" alt="Owl Cafe Interior">
            </div>
            <div class="carousel-item w-100 h-100">
                <img src="<?= $base_url ?>assets/images/gallery/gallery-2.jpeg" class="d-block w-100 h-100" style="object-fit: cover;" alt="Owl Cafe Interior">
            </div>
            <div class="carousel-item w-100 h-100">
                <img src="<?= $base_url ?>assets/images/gallery/gallery-3.jpeg" class="d-block w-100 h-100" style="object-fit: cover;" alt="Owl Cafe Interior">
            </div>
        </div>
    </div>
    
    <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100 z-1" style="background: rgba(10, 10, 10, 0.75);"></div>
    
    <div class="container hero-content text-center position-relative z-2">
        <div class="row justify-content-center">
            <div class="col-lg-10" data-aos="zoom-in">
                <!-- Large Owl Logo -->
                <img src="<?= $base_url ?>assets/images/logo.jpg" alt="Owl Cafe Logo" class="rounded-circle mb-4" style="width: 110px; height: 110px; object-fit: contain; box-shadow: 0 0 25px rgba(212, 175, 55, 0.5) !important; border: 2px solid var(--golden);">
                
                <h1 class="text-white glow-text display-1" style="font-family: 'Playfair Display', serif;">OWL CAFE</h1>
                <h2 class="text-gradient mb-4 fs-2" style="font-family: 'Outfit', sans-serif;">Wise Eats. Great Treats.</h2>
                <p class="text-white mx-auto fs-5 mb-5" style="max-width: 700px;">Experience premium coffee, delicious food, and unforgettable moments in the heart of Gomti Nagar.</p>
                
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="<?= $base_url ?>menu.php" class="btn btn-premium px-5 py-3 fs-5 rounded-pill">Explore Menu</a>
                    <a href="<?= $base_url ?>booking.php" class="btn btn-premium btn-outline px-5 py-3 fs-5 rounded-pill">Book a Table</a>
                    <button class="btn btn-outline-light installAppBtn px-5 py-3 fs-5 rounded-pill"><i class="fas fa-mobile-alt me-2"></i> Get App</button>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Daily Special Offers Slider -->
<?php if (!empty($active_offers)): ?>
<section class="section-padding theme-bg-sec pt-4 pb-5">
    <div class="container">
        <div class="section-title mb-4" data-aos="fade-up">
            <span class="subtitle text-danger fw-bold"><i class="fas fa-fire me-1"></i> Today's Deals</span>
            <h2>Exclusive Combos</h2>
        </div>
        
        <div class="swiper offers-slider" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper-wrapper">
                <?php foreach ($active_offers as $offer): ?>
                <div class="swiper-slide">
                    <div class="glass-card overflow-hidden h-100 card-hover-lift rounded-4 text-center" style="border: 2px solid rgba(212, 175, 55, 0.5); box-shadow: 0 10px 30px rgba(0,0,0,0.5); background: #0a0a0a;">
                        <img src="<?= media_resolve_src($offer['image'], $base_url) ?>" class="img-fluid" alt="<?= htmlspecialchars($offer['title']) ?>" style="max-height: 650px; width: auto; object-fit: contain;">
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination position-static mt-4"></div>
            <!-- Add Navigation -->
            <div class="swiper-button-next text-golden"></div>
            <div class="swiper-button-prev text-golden"></div>
        </div>
    </div>
</section>

<!-- Initialize Swiper for Offers -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.offers-slider', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.offers-slider .swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.offers-slider .swiper-button-next',
            prevEl: '.offers-slider .swiper-button-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            }
        }
    });
});
</script>
<?php endif; ?>

<!-- About Section -->
<section class="section-padding" id="about">
    <div class="container">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="position-relative">
                    <div class="glass-card overflow-hidden p-2 card-hover-lift">
                        <img src="<?= $base_url ?>assets/images/gallery/gallery-6.jpeg" class="img-fluid rounded-4 w-100" style="object-fit: cover; max-height: 500px;" alt="Luxury Owl Cafe">
                    </div>
                    <div class="position-absolute top-0 start-0 translate-middle w-25 h-25 bg-golden rounded-circle blur-effect z-n1" style="filter: blur(50px); opacity: 0.3;"></div>
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5" data-aos="fade-left">
                <div class="section-title text-start mb-4">
                    <span class="subtitle">Our Story</span>
                    <h2>OWL CAFE</h2>
                </div>
                <p class="theme-text-muted fs-5 mb-4">OWL CAFE is a premium destination where delicious food meets elegant ambience. We serve handcrafted coffee, Chinese cuisine, burgers, momos, and signature Al-Baik specials in a cozy owl-themed environment.</p>
                
                <div class="row mt-5 text-center text-sm-start">
                    <div class="col-sm-4 mb-4">
                        <div class="counter-number" data-target="1000" data-suffix="+">0</div>
                        <p class="theme-text-muted mb-0 fs-5 fw-bold text-white">Happy Customers</p>
                    </div>
                    <div class="col-sm-4 mb-4">
                        <div class="counter-number" data-target="50" data-suffix="+">0</div>
                        <p class="theme-text-muted mb-0 fs-5 fw-bold text-white">Menu Items</p>
                    </div>
                    <div class="col-sm-4 mb-4">
                        <div class="counter-number" data-target="5" data-suffix="★">0</div>
                        <p class="theme-text-muted mb-0 fs-5 fw-bold text-white">Customer Rating</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Menu -->
<section class="section-padding theme-bg-sec" id="featured">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <span class="subtitle">Masterpieces</span>
            <h2>Featured Menu</h2>
        </div>
        
        <div class="row gy-4">
            <!-- Mocked Featured Items -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="glass-card h-100 position-relative overflow-hidden card-hover-lift">
                    <div class="position-absolute top-0 end-0 m-3 z-2">
                        <span class="badge bg-golden text-black fs-6 py-2 px-3 rounded-pill"><i class="fas fa-star me-1"></i> 5.0</span>
                    </div>
                    <div class="overflow-hidden rounded-4 mb-4" style="height: 250px;">
                        <img src="<?= $base_url ?>assets/images/premium_coffee_1783449279091.png" class="img-fluid w-100 h-100 object-fit-cover hover-zoom transition-transform duration-500" alt="Coffee" style="transition: transform 0.5s; cursor: pointer;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    </div>
                    <span class="text-golden fw-bold small text-uppercase" style="letter-spacing: 2px;">Coffee</span>
                    <h3 class="h4 mb-2 mt-1">Premium Gold Cappuccino</h3>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <span class="fs-3 fw-bold text-gradient">₹199</span>
                        <a href="<?= $base_url ?>menu.php" class="btn btn-premium btn-sm px-4 py-2 rounded-pill">Order Now</a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="glass-card h-100 position-relative overflow-hidden card-hover-lift">
                    <div class="position-absolute top-0 end-0 m-3 z-2">
                        <span class="badge bg-golden text-black fs-6 py-2 px-3 rounded-pill"><i class="fas fa-star me-1"></i> 4.9</span>
                    </div>
                    <div class="overflow-hidden rounded-4 mb-4" style="height: 250px;">
                        <img src="<?= $base_url ?>assets/images/burger_premium_1783450998976.png" class="img-fluid w-100 h-100 object-fit-cover hover-zoom transition-transform duration-500" alt="Burgers" style="transition: transform 0.5s; cursor: pointer;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    </div>
                    <span class="text-golden fw-bold small text-uppercase" style="letter-spacing: 2px;">Burgers</span>
                    <h3 class="h4 mb-2 mt-1">The Owl Signature Burger</h3>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <span class="fs-3 fw-bold text-gradient">₹249</span>
                        <a href="<?= $base_url ?>menu.php" class="btn btn-premium btn-sm px-4 py-2 rounded-pill">Order Now</a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="glass-card h-100 position-relative overflow-hidden card-hover-lift">
                    <div class="position-absolute top-0 end-0 m-3 z-2">
                        <span class="badge bg-golden text-black fs-6 py-2 px-3 rounded-pill"><i class="fas fa-star me-1"></i> 4.8</span>
                    </div>
                    <div class="overflow-hidden rounded-4 mb-4" style="height: 250px;">
                        <img src="<?= $base_url ?>assets/images/chicken_starters_premium_1783450986638.png" class="img-fluid w-100 h-100 object-fit-cover hover-zoom transition-transform duration-500" alt="Al-Baik" style="transition: transform 0.5s; cursor: pointer;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    </div>
                    <span class="text-golden fw-bold small text-uppercase" style="letter-spacing: 2px;">Al-Baik Special</span>
                    <h3 class="h4 mb-2 mt-1">Crispy Al-Baik Chicken</h3>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <span class="fs-3 fw-bold text-gradient">₹349</span>
                        <a href="<?= $base_url ?>menu.php" class="btn btn-premium btn-sm px-4 py-2 rounded-pill">Order Now</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="<?= $base_url ?>menu.php" class="btn btn-outline-light px-5 py-3 rounded-pill fs-5">View All Categories</a>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="section-padding">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <span class="subtitle">Our Promise</span>
            <h2>Why Choose Us</h2>
        </div>
        <div class="row gy-4 text-center">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="glass-card p-5 h-100 card-hover-lift">
                    <div class="btn-icon bg-golden text-black mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2.2rem;"><i class="fas fa-leaf"></i></div>
                    <h4 class="text-white mb-3">Fresh Ingredients</h4>
                    <p class="theme-text-muted mb-0">We source only the freshest, highest quality ingredients daily to ensure maximum flavor.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="glass-card p-5 h-100 card-hover-lift">
                    <div class="btn-icon bg-golden text-black mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2.2rem;"><i class="fas fa-bolt"></i></div>
                    <h4 class="text-white mb-3">Fast Service</h4>
                    <p class="theme-text-muted mb-0">Quick, efficient, and exceptionally polite staff dedicated to your perfect experience.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="glass-card p-5 h-100 card-hover-lift">
                    <div class="btn-icon bg-golden text-black mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2.2rem;"><i class="fas fa-crown"></i></div>
                    <h4 class="text-white mb-3">Luxury Ambience</h4>
                    <p class="theme-text-muted mb-0">A meticulously designed, cozy, and premium setting perfect for any special occasion.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="glass-card p-5 h-100 card-hover-lift">
                    <div class="btn-icon bg-golden text-black mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2.2rem;"><i class="fas fa-child"></i></div>
                    <h4 class="text-white mb-3">Family Friendly</h4>
                    <p class="theme-text-muted mb-0">A welcoming, safe, and entertaining environment designed for families and children.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="glass-card p-5 h-100 card-hover-lift">
                    <div class="btn-icon bg-golden text-black mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2.2rem;"><i class="fas fa-mobile-alt"></i></div>
                    <h4 class="text-white mb-3">Online Ordering</h4>
                    <p class="theme-text-muted mb-0">Easy and seamless online ordering system right from your phone or computer.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                <div class="glass-card p-5 h-100 card-hover-lift">
                    <div class="btn-icon bg-golden text-black mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2.2rem;"><i class="fas fa-chair"></i></div>
                    <h4 class="text-white mb-3">Easy Table Booking</h4>
                    <p class="theme-text-muted mb-0">Reserve your premium VIP table in advance with just a single click online.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Preview -->
<section class="section-padding theme-bg-sec">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <span class="subtitle">Aesthetic Brilliance</span>
            <h2>Gallery Preview</h2>
        </div>
        <div class="row g-4 masonry-grid" data-aos="fade-up" data-aos-delay="100">
            <div class="col-md-4">
                <div class="glass-card p-2 overflow-hidden h-100 card-hover-lift">
                    <img src="<?= $base_url ?>assets/images/gallery/gallery-8.jpeg" class="img-fluid rounded w-100 h-100 object-fit-cover" style="min-height: 250px;" alt="Gallery" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" style="transition: 0.5s;">
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-2 overflow-hidden h-100 card-hover-lift">
                    <img src="<?= $base_url ?>assets/images/gallery/gallery-9.jpeg" class="img-fluid rounded w-100 h-100 object-fit-cover" style="min-height: 250px;" alt="Gallery" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" style="transition: 0.5s;">
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-2 overflow-hidden h-100 card-hover-lift">
                    <img src="<?= $base_url ?>assets/images/gallery/gallery-10.jpeg" class="img-fluid rounded w-100 h-100 object-fit-cover" style="min-height: 250px;" alt="Gallery" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" style="transition: 0.5s;">
                </div>
            </div>
        </div>
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="<?= $base_url ?>gallery.php" class="btn btn-premium px-5 py-3 rounded-pill fs-5">View Full Gallery</a>
        </div>
    </div>
</section>

<!-- Customer Reviews Slider -->
<section class="section-padding" id="reviews">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <span class="subtitle">Testimonials</span>
            <h2>Customer Reviews</h2>
        </div>
        
        <div class="swiper reviews-slider mt-4">
            <div class="swiper-wrapper">
                <div class="swiper-slide h-auto">
                    <div class="glass-card p-5 h-100 card-hover-lift" style="border-top: 4px solid var(--golden);">
                        <div class="d-flex text-golden mb-4 fs-5">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="fs-5 font-italic mb-5 text-white">"Absolutely the best cafe in Lucknow! The ambiance is incredibly luxurious, and their premium cappuccino is a masterpiece."</p>
                        <div class="d-flex align-items-center mt-auto">
                            <img src="https://ui-avatars.com/api/?name=Rahul+Verma&background=d4af37&color=0a0a0a&size=100" class="rounded-circle me-3" width="60" height="60" alt="Rahul Verma">
                            <div>
                                <h5 class="mb-0 text-gradient font-playfair">Rahul Verma</h5>
                                <span class="theme-text-muted small">Local Guide</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide h-auto">
                    <div class="glass-card p-5 h-100 card-hover-lift" style="border-top: 4px solid var(--golden);">
                        <div class="d-flex text-golden mb-4 fs-5">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="fs-5 font-italic mb-5 text-white">"The glassmorphism aesthetic is amazing. Booked a table for a date and the setup was perfect. Highly recommended!"</p>
                        <div class="d-flex align-items-center mt-auto">
                            <img src="https://ui-avatars.com/api/?name=Sneha+Sharma&background=d4af37&color=0a0a0a&size=100" class="rounded-circle me-3" width="60" height="60" alt="Sneha Sharma">
                            <div>
                                <h5 class="mb-0 text-gradient font-playfair">Sneha Sharma</h5>
                                <span class="theme-text-muted small">Food Blogger</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide h-auto">
                    <div class="glass-card p-5 h-100 card-hover-lift" style="border-top: 4px solid var(--golden);">
                        <div class="d-flex text-golden mb-4 fs-5">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <p class="fs-5 font-italic mb-5 text-white">"Online ordering was seamless. The pizza arrived hot and the ingredients felt very premium. Will visit soon!"</p>
                        <div class="d-flex align-items-center mt-auto">
                            <img src="https://ui-avatars.com/api/?name=Amit+Kumar&background=d4af37&color=0a0a0a&size=100" class="rounded-circle me-3" width="60" height="60" alt="Amit Kumar">
                            <div>
                                <h5 class="mb-0 text-gradient font-playfair">Amit Kumar</h5>
                                <span class="theme-text-muted small">Customer</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination position-static mt-5"></div>
        </div>
    </div>
</section>

<!-- Table Booking CTA -->
<section class="section-padding position-relative overflow-hidden" style="background: linear-gradient(rgba(10,10,10,0.8), rgba(10,10,10,0.8)), url('<?= $base_url ?>assets/images/gallery/gallery-11.jpeg') center/cover fixed;">
    <div class="container text-center position-relative z-2" data-aos="zoom-in">
        <h2 class="display-4 text-white mb-4 glow-text" style="font-family: 'Playfair Display', serif;">Reserve Your Table Today</h2>
        <p class="fs-4 theme-text-muted mb-5 max-w-2xl mx-auto">Skip the line and ensure a perfect premium dining experience by booking your table in advance.</p>
        <div class="d-flex flex-wrap gap-4 justify-content-center">
            <a href="<?= $base_url ?>booking.php" class="btn btn-premium px-5 py-3 fs-5 rounded-pill">Book Now</a>
            <a href="tel:9987043742" class="btn btn-outline-light px-5 py-3 fs-5 rounded-pill"><i class="fas fa-phone-alt me-2"></i> Call Now</a>
        </div>
    </div>
</section>

<!-- Location Section -->
<section class="section-padding theme-bg-sec">
    <div class="container">
        <div class="row align-items-center gy-5">
            <div class="col-lg-4" data-aos="fade-right">
                <div class="section-title text-start mb-4">
                    <span class="subtitle">Visit Us</span>
                    <h2>Location</h2>
                </div>
                <div class="glass-card p-4 card-hover-lift mb-4">
                    <div class="d-flex align-items-start">
                        <div class="btn-icon bg-golden text-black me-3 mt-1"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <h5 class="text-white mb-1">OWL CAFE</h5>
                            <p class="theme-text-muted mb-0">Gomti Nagar,<br>Lucknow, Uttar Pradesh</p>
                        </div>
                    </div>
                </div>
                <div class="glass-card p-4 card-hover-lift">
                    <div class="d-flex align-items-center">
                        <div class="btn-icon bg-golden text-black me-3"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <h5 class="text-white mb-1">Phone</h5>
                            <p class="theme-text-muted mb-0">9987043742</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8" data-aos="fade-left">
                <div class="glass-card p-2 card-hover-lift h-100">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d113911.23351910247!2d80.9329037!3d26.8486955!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x399bfd9bf0465249%3A0x67303f8a467e42d7!2sGomti%20Nagar%2C%20Lucknow%2C%20Uttar%20Pradesh!5e0!3m2!1sen!2sin!4v1680000000000!5m2!1sen!2sin" width="100%" height="450" style="border:0; border-radius: 10px; filter: invert(90%) hue-rotate(180deg) brightness(85%) contrast(110%);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
