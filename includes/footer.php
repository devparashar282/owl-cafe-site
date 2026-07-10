    <!-- Footer -->
    <footer class="theme-bg-sec pt-5 pb-3 border-top" style="border-color: rgba(212, 175, 55, 0.2) !important;">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <img src="<?= $base_url ?>assets/images/logo.jpg" alt="Owl Cafe Logo" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: contain; border: 2px solid var(--golden);">
                    <h3 class="footer-logo text-gradient mb-3" style="font-family: 'Playfair Display', serif;">OWL CAFE</h3>
                    <p class="theme-text-muted">Experience luxury and taste at Owl Cafe. We serve the finest coffee and exquisite dishes in a premium ambiance.</p>
                    <div class="social-icons mt-4">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <h4 class="footer-title mb-4 text-white">Quick Links</h4>
                    <ul class="footer-links list-unstyled">
                        <li><a href="<?= $base_url ?>index.php">Home</a></li>
                        <li><a href="<?= $base_url ?>menu.php">Menu</a></li>
                        <li><a href="<?= $base_url ?>gallery.php">Gallery</a></li>
                        <li><a href="<?= $base_url ?>about.php">About Us</a></li>
                        <li><a href="<?= $base_url ?>contact.php">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <h4 class="footer-title mb-4 text-white">Opening Hours</h4>
                    <ul class="footer-contact list-unstyled theme-text-muted">
                        <li class="mb-3 d-flex justify-content-between border-bottom pb-2" style="border-color: rgba(255,255,255,0.1) !important;">
                            <span>Mon - Fri:</span> <span class="text-white">10:00 AM - 11:00 PM</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between border-bottom pb-2" style="border-color: rgba(255,255,255,0.1) !important;">
                            <span>Saturday:</span> <span class="text-white">09:00 AM - 11:30 PM</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between">
                            <span>Sunday:</span> <span class="text-white">09:00 AM - 11:30 PM</span>
                        </li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <h4 class="footer-title mb-4 text-white">Newsletter</h4>
                    <p class="theme-text-muted">Subscribe to get special offers and updates.</p>
                    <form class="newsletter-form mt-3" id="newsletterForm">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Your Email" required style="background: rgba(255,255,255,0.1); border: 1px solid rgba(212,175,55,0.3); color: white;">
                            <button class="btn btn-premium" type="submit"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="row mt-5 pt-4 border-top" style="border-color: rgba(212, 175, 55, 0.2) !important;">
                <div class="col-12 text-center theme-text-muted">
                    <p class="mb-0">&copy; <?= date('Y') ?> Owl Cafe. All Rights Reserved. Made with <i class="fas fa-heart text-danger mx-1"></i> by OWL CAFE</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Buttons -->
    <a href="https://wa.me/919987043742" target="_blank" class="floating-btn whatsapp-btn">
        <i class="fab fa-whatsapp"></i>
    </a>
    <a href="tel:+919987043742" class="floating-btn call-btn">
        <i class="fas fa-phone-alt"></i>
    </a>
    
    <a href="#" class="scroll-top-btn" id="scrollTopBtn">
        <i class="fas fa-arrow-up"></i>
    </a>
    <script>
        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/Cafe/sw.js')
                    .then(registration => console.log('SW registered'))
                    .catch(err => console.log('SW registration failed:', err));
            });
        }

        // PWA Install Prompt
        let deferredPrompt;
        
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
        });

        document.addEventListener('click', (e) => {
            if (e.target.closest('.installAppBtn')) {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        deferredPrompt = null;
                    });
                } else {
                    alert("To install the app, please tap your browser's menu (⋮ or Share icon) and select 'Add to Home Screen' or 'Install App'.");
                }
            }
        });
    </script>

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Custom JS -->
    <script src="<?= $base_url ?>assets/js/main.js"></script>
</body>
</html>
