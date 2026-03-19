<?php
/**
 * DriveEase — Razorpay loader (no Composer required)
 * Suppresses PSR-0 deprecation notices from the legacy Requests library.
 */

// Temporarily silence E_DEPRECATED — only affects this file's scope
$prevErrorLevel = error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// Load WP Requests library (required by Razorpay SDK for HTTP calls)
$requestsLib = __DIR__ . '/vendor/rmccue/requests/library/Requests.php';
if (file_exists($requestsLib)) {
    require_once $requestsLib;
    if (class_exists('Requests')) {
        Requests::register_autoloader();
    }
}

// Restore original error reporting level
error_reporting($prevErrorLevel);

// Autoload Razorpay namespace classes from local src/
spl_autoload_register(function ($class) {
    $prefix = 'Razorpay\\Api\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) return;
    $relative = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($prefix)));
    $bases = [
        __DIR__ . '/razorpay/src/',
        __DIR__ . '/vendor/razorpay/razorpay/src/',
    ];
    foreach ($bases as $base) {
        $file = $base . $relative . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
