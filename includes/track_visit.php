<?php
/**
 * track_visit.php — Private visitor analytics
 * Include this at the top of every PUBLIC page (not admin, not auth).
 * Requires: $conn (from config/db.php), session_start() already called.
 */

function trackVisit(mysqli $conn, string $page): void {
    // Skip if admin is logged in
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') return;

    // Bot / crawler filter
    $ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
    $botKeywords = ['bot','spider','crawl','slurp','feedfetcher','mediapartners',
                    'googlebot','bingbot','yandex','baidu','duckduck','semrush',
                    'ahrefs','mj12','uptimerobot','pingdom','curl','wget','python',
                    'java','libwww','httpclient','go-http','okhttp','axios'];
    foreach ($botKeywords as $kw) {
        if (str_contains($ua, $kw)) return;
    }

    // De-duplicate: same IP + same page within 30 minutes = skip
    $ip    = substr($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0', 0, 45);
    $page  = substr($page, 0, 255);
    $ipEsc = mysqli_real_escape_string($conn, $ip);
    $pgEsc = mysqli_real_escape_string($conn, $page);

    $dup = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT id FROM visitors
          WHERE ip='$ipEsc' AND page='$pgEsc'
            AND visited_at >= NOW() - INTERVAL 30 MINUTE
          LIMIT 1"));
    if ($dup) return;

    // Insert the visit (no personal data — just IP + page + UA)
    $uaEsc = mysqli_real_escape_string($conn, substr($ua, 0, 500));
    mysqli_query($conn,
        "INSERT INTO visitors (ip, page, user_agent) VALUES ('$ipEsc','$pgEsc','$uaEsc')");
}

// Auto-detect current page name and track immediately
$_currentPage = basename($_SERVER['PHP_SELF'], '.php');
trackVisit($conn, $_currentPage);
