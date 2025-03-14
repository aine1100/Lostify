<?php
require_once 'config.php';  // Ensure .env is loaded

try {
    // Retrieve database credentials
    $db_host = env('DB_HOST') ?: '127.0.0.1';
    $db_name = env('DB_DATABASE') ?: 'lostify';
    $db_user = env('DB_USER') ?: 'root';
    $db_pass = env('DB_PASSWORD') ?: '';  
    $db_port = env('DB_PORT') ?: 3306;

  
    // Create MySQL connection
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

    if ($conn->connect_error) {
        throw new Exception("<script>console.log(Database connection failed:)</script>" . $conn->connect_error);
    }

    echo "<script>console.log('Database connection established')</script>";

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
