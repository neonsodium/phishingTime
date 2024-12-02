<?php
if (isset($_SERVER['HTTP_CLIENT_IP'])) {
    $ipaddr = $_SERVER['HTTP_CLIENT_IP'];
} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ipaddr = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ipaddr = $_SERVER['REMOTE_ADDR'];
}

if (strpos($ipaddr, ',') !== false) {
    $ipaddr = preg_split("/\,/", $ipaddr)[0];
}

// Collect user-agent and other information
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$referer = $_SERVER['HTTP_REFERER'];
$connectionType = $_SERVER['HTTP_CONNECTION'];
$encoding = $_SERVER['HTTP_ACCEPT_ENCODING'];

// Additional Fingerprinting Techniques
$webglSupported = (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ? "Supported" : "Not Supported";
$webrtcEnabled = (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] == 'https://example.com') ? "Enabled" : "Disabled";
$cookiesEnabled = (isset($_COOKIE)) ? "Enabled" : "Disabled";
$screenResolution = $_SERVER['HTTP_X_SCREEN_RESOLUTION'];
$indexedDBSupported = (class_exists('SQLite3')) ? "Supported" : "Not Supported";

// Capture Date and Time
$timestamp = date("Y-m-d H:i:s");

// Capture Page URL
$currentURL = $_SERVER['REQUEST_URI'];

// Capture Session ID
$sessionID = session_id();

// Capture Browser Information
$browser = get_browser(null, true);

// Capture User's Device Information
$deviceType = (strpos($userAgent, 'Mobile') !== false) ? "Mobile" : "Desktop";
$os = php_uname('s');
$screenSize = $_SERVER['HTTP_SCREEN_SIZE'];

$fp = fopen('ip.txt', 'a');
fwrite($fp, "IP: " . $ipaddr . "\r\n");
fwrite($fp, "User-Agent: " . $userAgent . "\r\n");
fwrite($fp, "Accept-Language: " . $acceptLanguage . "\r\n");
fwrite($fp, "Referer: " . $referer . "\r\n");
fwrite($fp, "Connection Type: " . $connectionType . "\r\n");
fwrite($fp, "Encoding: " . $encoding . "\r\n");

// Additional Information
fwrite($fp, "WebGL Support: " . $webglSupported . "\r\n");
fwrite($fp, "WebRTC Enabled: " . $webrtcEnabled . "\r\n");
fwrite($fp, "Cookies Enabled: " . $cookiesEnabled . "\r\n");
fwrite($fp, "Screen Resolution: " . $screenResolution . "\r\n");
fwrite($fp, "IndexedDB Support: " . $indexedDBSupported . "\r\n");

// Date and Time
fwrite($fp, "Date and Time: " . $timestamp . "\r\n");

// Page URL
fwrite($fp, "Page Visited: " . $currentURL . "\r\n");

// Session ID
fwrite($fp, "Session ID: " . $sessionID . "\r\n");

// Browser Information
fwrite($fp, "Browser Version: " . $browser['version'] . "\r\n");
fwrite($fp, "Browser Features: " . implode(', ', $browser['features']) . "\r\n");

// User's Device Information
fwrite($fp, "Device Type: " . $deviceType . "\r\n");
fwrite($fp, "Operating System: " . $os . "\r\n");
fwrite($fp, "Screen Size: " . $screenSize . "\r\n");

fwrite($fp, "\n\n");
fclose($fp);
?>

