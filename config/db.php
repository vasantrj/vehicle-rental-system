<?php
// Load .env from project root
if (!isset($_ENV['DB_HOST'])) {
    $envPath = __DIR__ . '/../.env';
    if (file_exists($envPath)) {
        foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            [$k, $v] = array_pad(explode('=', $line, 2), 2, '');
            $_ENV[trim($k)] = trim($v, " \t\n\r\0\x0B\"'");
        }
    }
}

$host = $_ENV['DB_HOST']     ?? 'localhost';
$user = $_ENV['DB_USERNAME']  ?? 'root';
$pass = $_ENV['DB_PASSWORD']  ?? '';
$db   = $_ENV['DB_DATABASE']  ?? 'vehicle_rental';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
