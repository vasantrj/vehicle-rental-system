<?php
// Load .env if not already loaded
if (!isset($_ENV['RAZORPAY_KEY_ID'])) {
    $envPath = __DIR__ . '/../.env';
    if (file_exists($envPath)) {
        foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            [$k, $v] = array_pad(explode('=', $line, 2), 2, '');
            $_ENV[trim($k)] = trim($v, " \t\n\r\0\x0B\"'");
        }
    }
}

$keyId     = $_ENV['RAZORPAY_KEY_ID']     ?? '';
$keySecret = $_ENV['RAZORPAY_KEY_SECRET'] ?? '';
