<?php
require_once dirname(__DIR__) . '/includes/db.php';
require '../php/PHPMailer/src/Exception.php';
require '../php/PHPMailer/src/PHPMailer.php';
require '../php/PHPMailer/src/SMTP.php';
require_once '../includes/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $guests = (int)$_POST['guests'];
    $booking_type = filter_var($_POST['booking_type'], FILTER_SANITIZE_STRING) ?: 'Regular Dining';
    $date = $_POST['booking_date'];
    $time = $_POST['booking_time'];
    $special = filter_var($_POST['special_request'], FILTER_SANITIZE_STRING);

    try {
        // Insert into DB
        $stmt = $pdo->prepare("INSERT INTO bookings (name, email, phone, guests, booking_type, booking_date, booking_time, special_request, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->execute([$name, $email, $phone, $guests, $booking_type, $date, $time, $special]);

        // Send Email
        $mail = new PHPMailer(true);
        try {
            $mailConfigured = configureMailerFromEnv($mail);
            $mail->addAddress($email, $name);

            $adminBcc = getenv('MAIL_ADMIN_BCC') ?: getenv('MAIL_USERNAME');
            if ($adminBcc) {
                $mail->addBCC($adminBcc, 'Admin');
            }

            $mail->isHTML(true);
            $mail->Subject = 'Table Booking Confirmation - OWL CAFE';
            $mail->Body    = "
            <div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6; max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px; border-radius: 10px;'>
                <h2 style='color: #d4af37; text-align: center;'>Table Reservation</h2>
                <p>Dear <strong>$name</strong>,</p>
                <p>Thank you for booking your table at <strong>OWL CAFE</strong>.</p>
                <p>Your reservation has been received and is currently Pending confirmation. Our team will contact you shortly.</p>
                <div style='background: #f8f5f2; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                    <p><strong>Event Type:</strong> $booking_type</p>
                    <p><strong>Date:</strong> $date</p>
                    <p><strong>Time:</strong> $time</p>
                    <p><strong>Guests:</strong> $guests</p>
                </div>
                <p>We look forward to serving you.</p>
                <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
                <p style='text-align: center; color: #888;'>OWL CAFE<br>Gomti Nagar<br>Phone: 9987043742</p>
            </div>
            ";

            if ($mailConfigured) {
                $mail->send();
                echo json_encode(['status' => 'success', 'message' => 'Booking Successful! Your confirmation email has been sent.']);
            } else {
                echo json_encode(['status' => 'success', 'message' => 'Booking Successful! Email is not configured on this deployment, so only the reservation was saved.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'success', 'message' => 'Booking saved, but email could not be sent.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
}
?>
