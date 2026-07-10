<?php 
require_once 'includes/db.php';
require_once 'includes/header.php'; 
?>

<!-- Page Header -->
<section class="section-padding theme-bg-sec" style="padding-top: 150px; padding-bottom: 50px;">
    <div class="container text-center" data-aos="fade-up">
        <h1 class="theme-text glow-text display-1" style="font-family: 'Playfair Display', serif;">Contact Us</h1>
        <p class="theme-text-muted max-w-2xl mx-auto fs-5">We would love to hear from you. Get in touch for any inquiries or feedback.</p>
    </div>
</section>

<!-- Contact Section -->
<section class="section-padding">
    <div class="container">
        <div class="row gy-5">
            <!-- Contact Info -->
            <div class="col-lg-5" data-aos="fade-right">
                <div class="glass-card h-100 p-4 p-md-5 card-hover-lift">
                    <h3 class="mb-4 text-golden" style="font-family: 'Playfair Display', serif;">Get In Touch</h3>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="btn-icon bg-golden text-black me-3"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <h5 class="mb-1">Our Location</h5>
                            <p class="theme-text-muted mb-0">Owl Cafe, Gomti Nagar, Lucknow, Uttar Pradesh, India</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="btn-icon bg-golden text-black me-3"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <h5 class="mb-1">Phone Number</h5>
                            <p class="theme-text-muted mb-0">9987043742</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-5">
                        <div class="btn-icon bg-golden text-black me-3"><i class="fas fa-envelope"></i></div>
                        <div>
                            <h5 class="mb-1">Email Address</h5>
                            <p class="theme-text-muted mb-0">info@owlcafe.com</p>
                        </div>
                    </div>
                    
                    <h4 class="mb-3 text-golden" style="font-family: 'Playfair Display', serif;">Working Hours</h4>
                    <ul class="list-unstyled theme-text-muted">
                        <li class="d-flex justify-content-between mb-2"><span>Monday - Friday</span> <span>10:00 AM - 11:00 PM</span></li>
                        <li class="d-flex justify-content-between mb-2"><span>Saturday - Sunday</span> <span>09:00 AM - 12:00 AM</span></li>
                    </ul>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="col-lg-7" data-aos="fade-left">
                <div class="glass-card p-4 p-md-5 card-hover-lift">
                    <h3 class="mb-4 theme-text" style="font-family: 'Playfair Display', serif;">Send Us A Message</h3>
                    <form action="#" method="POST">
                        <div class="row gy-4">
                            <div class="col-md-6">
                                <label class="form-label text-golden fw-bold">Your Name</label>
                                <input type="text" class="form-control bg-transparent theme-border theme-text py-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-golden fw-bold">Email Address</label>
                                <input type="email" class="form-control bg-transparent theme-border theme-text py-3" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-golden fw-bold">Subject</label>
                                <input type="text" class="form-control bg-transparent theme-border theme-text py-3" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-golden fw-bold">Message</label>
                                <textarea class="form-control bg-transparent theme-border theme-text py-3" rows="5" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-premium px-5 py-3 w-100">Send Message <i class="fas fa-paper-plane ms-2"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Google Map -->
        <div class="row mt-5" data-aos="fade-up">
            <div class="col-12">
                <div class="glass-card p-2">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d113911.23351910247!2d80.9329037!3d26.8486955!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x399bfd9bf0465249%3A0x67303f8a467e42d7!2sGomti%20Nagar%2C%20Lucknow%2C%20Uttar%20Pradesh!5e0!3m2!1sen!2sin!4v1680000000000!5m2!1sen!2sin" width="100%" height="400" style="border:0; border-radius: 10px; filter: invert(90%) hue-rotate(180deg) brightness(85%) contrast(110%);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
