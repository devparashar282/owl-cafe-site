<?php 
require_once 'includes/db.php';
require_once 'includes/header.php'; 
?>

<!-- Page Header -->
<section class="section-padding theme-bg-sec" style="padding-top: 150px; padding-bottom: 50px;">
    <div class="container text-center" data-aos="fade-up">
        <h1 class="theme-text glow-text display-1" style="font-family: 'Playfair Display', serif;">Our Story</h1>
        <p class="theme-text-muted max-w-2xl mx-auto fs-5">A journey of passion, flavor, and luxury in every cup and plate.</p>
    </div>
</section>

<!-- Story Section -->
<section class="section-padding">
    <div class="container">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="glass-card overflow-hidden p-2 position-relative card-hover-lift">
                    <img src="<?= $base_url ?>assets/images/gallery/gallery-7.jpeg" class="img-fluid rounded w-100" style="object-fit: cover; max-height: 500px;" alt="Owl Cafe History">
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5" data-aos="fade-left">
                <div class="section-title text-start mb-4">
                    <span class="subtitle">The Beginning</span>
                    <h2 style="font-family: 'Playfair Display', serif;">How Owl Cafe Was Born</h2>
                </div>
                <p class="theme-text-muted fs-5 mb-4">Founded with a vision to revolutionize the cafe culture in Lucknow, Owl Cafe started as a dream to create a space where luxury meets comfort. Every detail, from our plush seating to our curated menu, reflects our dedication to excellence.</p>
                
                <div class="row mt-5">
                    <div class="col-6">
                        <h3 class="text-golden fw-bold fs-1 mb-0">10k+</h3>
                        <p class="theme-text-muted">Happy Customers</p>
                    </div>
                    <div class="col-6">
                        <h3 class="text-golden fw-bold fs-1 mb-0">50+</h3>
                        <p class="theme-text-muted">Premium Dishes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="section-padding theme-bg-sec">
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="glass-card h-100 text-center p-5 card-hover-lift">
                    <div class="btn-icon bg-golden text-black mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2.2rem;"><i class="fas fa-bullseye"></i></div>
                    <h3 class="mb-3 theme-text" style="font-family: 'Playfair Display', serif;">Our Mission</h3>
                    <p class="theme-text-muted mb-0">To consistently provide an unparalleled dining and coffee experience through exceptional service, premium quality, and a luxurious atmosphere.</p>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="glass-card h-100 text-center p-5 card-hover-lift">
                    <div class="btn-icon bg-golden text-black mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2.2rem;"><i class="fas fa-eye"></i></div>
                    <h3 class="mb-3 theme-text" style="font-family: 'Playfair Display', serif;">Our Vision</h3>
                    <p class="theme-text-muted mb-0">To be the most recognized and beloved luxury cafe brand in India, setting new standards for culinary innovation and aesthetic brilliance.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
