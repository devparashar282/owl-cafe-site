<?php 
require_once 'includes/db.php';
require_once 'includes/header.php'; 
?>

<!-- Page Header -->
<section class="section-padding theme-bg-sec" style="padding-top: 150px; padding-bottom: 50px;">
    <div class="container text-center" data-aos="fade-up">
        <h1 class="theme-text glow-text display-1" style="font-family: 'Playfair Display', serif;">Our Gallery</h1>
        <p class="theme-text-muted max-w-2xl mx-auto fs-5">Explore the luxurious ambiance, premium food, and memorable moments at Owl Cafe.</p>
    </div>
</section>

<!-- Gallery Section -->
<section class="section-padding pt-0" id="gallery">
    <div class="container">
        <!-- Masonry Grid -->
        <div class="row g-4 masonry-grid" data-aos="fade-up" data-aos-delay="200">
            <!-- Simulated Gallery Items -->
            <?php
            // Fetch gallery items from the database
            $gallery_items = $pdo->query("SELECT * FROM gallery ORDER BY id DESC")->fetchAll();
            
            
            foreach($gallery_items as $index => $item):
            ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="glass-card p-2 h-100 position-relative overflow-hidden group gallery-item card-hover-lift" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#lightboxModal" data-img="<?= $base_url ?>assets/images/<?= $item['image'] ?>" data-title="<?= $item['category'] ?>">
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-0 group-hover-opacity-50 transition-all z-1 d-flex align-items-center justify-content-center" style="opacity: 0; transition: 0.3s;" onmouseover="this.style.opacity='0.6'" onmouseout="this.style.opacity='0'">
                        <i class="fas fa-search-plus text-golden fs-1"></i>
                    </div>
                    <div class="overflow-hidden rounded w-100 h-100">
                        <img src="<?= $base_url ?>assets/images/<?= $item['image'] ?>" class="img-fluid w-100 h-100 object-fit-cover hover-zoom" alt="<?= $item['category'] ?>" style="min-height: 250px; transition: transform 0.5s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    </div>
                    <div class="position-absolute bottom-0 start-0 m-3 z-2">
                        <span class="badge bg-golden text-black py-1 px-2 fw-bold text-uppercase" style="letter-spacing: 1px;"><?= $item['category'] ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0" style="background: rgba(10, 10, 10, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(212, 175, 55, 0.2) !important;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-golden" id="lightboxTitle" style="font-family: 'Playfair Display', serif; font-size: 1.5rem;">Image</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-2">
                <img src="" id="lightboxImg" class="img-fluid rounded" alt="Gallery Image">
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const galleryItems = document.querySelectorAll('.gallery-item');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxTitle = document.getElementById('lightboxTitle');

    galleryItems.forEach(item => {
        item.addEventListener('click', () => {
            const imgSrc = item.getAttribute('data-img');
            const title = item.getAttribute('data-title');
            lightboxImg.src = imgSrc;
            lightboxTitle.innerText = title;
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
