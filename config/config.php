<?php

function env($key) {
    $filePath = __DIR__ . "/../.env";

    if (!file_exists($filePath)) {
        die("Error: .env file not found!");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false) {
            list($envKey, $envValue) = explode('=', trim($line), 2);

            if (trim($envKey) === $key) {
                return trim($envValue, '"');  // Remove double quotes if present
            }
        }
    }
    return null;
}

?>
