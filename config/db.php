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

// Detect environment
$isLocal = ($_SERVER['SERVER_NAME'] === 'localhost');

// LOCAL ENV
if ($isLocal) {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "vehicle_rental";
} 
// LIVE (InfinityFree)
else {
    $host = "sql301.infinityfree.com";
    $user = "if0_41439231";
    $pass = "zNHi6cAGLnVh";
    $db   = "if0_41439231_vehicle_rental";
}

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
