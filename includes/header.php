<?php
// Base URL is initialized in includes/db.php for both local and Vercel hosting.
if (!isset($base_url)) {
    $base_url = '/';
}

$cart_count = 0;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OWL CAFE | Premium Luxury Cafe</title>
    <meta name="description" content="Owl Cafe offers a premium luxury coffee and dining experience in Gomti Nagar, Lucknow.">
    <link rel="icon" type="image/jpeg" href="<?= $base_url ?>assets/images/logo.jpg">
    <link rel="apple-touch-icon" href="<?= $base_url ?>assets/images/logo.jpg">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Owl Cafe">
    <link rel="manifest" href="<?= $base_url ?>manifest.json">
    <meta name="theme-color" content="#d4af37">
    
    <!-- Google Fonts: Inter, Roboto, Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= $base_url ?>assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= $base_url ?>assets/css/responsive.css?v=<?= time() ?>">
</head>
<body>


    <!-- Preloader -->
    <div id="preloader">
        <div class="loader position-relative">
            <div class="coffee-steam">
                <span></span><span></span><span></span>
            </div>
            <div class="css-owl mt-4">
                <div class="owl-ears"></div>
                <div class="owl-eyes">
                    <div class="eye"><div class="pupil"></div></div>
                    <div class="eye"><div class="pupil"></div></div>
                </div>
                <div class="owl-beak"></div>
            </div>
            <h3 class="welcome-text text-gradient mt-4">Welcome to OWL CAFE</h3>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top glassmorphism">
        <div class="container">
            <!-- Left: Logo -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= $base_url ?>index.php">
                <img src="<?= $base_url ?>assets/images/logo.jpg" alt="Owl Cafe Logo" style="height: 55px; width: 55px; object-fit: contain;" class="rounded-circle shadow-sm">
                <span class="fs-4 fw-bold text-gradient d-none d-sm-block" style="font-family: 'Playfair Display', serif;">OWL CAFE</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars theme-text fs-4"></i>
            </button>
            
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <!-- Center: Links -->
                <ul class="navbar-nav mx-auto gap-lg-3">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base_url ?>index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base_url ?>about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base_url ?>gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base_url ?>menu.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base_url ?>movie_night.php">Movie Night</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base_url ?>contact.php">Contact</a>
                    </li>
                </ul>
                
                <!-- Right: Actions -->
                <div class="d-flex align-items-center gap-3 nav-actions mt-3 mt-lg-0">
                    <a href="<?= $base_url ?>menu.php" class="btn btn-outline-light d-none d-xl-block" style="border-radius: 30px; padding: 8px 20px;">Order Online</a>
                    <a href="<?= $base_url ?>booking.php" class="btn-premium d-none d-sm-block" style="padding: 10px 20px;">Book Table</a>
                    <button class="btn btn-outline-light installAppBtn" style="border-radius: 30px; padding: 8px 20px;"><i class="fas fa-mobile-alt me-1"></i> Get App</button>
                    
                    <a href="<?= $base_url ?>order.php" class="btn-icon position-relative text-decoration-none me-2" title="Cart">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-badge" style="font-size: 0.65rem;">
                            <?= $cart_count ?? 0 ?>
                        </span>
                    </a>

                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <button class="btn-icon dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end glass-dropdown">
                                <li><a class="dropdown-item" href="<?= $base_url ?>profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="<?= $base_url ?>my_orders.php">My Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= $base_url ?>php/auth.php?action=logout">Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?= $base_url ?>login.php" class="btn-icon" title="Login"><i class="fas fa-user"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
