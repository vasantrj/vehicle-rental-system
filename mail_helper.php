<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/SMTP.php';

function loadMailEnv() {
    if (isset($_ENV['_MAIL_ENV_LOADED'])) return;
    $envPath = __DIR__ . '/.env';
    if (!file_exists($envPath)) return;
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || strpos($line,'=') === false) continue;
        [$k, $v] = array_pad(explode('=', $line, 2), 2, '');
        $k = trim($k); $v = trim($v, " \t\n\r\0\x0B\"'");
        $_ENV[$k] = $v; putenv("$k=$v");
    }
    $_ENV['_MAIL_ENV_LOADED'] = true;
}
loadMailEnv();

function sendBookingEmail($toEmail, $userName, $vehicleName, $start, $end, $total)
{
    $smtpHost  = $_ENV['MAIL_HOST']          ?? 'smtp.gmail.com';
    $smtpUser  = $_ENV['MAIL_USERNAME']       ?? '';
    $smtpPass  = $_ENV['MAIL_PASSWORD']       ?? '';
    $smtpPort  = intval($_ENV['MAIL_PORT']    ?? 587);
    $fromName  = $_ENV['MAIL_FROM_NAME']      ?? 'DriveEase';
    $fromAddr  = $_ENV['MAIL_FROM_ADDRESS']   ?? $smtpUser;

    if (empty($smtpUser) || empty($smtpPass)) {
        error_log('[DriveEase Mail] Skipped: MAIL_USERNAME or MAIL_PASSWORD missing in .env');
        return false;
    }

    $days           = (new DateTime($start))->diff(new DateTime($end))->days + 1;
    $totalFormatted = 'Rs. ' . number_format($total);
    $invNo          = 'INV-' . str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
    $year           = date('Y');
    $dateNow        = date('d M Y');
    $nameClean      = htmlspecialchars($userName,    ENT_QUOTES, 'UTF-8');
    $vehicleClean   = htmlspecialchars($vehicleName, ENT_QUOTES, 'UTF-8');

    // ── Plain text version (important for spam filters) ──────────────────
    $plainText = "DRIVEEASE — BOOKING CONFIRMATION\n"
               . "=================================\n\n"
               . "Hi $userName,\n\n"
               . "Your booking has been confirmed and payment was successful.\n\n"
               . "BOOKING DETAILS\n"
               . "---------------\n"
               . "Vehicle     : $vehicleName\n"
               . "Start Date  : $start\n"
               . "End Date    : $end\n"
               . "Duration    : $days day(s)\n"
               . "Total Paid  : $totalFormatted\n\n"
               . "PAYMENT INFO\n"
               . "------------\n"
               . "Status      : Paid\n"
               . "Invoice No  : $invNo\n"
               . "Date        : $dateNow\n\n"
               . "You can download your invoice by logging into your account\n"
               . "and visiting My Bookings.\n\n"
               . "For support: support@driveease.in | +91 98765 43210\n\n"
               . "Thank you for choosing DriveEase!\n"
               . "-- DriveEase Team, Bengaluru";

    // ── HTML version ─────────────────────────────────────────────────────
    $htmlBody = '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Booking Confirmed</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f6f9;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#333333">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f6f9;padding:30px 0">
<tr><td align="center">

  <!-- Card -->
  <table width="600" cellpadding="0" cellspacing="0" border="0"
    style="background:#ffffff;border-radius:8px;overflow:hidden;
           border:1px solid #e2e8f0;max-width:600px;width:100%">

    <!-- Header bar -->
    <tr>
      <td style="background-color:#ea580c;padding:0;height:5px">&nbsp;</td>
    </tr>

    <!-- Logo + title -->
    <tr>
      <td style="padding:32px 40px 24px;border-bottom:1px solid #f1f5f9">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>
              <div style="font-size:22px;font-weight:700;color:#1e293b;letter-spacing:-0.5px">DriveEase</div>
              <div style="font-size:12px;color:#94a3b8;margin-top:2px">Vehicle Rental Service</div>
            </td>
            <td align="right">
              <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:20px;
                          padding:6px 14px;font-size:12px;font-weight:700;color:#15803d;
                          display:inline-block">
                Payment Confirmed
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <!-- Greeting -->
    <tr>
      <td style="padding:28px 40px 0">
        <div style="font-size:18px;font-weight:700;color:#1e293b;margin-bottom:6px">
          Hi ' . $nameClean . ',
        </div>
        <div style="font-size:14px;color:#64748b;line-height:1.6">
          Your booking is confirmed and payment was received successfully.
          Here is your booking summary below.
        </div>
      </td>
    </tr>

    <!-- Booking Details box -->
    <tr>
      <td style="padding:20px 40px 0">
        <table width="100%" cellpadding="0" cellspacing="0" border="0"
          style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;overflow:hidden">

          <!-- Box header -->
          <tr>
            <td colspan="2"
              style="background:#1e293b;padding:10px 18px;
                     font-size:11px;font-weight:700;color:#94a3b8;
                     text-transform:uppercase;letter-spacing:1px">
              Booking Details
            </td>
          </tr>

          <!-- Rows -->
          <tr>
            <td style="padding:11px 18px;font-size:13px;color:#64748b;font-weight:600;
                       border-bottom:1px solid #e2e8f0;width:38%">Vehicle</td>
            <td style="padding:11px 18px;font-size:13px;color:#1e293b;font-weight:700;
                       border-bottom:1px solid #e2e8f0">' . $vehicleClean . '</td>
          </tr>
          <tr>
            <td style="padding:11px 18px;font-size:13px;color:#64748b;font-weight:600;
                       border-bottom:1px solid #e2e8f0">Start Date</td>
            <td style="padding:11px 18px;font-size:13px;color:#1e293b;
                       border-bottom:1px solid #e2e8f0">' . htmlspecialchars($start) . '</td>
          </tr>
          <tr>
            <td style="padding:11px 18px;font-size:13px;color:#64748b;font-weight:600;
                       border-bottom:1px solid #e2e8f0">End Date</td>
            <td style="padding:11px 18px;font-size:13px;color:#1e293b;
                       border-bottom:1px solid #e2e8f0">' . htmlspecialchars($end) . '</td>
          </tr>
          <tr>
            <td style="padding:11px 18px;font-size:13px;color:#64748b;font-weight:600">Duration</td>
            <td style="padding:11px 18px;font-size:13px;color:#0369a1;font-weight:700">' . $days . ' day' . ($days > 1 ? 's' : '') . '</td>
          </tr>
        </table>
      </td>
    </tr>

    <!-- Total Paid -->
    <tr>
      <td style="padding:16px 40px 0">
        <table width="100%" cellpadding="0" cellspacing="0" border="0"
          style="background:#fff7ed;border:1px solid #fed7aa;border-radius:6px">
          <tr>
            <td style="padding:14px 18px;font-size:14px;font-weight:700;color:#1e293b">
              Total Amount Paid
            </td>
            <td align="right"
              style="padding:14px 18px;font-size:20px;font-weight:800;color:#ea580c">
              ' . $totalFormatted . '
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <!-- What next -->
    <tr>
      <td style="padding:20px 40px 0">
        <table width="100%" cellpadding="0" cellspacing="0" border="0"
          style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:6px;padding:14px 18px">
          <tr>
            <td style="padding:14px 18px">
              <div style="font-size:12px;font-weight:700;color:#1d4ed8;
                          text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">
                What to do next
              </div>
              <div style="font-size:13px;color:#475569;line-height:2">
                - Log in to your account and go to <strong>My Bookings</strong> to download your invoice.<br>
                - For any queries, email us at support@driveease.in<br>
                - Contact: +91 98765 43210
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <!-- Footer -->
    <tr>
      <td style="padding:24px 40px 28px;border-top:1px solid #f1f5f9;margin-top:24px">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td style="font-size:12px;color:#94a3b8;line-height:1.6">
              &copy; ' . $year . ' DriveEase. All rights reserved.<br>
              This is an automated email. Please do not reply directly to this message.
            </td>
            <td align="right" style="font-size:11px;color:#cbd5e1;white-space:nowrap">
              Bengaluru, India
            </td>
          </tr>
        </table>
      </td>
    </tr>

  </table>
  <!-- /Card -->

</td></tr>
</table>

</body>
</html>';

    // ── Send ──────────────────────────────────────────────────────────────
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $smtpHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPass;
        $mail->SMTPSecure = ($smtpPort === 465)
                            ? PHPMailer::ENCRYPTION_SMTPS
                            : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $smtpPort;
        $mail->CharSet    = PHPMailer::CHARSET_UTF8;
        $mail->Encoding   = PHPMailer::ENCODING_BASE64;
        $mail->Timeout    = 20;

        $mail->setFrom($fromAddr, $fromName);
        $mail->addAddress($toEmail, $userName);
        $mail->addReplyTo('support@driveease.in', 'DriveEase Support');

        $mail->Subject  = 'Booking Confirmed - DriveEase';   // plain subject, no emoji
        $mail->Body     = $htmlBody;
        $mail->AltBody  = $plainText;                         // plain-text fallback

        $mail->send();
        error_log('[DriveEase Mail] Sent to: ' . $toEmail);
        return true;

    } catch (Exception $e) {
        error_log('[DriveEase Mail] FAILED to ' . $toEmail . ' — ' . $mail->ErrorInfo);
        return false;
    }
}
