<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

function sendBookingEmail($toEmail, $userName, $vehicleName, $start, $end, $total)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP SETTINGS
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'avadhutjoshi2580@gmail.com'; // 🔴 CHANGE THIS
        $mail->Password   = 'wcxtyqjvmoliwbek';   // 🔴 CHANGE THIS
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('avadhutjoshi2580@gmail.com', 'Vehicle Rental System');
        $mail->addAddress($toEmail, $userName);

        $mail->isHTML(true);
        $mail->Subject = 'Booking Approved';

        $mail->Body = "
            <h3>Hello $userName,</h3>
            <p>Your booking has been approved.</p>
            <p><strong>Vehicle:</strong> $vehicleName</p>
            <p><strong>From:</strong> $start</p>
            <p><strong>To:</strong> $end</p>
            <p><strong>Total:</strong> ₹$total</p>
            <br>
            <p>Thank you for choosing us!</p>
        ";

        $mail->send();

    } catch (Exception $e) {
        // Silent fail (optional logging)
    }
}