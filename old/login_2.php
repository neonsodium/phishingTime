<?php
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

            // Define the file path for storing data
            $filePath = "passwd.txt";

            // Append the content to the file
            if (file_put_contents($filePath, $content . "\n\n", FILE_APPEND | LOCK_EX) !== false) {
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

