<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../../vendor/autoload.php';
require '../../config/db.php';
// require '../../utils/not_server.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "aine123";

// Check for JWT token
if (!isset($_COOKIE['jwt'])) {
    header("Location: ../auth/login.php");
    exit();
}

$jwt = $_COOKIE['jwt'];

try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    
    // Set session variables from token
    $_SESSION['user_id'] = $decoded->user_id;
    $_SESSION['username'] = $decoded->username;
    $_SESSION['email'] = $decoded->email;

} catch (Exception $e) {
    header("Location: ../auth/login.php?error=invalid_token");
    exit();
}

// Add this function to fetch documents from database
function fetchDocuments($user_id, $conn) {
    $stmt = $conn->prepare("SELECT * FROM found_documents WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $documents = [];
    while ($row = $result->fetch_assoc()) {
        $documents[] = $row;
    }
    $stmt->close();
    return $documents;
}

// Fetch user's documents
$documents = fetchDocuments($_SESSION['user_id'], $conn);

// Handle document upload
if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST['document_type'] === 'found') {
    $user_id = $_SESSION["user_id"];
    $document_type = $_POST['document_type'];
    $category = $_POST['category'];
    $province = $_POST['province'];
    $district = $_POST['district'];
    $sector = $_POST['sector'];
    $incident_date = $_POST['incident_date'];
    $incident_time = $_POST['incident_time'];
    $specific_location = $_POST['specific_location'];
    $description = $_POST['description'];
    // $status = $document_type === 'lost' ? 'lost' : 'found';

    // File upload handling
    $upload_dir = "../../uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = $_FILES["document_image"]["name"];
    $file_tmp = $_FILES["document_image"]["tmp_name"];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Generate unique filename
    $new_file_name = uniqid("doc_") . "." . $file_ext;
    $file_path = $upload_dir . $new_file_name;
    
    if (move_uploaded_file($file_tmp, $file_path)) {
        $db_file_path = "uploads/" . $new_file_name;
        
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO found_documents (user_id, document_type, category, province, district, sector, 
                              incident_date, incident_time, specific_location, description, document_image, created_at) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  NOW())");
        
        $stmt->bind_param("issssssssss", $user_id, $document_type, $category, $province, $district, $sector, 
                         $incident_date, $incident_time, $specific_location, $description, $db_file_path);
        
        if ($stmt->execute()) {
            // $socket=fsockopen("localhost",8080);
            // fwrite($socket,json_encode(["user_id"=>$user_id,"message"=>"New Document is Reported"]));
            // fclose($socket);
            echo json_encode(["success"=>"report submitted successfully"]);
            header("Location: document.php?success=1");
            exit();
        } else {
            header("Location: document.php?error=db_error");
            exit();
        }
      
    } else {
        header("Location: document.php?error=upload_failed");
        exit();
    }
}

?>