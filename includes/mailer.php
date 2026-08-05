<?php
require_once dirname(__DIR__) . '/php/PHPMailer/src/Exception.php';
require_once dirname(__DIR__) . '/php/PHPMailer/src/PHPMailer.php';
require_once dirname(__DIR__) . '/php/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function getMailer() {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'owlcafeposs@gmail.com';
        $mail->Password   = 'lisd ymjh ybxn utrd';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->setFrom('owlcafeposs@gmail.com', 'Owl Cafe');
        return $mail;
    } catch (Exception $e) {
        return null;
    }
}

function sendAdminOrderNotification($order_id, $order_details) {
    $mail = getMailer();
    if (!$mail) return false;

    try {
        $mail->addAddress('owlcafeposs@gmail.com', 'Admin');
        $mail->isHTML(true);
        $mail->Subject = 'New Order Received! #' . str_pad($order_id, 5, '0', STR_PAD_LEFT);
        
        $body = "<h2>New Order #".str_pad($order_id, 5, '0', STR_PAD_LEFT)."</h2>";
        $body .= "<p><strong>Customer:</strong> " . htmlspecialchars($order_details['name']) . "</p>";
        $body .= "<p><strong>Phone:</strong> " . htmlspecialchars($order_details['phone']) . "</p>";
        $body .= "<p><strong>Email:</strong> " . htmlspecialchars($order_details['email']) . "</p>";
        $body .= "<p><strong>Type:</strong> " . htmlspecialchars($order_details['order_type']) . "</p>";
        $body .= "<p><strong>Address/Table:</strong> " . htmlspecialchars($order_details['address']) . "</p>";
        $body .= "<p><strong>Payment Method:</strong> " . htmlspecialchars($order_details['payment_method']) . "</p>";
        $body .= "<hr><h3>Total: ₹" . number_format($order_details['total'], 2) . "</h3>";
        
        $mail->Body = $body;
        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}

function sendCustomerInvoice($customer_email, $order_id, $order_details) {
    if (empty($customer_email)) return false;
    
    $mail = getMailer();
    if (!$mail) return false;

    try {
        $mail->addAddress($customer_email, $order_details['name']);
        $mail->isHTML(true);
        $mail->Subject = 'Your Order Invoice from Owl Cafe - #' . str_pad($order_id, 5, '0', STR_PAD_LEFT);
        
        $body = "<h2>Thank you for your order, " . htmlspecialchars($order_details['name']) . "!</h2>";
        $body .= "<p>Your order (<strong>#" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . "</strong>) has been successfully placed.</p>";
        $body .= "<p><strong>Order Type:</strong> " . htmlspecialchars($order_details['order_type']) . "</p>";
        $body .= "<p><strong>Payment Method:</strong> " . htmlspecialchars($order_details['payment_method']) . "</p>";
        
        $body .= "<hr>";
        $body .= "<p>Subtotal: ₹" . number_format($order_details['subtotal'], 2) . "</p>";
        if ($order_details['gst'] > 0) {
            $body .= "<p>GST (5%): ₹" . number_format($order_details['gst'], 2) . "</p>";
        }
        if ($order_details['delivery_charge'] > 0) {
            $body .= "<p>Delivery Charge: ₹" . number_format($order_details['delivery_charge'], 2) . "</p>";
        }
        $body .= "<h3>Total Paid/Payable: ₹" . number_format($order_details['total'], 2) . "</h3>";
        $body .= "<hr>";
        $body .= "<p>If you have any questions, please reply to this email or contact us at owlcafeposs@gmail.com.</p>";
        $body .= "<p>Warm Regards,<br><strong>Owl Cafe Team</strong></p>";
        
        $mail->Body = $body;
        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}
?>
