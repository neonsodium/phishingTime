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

$fp = fopen('ip.txt', 'a');
fwrite($fp, "IP: " . $ipaddr . "\r\n");
fwrite($fp, "User-Agent: " . $userAgent . "\r\n");
fwrite($fp, "Accept-Language: " . $acceptLanguage . "\r\n");
fwrite($fp, "Referer: " . $referer . "\r\n");
fwrite($fp, "Connection Type: " . $connectionType . "\r\n");
fwrite($fp, "Encoding: " . $encoding . "\r\n");
// Add more information as needed

// Battery, health, and screen size cannot be obtained using PHP alone without explicit user permission

fwrite($fp, "\n\n");
fclose($fp);
?>

