<?php 
require_once __DIR__ . '/includes/db.php';
require_once 'includes/header.php'; 
?>

<section class="section-padding theme-bg-sec" style="padding-top: 150px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="section-title">
                    <span class="subtitle">Reservation</span>
                    <h2>Book Your Table</h2>
                </div>
                
                <div class="glass-card p-4 p-md-5">
                    <div id="booking-alert"></div>
                    
                    <form id="bookingForm" action="php/book_table.php" method="POST">
                        <div class="row gy-4">
                            <div class="col-md-6">
                                <label class="form-label text-golden">Your Name *</label>
                                <input type="text" name="name" class="form-control bg-transparent theme-border theme-text py-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-golden">Phone Number *</label>
                                <input type="tel" name="phone" class="form-control bg-transparent theme-border theme-text py-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-golden">Email Address *</label>
                                <input type="email" name="email" class="form-control bg-transparent theme-border theme-text py-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-golden">Number of Guests *</label>
                                <select name="guests" class="form-select bg-transparent theme-border theme-text py-3" style="background-color: var(--bg-primary);" required>
                                    <option value="1">1 Person</option>
                                    <option value="2">2 People</option>
                                    <option value="3">3 People</option>
                                    <option value="4">4 People</option>
                                    <option value="5">5 People</option>
                                    <option value="6">6+ People</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-golden">Event Type *</label>
                                <select name="booking_type" class="form-select bg-transparent theme-border theme-text py-3" style="background-color: var(--bg-primary);" required>
                                    <option value="Regular Dining">Regular Dining</option>
                                    <option value="Birthday Party">Birthday Party</option>
                                    <option value="Kitty Party">Kitty Party</option>
                                    <option value="Private Party">Private Party</option>
                                    <option value="Meeting">Business Meeting</option>
                                    <option value="Anniversary">Anniversary</option>
                                    <option value="Movie Night">Movie Night Booking</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-golden">Date *</label>
                                <input type="date" name="booking_date" class="form-control bg-transparent theme-border theme-text py-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-golden">Time *</label>
                                <input type="time" name="booking_time" class="form-control bg-transparent theme-border theme-text py-3" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-golden">Special Request (Optional)</label>
                                <textarea name="special_request" class="form-control bg-transparent theme-border theme-text py-3" rows="3" placeholder="E.g., Anniversary decoration, corner table..."></textarea>
                            </div>
                            <div class="col-12 text-center mt-5">
                                <button type="submit" class="btn btn-premium px-5 py-3 w-100" id="btn-book">Confirm Reservation <i class="fas fa-check-circle ms-2"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('bookingForm');
    const alertBox = document.getElementById('booking-alert');
    const btnBook = document.getElementById('btn-book');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const originalText = btnBook.innerHTML;
        btnBook.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        btnBook.disabled = true;
        
        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            
            if(data.status === 'success') {
                alertBox.innerHTML = `<div class="alert alert-success bg-transparent border-success text-success"><i class="fas fa-check-circle me-2"></i> ${data.message}</div>`;
                form.reset();
            } else {
                alertBox.innerHTML = `<div class="alert alert-danger bg-transparent border-danger text-danger"><i class="fas fa-exclamation-circle me-2"></i> ${data.message}</div>`;
            }
        } catch(err) {
            alertBox.innerHTML = `<div class="alert alert-danger bg-transparent border-danger text-danger"><i class="fas fa-exclamation-circle me-2"></i> An error occurred. Please try again later.</div>`;
        } finally {
            btnBook.innerHTML = originalText;
            btnBook.disabled = false;
        }
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
