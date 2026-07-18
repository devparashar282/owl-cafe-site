<?php
require_once 'includes/db.php';
require_once 'includes/media.php';

// Fetch the currently active movie night, or the most recent one if multiple active
try {
    $stmt = $pdo->query("SELECT * FROM movie_nights WHERE status = 'Active' ORDER BY movie_date ASC LIMIT 1");
    $active_movie = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $active_movie = null;
}

require_once 'includes/header.php';
?>

<!-- Page Header -->
<section class="section-padding theme-bg-sec" style="padding-top: 150px; padding-bottom: 50px;">
    <div class="container text-center" data-aos="fade-up">
        <h1 class="text-gradient mb-3">Movie Night</h1>
        <p class="theme-text-muted max-w-2xl mx-auto">Join us for an unforgettable cinematic experience with premium coffee and delicious food!</p>
    </div>
</section>

<!-- Movie Content -->
<section class="section-padding" style="min-height: 50vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php if($active_movie): ?>
                    <div class="glass-card p-4 p-md-5" data-aos="fade-up">
                        <div class="row gy-5 align-items-center">
                            <div class="col-md-5 text-center">
                                <img src="<?= media_resolve_src($active_movie['image'], './') ?>" alt="<?= htmlspecialchars($active_movie['title']) ?>" class="img-fluid rounded" style="box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3); border: 2px solid var(--golden); max-height: 500px; object-fit: cover;">
                            </div>
                            <div class="col-md-7">
                                <div class="mb-4">
                                    <span class="badge bg-danger mb-3 px-3 py-2" style="font-size: 0.9rem;"><i class="fas fa-ticket-alt me-1"></i> Now Showing</span>
                                    <h2 class="text-golden display-5 fw-bold mb-3"><?= htmlspecialchars($active_movie['title']) ?></h2>
                                    
                                    <div class="d-flex flex-wrap gap-4 mb-4 mt-4">
                                        <div class="d-flex align-items-center text-light">
                                            <i class="far fa-calendar-alt text-golden fs-4 me-3"></i>
                                            <div>
                                                <h6 class="mb-0 theme-text-muted">Date</h6>
                                                <span class="fs-5"><?= date('l, d F Y', strtotime($active_movie['movie_date'])) ?></span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center text-light">
                                            <i class="far fa-clock text-golden fs-4 me-3"></i>
                                            <div>
                                                <h6 class="mb-0 theme-text-muted">Showtime</h6>
                                                <span class="fs-5"><?= date('h:i A', strtotime($active_movie['movie_time'])) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr class="theme-border my-4">
                                    
                                    <h5 class="theme-text-muted mb-3">About the Night</h5>
                                    <p class="text-light lh-lg" style="font-size: 1.1rem;"><?= nl2br(htmlspecialchars($active_movie['description'])) ?></p>
                                </div>
                                
                                <div class="mt-5">
                                    <a href="booking.php" class="btn btn-premium btn-lg w-100 py-3 rounded-pill fs-5">
                                        <i class="fas fa-calendar-check me-2"></i> Book Your Table Now
                                    </a>
                                    <p class="text-center text-muted small mt-3">* Tables fill up fast on movie nights. Reserve your spot to ensure you don't miss out!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 glass-card" data-aos="fade-up">
                        <i class="fas fa-film fa-4x text-golden mb-4 opacity-50"></i>
                        <h3 class="theme-text mb-3">No Upcoming Movie Nights</h3>
                        <p class="theme-text-muted fs-5">We are currently planning our next blockbuster event.<br>Stay tuned to our social media for updates!</p>
                        <a href="menu.php" class="btn btn-outline-light mt-4">Explore Menu in the meantime</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
