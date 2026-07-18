<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'includes/header.php';
require_once 'includes/sidebar.php';

require_once '../includes/PHPMailer/src/Exception.php';
require_once '../includes/PHPMailer/src/PHPMailer.php';
require_once '../includes/PHPMailer/src/SMTP.php';
require_once '../includes/mail.php';

$message = '';

// Handle Status Update
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $booking_id = $_POST['booking_id'];
    $new_status = $_POST['status'];
    
    try {
        // Fetch booking details first
        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$booking_id]);
        $booking = $stmt->fetch();
        
        // Update status
        $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $booking_id]);
        
        $message = "<div class='alert alert-success'>Booking #$booking_id status updated to $new_status.</div>";
        
        // If confirmed, send email
        if ($new_status === 'Confirmed' && $booking) {
            $to = $booking['email'];
            $subject = "Table Booking Confirmed - Owl Cafe";
            
            $logo_url = $site_origin . $base_url . 'assets/images/logo.jpg';
            
            $html_message = "
            <html>
            <head>
                <style>
                    body { font-family: 'Arial', sans-serif; background-color: #0a0a0a; color: #ffffff; padding: 20px; }
                    .container { max-width: 600px; margin: 0 auto; background-color: #141414; border: 1px solid #d4af37; border-radius: 10px; padding: 30px; text-align: center; }
                    .logo { width: 100px; height: 100px; border-radius: 50%; object-fit: contain; margin-bottom: 20px; }
                    .title { color: #d4af37; font-size: 24px; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 2px; }
                    .details { text-align: left; background: rgba(255,255,255,0.05); padding: 20px; border-radius: 8px; margin-bottom: 20px; }
                    .details p { margin: 10px 0; font-size: 16px; color: #e0e0e0; }
                    .golden { color: #d4af37; font-weight: bold; }
                    .footer { font-size: 12px; color: #888; margin-top: 30px; border-top: 1px solid #333; padding-top: 20px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <img src='{$logo_url}' alt='Owl Cafe Logo' class='logo'>
                    <h1 class='title'>Booking Confirmed!</h1>
                    <p style='font-size: 18px; margin-bottom: 30px;'>Hello <span class='golden'>{$booking['name']}</span>,<br>Your table reservation at Owl Cafe has been successfully confirmed.</p>
                    
                    <div class='details'>
                        <p><span class='golden'>Event Type:</span> {$booking['booking_type']}</p>
                        <p><span class='golden'>Date:</span> " . date('d M Y', strtotime($booking['booking_date'])) . "</p>
                        <p><span class='golden'>Time:</span> " . date('h:i A', strtotime($booking['booking_time'])) . "</p>
                        <p><span class='golden'>Guests:</span> {$booking['guests']} Person(s)</p>
                        <p><span class='golden'>Special Request:</span> " . ($booking['special_request'] ?: 'None') . "</p>
                    </div>
                    
                    <p>We look forward to serving you an unforgettable premium experience.</p>
                    
                    <div class='footer'>
                        &copy; " . date('Y') . " Owl Cafe. All rights reserved.<br>
                        This is an automated confirmation email.
                    </div>
                </div>
            </body>
            </html>
            ";
            $mail = new PHPMailer(true);
            try {
                if (!configureMailerFromEnv($mail)) {
                    throw new Exception('Mail is not configured.');
                }

                // Recipients
                $mail->addAddress($to, $booking['name']);

                // Content
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $html_message;

                $mail->send();
                $message .= "<div class='alert alert-success mt-2'>Confirmation email sent to {$to}.</div>";
            } catch (Exception $e) {
                $errorMessage = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
                $message .= "<div class='alert alert-warning mt-2'>Status updated, but email is not configured or failed to send. Error: {$errorMessage}</div>";
            }
        }
        
    } catch(PDOException $e) {
        $message = "<div class='alert alert-danger'>Failed to update booking status.</div>";
    }
}

// Fetch all bookings
try {
    $stmt = $pdo->query("SELECT * FROM bookings ORDER BY booking_date DESC, booking_time DESC");
    $bookings = $stmt->fetchAll();
} catch(PDOException $e) {
    $bookings = [];
}
?>

<main id="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 theme-text">Table Bookings</h2>
    </div>

    <?= $message ?>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Date & Time</th>
                        <th>Details</th>
                        <th>Request</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($bookings)): ?>
                    <tr><td colspan="8" class="text-center py-4 theme-text-muted">No bookings found.</td></tr>
                    <?php else: foreach($bookings as $book): ?>
                    <tr>
                        <td class="fw-bold">#<?= $book['id'] ?></td>
                        <td><?= htmlspecialchars($book['name']) ?></td>
                        <td>
                            <div><?= htmlspecialchars($book['phone']) ?></div>
                            <small class="theme-text-muted"><?= htmlspecialchars($book['email']) ?></small>
                        </td>
                        <td>
                            <div class="text-golden fw-bold"><?= date('d M Y', strtotime($book['booking_date'])) ?></div>
                            <small><?= date('h:i A', strtotime($book['booking_time'])) ?></small>
                        </td>
                        <td>
                            <span class="badge bg-secondary mb-1"><?= htmlspecialchars($book['booking_type'] ?? 'Regular Dining') ?></span><br>
                            <?= $book['guests'] ?> Person(s)
                        </td>
                        <td><small><?= htmlspecialchars($book['special_request'] ?: 'None') ?></small></td>
                        <td>
                            <?php 
                            $badge = 'bg-warning text-dark';
                            if($book['status'] == 'Confirmed') $badge = 'bg-success';
                            if($book['status'] == 'Cancelled') $badge = 'bg-danger';
                            ?>
                            <span class="badge <?= $badge ?>"><?= $book['status'] ?></span>
                        </td>
                        <td>
                            <form action="" method="POST" class="d-flex gap-2">
                                <input type="hidden" name="booking_id" value="<?= $book['id'] ?>">
                                <select name="status" class="form-select form-select-sm bg-transparent theme-border theme-text" style="width: 120px;">
                                    <option class="text-dark" value="Pending" <?= $book['status']=='Pending'?'selected':'' ?>>Pending</option>
                                    <option class="text-dark" value="Confirmed" <?= $book['status']=='Confirmed'?'selected':'' ?>>Confirmed</option>
                                    <option class="text-dark" value="Cancelled" <?= $book['status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-sm btn-outline-golden">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
