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
$userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "N/A";
$acceptLanguage = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : "N/A";
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "N/A";
$connectionType = isset($_SERVER['HTTP_CONNECTION']) ? $_SERVER['HTTP_CONNECTION'] : "N/A";
$encoding = isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : "N/A";

// Additional Fingerprinting Techniques
$webglSupported = (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ? "Supported" : "Not Supported";
$webrtcEnabled = (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] == 'https://example.com') ? "Enabled" : "Disabled";
$cookiesEnabled = (isset($_COOKIE)) ? "Enabled" : "Disabled";
$screenResolution = isset($_SERVER['HTTP_X_SCREEN_RESOLUTION']) ? $_SERVER['HTTP_X_SCREEN_RESOLUTION'] : "N/A";
$indexedDBSupported = (class_exists('SQLite3')) ? "Supported" : "Not Supported";

// Capture Date and Time
$timestamp = date("Y-m-d H:i:s");

// Capture Page URL
$currentURL = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "N/A";

// Capture Session ID
$sessionID = session_id();

// Capture Browser Information
$browser = get_browser(null, true);
$browserVersion = isset($browser['version']) ? $browser['version'] : "N/A";
$browserFeatures = isset($browser['browser_name_pattern']) ? implode(', ', $browser['browser_name_pattern']) : "N/A";

// Capture User's Device Information
$deviceType = (strpos($userAgent, 'Mobile') !== false) ? "Mobile" : "Desktop";
$os = php_uname('s');
$screenSize = isset($_SERVER['HTTP_SCREEN_SIZE']) ? $_SERVER['HTTP_SCREEN_SIZE'] : "N/A";

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
fwrite($fp, "Browser Version: " . $browserVersion . "\r\n");
fwrite($fp, "Browser Features: " . $browserFeatures . "\r\n");

// User's Device Information
fwrite($fp, "Device Type: " . $deviceType . "\r\n");
fwrite($fp, "Operating System: " . $os . "\r\n");
fwrite($fp, "Screen Size: " . $screenSize . "\r\n");

fwrite($fp, "\n\n");
fclose($fp);
?>

