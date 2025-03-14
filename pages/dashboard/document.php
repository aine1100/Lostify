<?php
session_start();
require '../../vendor/autoload.php';
require '../../config/db.php';
require './found_document.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "aine123";

// Check for JWT token
if (!isset($_COOKIE['jwt'])) {
    header("Location: login.php");
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
    header("Location: login.php?error=invalid_token");
    exit();
}

// Add this function to fetch documents from database
function fetchAllDocuments($user_id, $conn)
{
    $documents = [];

    // Fetch lost documents
    $stmt = $conn->prepare("SELECT *, 'lost' AS document_type FROM lost_documents WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $documents[] = $row;
    }
    $stmt->close();

    // Fetch found documents
    $stmt = $conn->prepare("SELECT *, 'found' AS document_type FROM found_documents WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $documents[] = $row;
    }
    $stmt->close();

    // Sort documents by created_at date
    usort($documents, function ($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    return $documents;
}

// Fetch user's documents from both tables
$documents = fetchAllDocuments($_SESSION['user_id'], $conn);

// Handle document upload
if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
        $stmt = $conn->prepare("INSERT INTO lost_documents (user_id, document_type, category, province, district, sector, 
                              incident_date, incident_time, specific_location, description, document_image, created_at) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  NOW())");

        $stmt->bind_param(
            "issssssssss",
            $user_id,
            $document_type,
            $category,
            $province,
            $district,
            $sector,
            $incident_date,
            $incident_time,
            $specific_location,
            $description,
            $db_file_path
        );

        if ($stmt->execute()) {
            // $socket = fsockopen("localhost", 8080);
            // fwrite($socket, json_encode(["user_id" => $user_id, "message" => "New Document is Reported"]));
            // fclose($socket);
            // echo json_encode(["success" => "report submitted successfully"]);
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

// Function to fetch locations

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Lostify - My Documents</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .modal-content {
            max-height: 90vh;
            overflow-y: auto;
        }

        .fixed {
            transition: opacity 0.2s ease-in-out;
        }

        .fixed.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .fixed:not(.hidden) {
            opacity: 1;
        }

        /* Prevent background scroll when modal is open */
        body.modal-open {
            overflow: hidden;
        }

        .active-tab {
            background-color: #102b48; /* Change to your desired active color */
            color: white;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <script>const socket = new WebSocket("ws://localhost:8080");

            socket.onmessage = function (event) {
                const data = JSON.parse(event.data);
                alert("🔔 " + data.message);
            };</script>
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col">
            <div class="flex flex-col flex-grow pt-5 px-3 bg-white overflow-y-auto shadow-lg">
                <div class="flex items-center flex-shrink-0 px-4">
                    <h1 class="text-2xl font-bold" style="color:#102b48;">Lostify</h1>
                </div>
                <div class="mt-8 flex-grow flex flex-col">
                    <nav class="flex-1 px-2 space-y-1">
                        <a href="dashboard.php"
                            class="text-gray-600 group flex items-center gap-5 px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-home h-4 w-4"></i>
                            Home
                        </a>
                        <a href="document.php" style="color:#102b48;"
                            class="bg-blue-50 gap-5 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-folder h-4 w-4"></i>
                            My Documents
                        </a>
                        <a href="alldocuments.php"
                            class="text-gray-600 gap-5 hover:bg-gray-50 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-bell h-4 w-4"></i>
                            All Documents
                        </a>
                      
                        <a href="profile.php"
                            class="text-gray-600 gap-4 hover:bg-gray-50 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-user h-4 w-4"></i>
                            Profile
                        </a>
                    </nav>
                    <div class="p-4">
                        <a href="logout.php" class="text-xs font-medium text-red-500 hover:text-red-700">Sign out</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <div class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between h-16 px-4">
                    <button class="md:hidden text-gray-500" onclick="toggleSidebar()">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>
                    <div class="flex items-center">
                        <div class="relative">
                            <input type="text" placeholder="Search documents..."
                                class="w-64 pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-bell fa-lg"></i>
                        </button>
                        <button onclick="toggleChatbot()" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-comment-dots fa-lg"></i>
                        </button>
                        <div class="flex-shrink-0 flex items-center gap-5">
                            <div class="flex-shrink-0">
                                <img class="h-8 w-8 rounded-full"
                                    src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['username']); ?>"
                                    alt="">
                            </div>
                            <div class="">
                                <p class="text-sm font-medium text-gray-700">
                                    <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">My Documents</h2>
                        <p class="text-gray-600">Manage your lost and found documents</p>
                    </div>
                    <div class="space-x-4">
                        <button type="button" onclick="openModal('lostModal')" style="background-color: #102b48;"
                            class="hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-exclamation-circle mr-2"></i>Report Lost
                        </button>
                        <button type="button" onclick="openModal('foundModal')"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-hand-holding-heart mr-2"></i>Report Found
                        </button>
                    </div>
                </div>

                <!-- Filters -->
                <div class="mb-6 flex gap-4">
                    <button class="filter-button bg-blue-50 text-blue-600 px-4 py-2 rounded-lg text-sm font-medium" data-filter="all">
                        All Documents
                    </button>
                    <button class="filter-button text-gray-600 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium" data-filter="lost">
                        Lost
                    </button>
                    <button class="filter-button text-gray-600 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium" data-filter="found">
                        Found
                    </button>
                </div>

                <!-- Documents Grid -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Documents</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php if (empty($documents)): ?>
                                <div class="text-center py-12">
                                    <div class="text-gray-400 mb-4">
                                        <i class="fas fa-folder-open fa-3x"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900">No documents yet</h3>
                                    <p class="text-gray-500 mt-1">Start by reporting a lost or found document</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($documents as $document): ?>
                                    <div class="flex items-center p-4 bg-gray-50 rounded-lg" data-type="<?php echo $document['document_type']; ?>">
                                        <div class="flex-shrink-0">
                                            <img src="../../<?php echo htmlspecialchars($document['document_image']); ?>"
                                                alt="Document" class="h-16 w-16 object-cover rounded">
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($document['category']); ?>
                                            </h4>
                                            <p class="text-sm text-gray-500">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                <?php echo htmlspecialchars($document['specific_location']); ?> •
                                                <i class="far fa-clock mr-1"></i>
                                                <?php echo date('j M Y', strtotime($document['incident_date'])); ?>
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <?php echo htmlspecialchars($document['province']); ?>,
                                                <?php echo htmlspecialchars($document['district']); ?>,
                                                <?php echo htmlspecialchars($document['sector']); ?>
                                            </p>
                                        </div>
                                        <div class="ml-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                       <?php echo $document['document_type'] === 'found' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                                <?php echo ucfirst($document['document_type']); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Lost Document Modal -->
        <div id="lostModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="modal-content bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="flex justify-between items-center p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Report Lost Document</h3>
                        <button type="button" onclick="closeModal('lostModal')"
                            class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form action="document.php" method="POST" enctype="multipart/form-data" class="p-6">
                        <input type="hidden" name="document_type" value="lost">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Document Category</label>
                                <select name="category" required
                                    class="mt-1 focus:outline-none p-2 block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select category</option>
                                    <option value="id_card">ID Card</option>
                                    <option value="passport">Passport</option>
                                    <option value="drivers_license">Driver's License</option>
                                    <option value="student_card">Student Card</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Province</label>
                                    <select name="province" id="province" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none p-2 focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Province</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">District</label>
                                    <select name="district" id="district" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none p-2 focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select District</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Sector</label>
                                    <select name="sector" id="sector" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none p-2 focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Sector</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date</label>
                                <input type="date" name="incident_date" required max="<?php echo date('Y-m-d'); ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none p-2 focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Approximate Time</label>
                                <input type="time" name="incident_time" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none p-2 focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Specific Location</label>
                                <input type="text" name="specific_location" required
                                    placeholder="E.g., Near Kigali Public Library"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none p-2 focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" rows="3" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none p-2 focus:ring-2 focus:ring-blue-500"
                                    placeholder="Provide detailed description of the document..."></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Upload Image</label>
                                <div
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <i class="fas fa-image text-gray-400 text-3xl"></i>
                                        <div class="flex text-sm text-gray-600">
                                            <label
                                                class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                <span>Upload a file</span>
                                                <input type="file" name="document_image" class="sr-only"
                                                    accept="image/*" required>
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" onclick="closeModal('lostModal')"
                                class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                                Cancel
                            </button>
                            <button type="submit" style="background-color: #102b48;"
                                class="px-4 py-2 text-sm font-medium text-white rounded-md hover:bg-blue-700">
                                Submit Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Found Document Modal -->
        <div id="foundModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="modal-content bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="flex justify-between items-center p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Report Found Document</h3>
                        <button onclick="closeModal('foundModal')" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form action="found_document.php" method="POST" enctype="multipart/form-data" class="p-6">
                        <input type="hidden" name="document_type" value="found">
                        <div class="space-y-4">
                            <!-- Document Category -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Document Type</label>
                                <select name="category" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none p-2 focus:ring-2 focus:ring-green-500">
                                    <option value="">Select type</option>
                                    <option value="id_card">ID Card</option>
                                    <option value="passport">Passport</option>
                                    <option value="drivers_license">Driver's License</option>
                                    <option value="student_card">Student Card</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <!-- Location Selection -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Province Select -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Province</label>
                                    <select name="province" id="found_province" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none p-2 focus:ring-2 focus:ring-green-500">
                                        <option value="">Select Province</option>
                                    </select>
                                </div>

                                <!-- District Select -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">District</label>
                                    <select name="district" id="found_district" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none p-2 focus:ring-2 focus:ring-green-500">
                                        <option value="">Select District</option>
                                    </select>
                                </div>

                                <!-- Sector Select -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Sector</label>
                                    <select name="sector" id="found_sector" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none p-2 focus:ring-2 focus:ring-green-500">
                                        <option value="">Select Sector</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Date and Time -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Date Found</label>
                                    <input type="date" name="incident_date" required max="<?php echo date('Y-m-d'); ?>"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none p-2 focus:ring-2 focus:ring-green-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Approximate Time</label>
                                    <input type="time" name="incident_time" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none p-2 focus:ring-2 focus:ring-green-500">
                                </div>
                            </div>

                            <!-- Specific Location -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Specific Location</label>
                                <input type="text" name="specific_location" required
                                    placeholder="E.g., Near Kigali Public Library"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:outline-none p-2 focus:ring-2 focus:ring-green-500">
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" rows="3" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:outline-none focus:ring-green-500"
                                    placeholder="Provide detailed description of the found document..."></textarea>
                            </div>

                            <!-- Image Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Upload Image</label>
                                <div
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <i class="fas fa-image text-gray-400 text-3xl"></i>
                                        <div class="flex text-sm text-gray-600">
                                            <label
                                                class="relative cursor-pointer rounded-md font-medium text-green-600 hover:text-green-500">
                                                <span>Upload a file</span>
                                                <input type="file" name="document_image" class="sr-only"
                                                    accept="image/*" required>
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" onclick="closeModal('foundModal')"
                                class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600">
                                Submit Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Chatbot -->
        <div id="chatbot" class="hidden fixed bottom-4 right-4 w-96 bg-white rounded-lg shadow-xl">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold">Lostify Assistant</h3>
                <button onclick="toggleChatbot()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="h-96 overflow-y-auto p-4 space-y-4">
                <!-- Chat messages will go here -->
            </div>
            <div class="p-4 border-t">
                <div class="flex gap-2">
                    <input type="text" placeholder="Type your message..."
                        class="flex-1 border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button style="background-color: #102b48;"
                        class="text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.md\\:flex.md\\:w-64');
            sidebar.classList.toggle('hidden');
        }

        function toggleChatbot() {
            const chatbot = document.getElementById('chatbot');
            chatbot.classList.toggle('hidden');
        }

        // Define modal functions
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Close modal when clicking outside
        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('fixed')) {
                event.target.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });

        // Close modal when pressing ESC key
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.fixed').forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        modal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                    }
                });
            }
        });

        // Prevent modal close when clicking inside modal content
        document.querySelectorAll('.modal-content').forEach(content => {
            content.addEventListener('click', function (event) {
                event.stopPropagation();
            });
        });

        // Debug function to check if everything is working
        function debugModals() {
            console.log('Lost button:', document.getElementById('lostButton'));
            console.log('Found button:', document.getElementById('foundButton'));
            console.log('Lost modal:', document.getElementById('lostModal'));
            console.log('Found modal:', document.getElementById('foundModal'));
        }

        // Call debug function when page loads
        document.addEventListener('DOMContentLoaded', debugModals);

        // Image preview functionality
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    const container = input.closest('div').parentElement;

                    reader.onload = function (e) {
                        const existingPreview = container.querySelector('.preview-image');
                        if (existingPreview) {
                            existingPreview.remove();
                        }

                        const preview = document.createElement('div');
                        preview.className = 'preview-image mt-4';
                        preview.innerHTML = `
                            <img src="${e.target.result}" class="h-32 w-full object-cover rounded-md">
                            <button type="button" onclick="removePreview(this)" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        container.appendChild(preview);
                    }
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        });

        function removePreview(button) {
            const container = button.closest('.preview-image').parentElement;
            const input = container.querySelector('input[type="file"]');
            input.value = '';
            button.closest('.preview-image').remove();
        }

        function viewDocument(documentId) {
            // Implement document detail view functionality
            console.log('Viewing document:', documentId);
        }

        // Define the Rwanda locations data structure
        const rwandaData = {
            'Kigali City': {
                districts: ['Gasabo', 'Kicukiro', 'Nyarugenge'],
                sectors: {
                    'Gasabo': ['Gisozi', 'Kimihurura', 'Kimironko', 'Remera', 'Kacyiru', 'Bumbogo', 'Gatsata', 'Jali', 'Gikomero', 'Jabana', 'Nduba', 'Ndera', 'Rusororo', 'Rutunga'],
                    'Kicukiro': ['Gahanga', 'Gatenga', 'Gikondo', 'Kagarama', 'Kanombe', 'Kicukiro', 'Kigarama', 'Masaka', 'Niboye'],
                    'Nyarugenge': ['Gitega', 'Kanyinya', 'Kigali', 'Kimisagara', 'Mageragere', 'Muhima', 'Nyakabanda', 'Nyamirambo', 'Nyarugenge', 'Rwezamenyo']
                }
            },
            'Eastern Province': {
                districts: ['Bugesera', 'Gatsibo', 'Kayonza', 'Kirehe', 'Ngoma', 'Nyagatare', 'Rwamagana'],
                sectors: {
                    'Bugesera': ['Gashora', 'Juru', 'Kamabuye', 'Ntarama', 'Mayange', 'Musenyi', 'Mwogo', 'Ngeruka', 'Nyamata', 'Rilima', 'Ruhuha', 'Rweru', 'Shyara'],
                    'Gatsibo': ['Gasange', 'Gatsibo', 'Gitoki', 'Kabarore', 'Kageyo', 'Kiramuruzi', 'Kiziguro', 'Muhura', 'Murambi', 'Ngarama', 'Nyagihanga', 'Remera', 'Rugarama'],
                    'Kayonza': ['Gahini', 'Kabare', 'Kabarondo', 'Mukarange', 'Murama', 'Murundi', 'Mwiri', 'Ndego', 'Nyamirama', 'Rukara', 'Ruramira', 'Rwinkwavu'],
                    'Kirehe': ['Gahara', 'Gatore', 'Kigarama', 'Kigina', 'Kirehe', 'Mahama', 'Mpanga', 'Musaza', 'Mushikiri', 'Nasho', 'Nyamugari', 'Nyarubuye'],
                    'Ngoma': ['Gashanda', 'Jarama', 'Karembo', 'Kazo', 'Kibungo', 'Mugesera', 'Murama', 'Mutenderi', 'Remera', 'Rukira', 'Rukumberi', 'Rurenge', 'Sake', 'Zaza'],
                    'Nyagatare': ['Gatunda', 'Karama', 'Katabagemu', 'Kiyombe', 'Matimba', 'Mimuri', 'Mukama', 'Musheri', 'Nyagatare', 'Rukomo', 'Rwempasha', 'Rwimiyaga', 'Tabagwe'],
                    'Rwamagana': ['Fumbwe', 'Gahengeri', 'Gishali', 'Karenge', 'Kigabiro', 'Muhazi', 'Munyaga', 'Munyiginya', 'Musha', 'Muyumbu', 'Mwulire', 'Nyakariro', 'Nzige', 'Rubona']
                }
            },
            'Northern Province': {
                districts: ['Burera', 'Gakenke', 'Gicumbi', 'Musanze', 'Rulindo'],
                sectors: {
                    'Burera': ['Bungwe', 'Butaro', 'Cyanika', 'Cyeru', 'Gahunga', 'Gatebe', 'Gitovu', 'Kagogo', 'Kinoni', 'Kinyababa', 'Kivuye', 'Nemba', 'Rugarama', 'Rugengabari', 'Ruhunde', 'Rusarabuye', 'Rwerere'],
                    'Gakenke': ['Busengo', 'Coko', 'Cyabingo', 'Gakenke', 'Gashenyi', 'Janja', 'Kamubuga', 'Karambo', 'Kivuruga', 'Mataba', 'Minazi', 'Mugunga', 'Muhondo', 'Muyongwe', 'Muzo', 'Nemba', 'Ruli', 'Rusasa', 'Rushashi'],
                    'Gicumbi': ['Bukure', 'Bwisige', 'Byumba', 'Cyumba', 'Giti', 'Kageyo', 'Kaniga', 'Manyagiro', 'Miyove', 'Mukarange', 'Muko', 'Mutete', 'Nyamiyaga', 'Nyankenke', 'Rubaya', 'Rukomo', 'Rushaki', 'Rutare', 'Ruvune', 'Rwamiko', 'Shangasha'],
                    'Musanze': ['Busogo', 'Cyuve', 'Gacaca', 'Gashaki', 'Gataraga', 'Kimonyi', 'Kinigi', 'Muhoza', 'Muko', 'Musanze', 'Nkotsi', 'Nyange', 'Remera', 'Rwaza', 'Shingiro'],
                    'Rulindo': ['Base', 'Burega', 'Bushoki', 'Buyoga', 'Cyinzuzi', 'Cyungo', 'Kinihira', 'Kisaro', 'Masoro', 'Mbogo', 'Murambi', 'Ngoma', 'Ntarabana', 'Rukozo', 'Rusiga', 'Shyorongi', 'Tumba']
                }
            },
            'Southern Province': {
                districts: ['Gisagara', 'Huye', 'Kamonyi', 'Muhanga', 'Nyamagabe', 'Nyanza', 'Nyaruguru', 'Ruhango'],
                sectors: {
                    'Gisagara': ['Gikonko', 'Gishubi', 'Kansi', 'Kibirizi', 'Kigembe', 'Mamba', 'Muganza', 'Mugombwa', 'Mukindo', 'Musha', 'Ndora', 'Save', 'Nyanza'],
                    'Huye': ['Gishamvu', 'Huye', 'Karama', 'Kigoma', 'Kinazi', 'Maraba', 'Mbazi', 'Mukura', 'Ngoma', 'Ruhashya', 'Rusatira', 'Rwaniro', 'Simbi', 'Tumba'],
                    'Kamonyi': ['Gacurabwenge', 'Karama', 'Kayenzi', 'Kayumbu', 'Mugina', 'Musambira', 'Ngamba', 'Nyamiyaga', 'Nyarubaka', 'Rukoma', 'Runda', 'Rugarika'],
                    'Muhanga': ['Cyeza', 'Kabacuzi', 'Kibangu', 'Kiyumba', 'Muhanga', 'Mushishiro', 'Nyabinoni', 'Nyamabuye', 'Nyarusange', 'Rongi', 'Rugendabari', 'Shyogwe'],
                    'Nyamagabe': ['Buruhukiro', 'Cyanika', 'Gatare', 'Kaduha', 'Kamegeri', 'Kibirizi', 'Kibumbwe', 'Kitabi', 'Mbazi', 'Mugano', 'Musange', 'Musebeya', 'Mushubi', 'Nkomane', 'Tare', 'Uwinkingi'],
                    'Nyanza': ['Busasamana', 'Busoro', 'Cyabakamyi', 'Kibirizi', 'Kigoma', 'Mukingo', 'Muyira', 'Ntyazo', 'Nyagisozi', 'Rwabicuma'],
                    'Nyaruguru': ['Busanze', 'Cyahinda', 'Kibeho', 'Kibumbwe', 'Kivu', 'Mata', 'Muganza', 'Munini', 'Ngera', 'Ngoma', 'Nyabimata', 'Nyagisozi', 'Ruheru', 'Ruramba'],
                    'Ruhango': ['Bweramana', 'Byimana', 'Kabagali', 'Kinazi', 'Kinihira', 'Mbuye', 'Mwendo', 'Ntongwe', 'Ruhango']
                }
            },
            'Western Province': {
                districts: ['Karongi', 'Ngororero', 'Nyabihu', 'Nyamasheke', 'Rubavu', 'Rusizi', 'Rutsiro'],
                sectors: {
                    'Karongi': ['Bwishyura', 'Gishyita', 'Gitesi', 'Mubuga', 'Murambi', 'Murundi', 'Mutuntu', 'Rubengera', 'Rugabano', 'Ruganda', 'Rwankuba', 'Twumba'],
                    'Ngororero': ['Bwira', 'Gatumba', 'Hindiro', 'Kabaya', 'Kageyo', 'Kavumu', 'Matyazo', 'Muhanda', 'Muhororo', 'Ndaro', 'Ngororero', 'Nyange', 'Sovu'],
                    'Nyabihu': ['Bigogwe', 'Jenda', 'Jomba', 'Kabatwa', 'Karago', 'Kintobo', 'Mukamira', 'Muringa', 'Rambura', 'Rugera', 'Rurembo', 'Shyira'],
                    'Nyamasheke': ['Bushekeri', 'Bushenge', 'Cyato', 'Gihombo', 'Kagano', 'Kanjongo', 'Karambi', 'Karengera', 'Kirimbi', 'Macuba', 'Mahembe', 'Nyabitekeri', 'Rangiro', 'Ruharambuga', 'Shangi'],
                    'Rubavu': ['Bugeshi', 'Busasamana', 'Cyanzarwe', 'Gisenyi', 'Kanama', 'Kanzenze', 'Mudende', 'Nyakiliba', 'Nyamyumba', 'Nyundo', 'Rubavu', 'Rugerero'],
                    'Rusizi': ['Bugarama', 'Butare', 'Bweyeye', 'Gashonga', 'Giheke', 'Gihundwe', 'Gikundamvura', 'Gitambi', 'Kamembe', 'Muganza', 'Mururu', 'Nkanka', 'Nkombo', 'Nkungu', 'Nyakabuye', 'Nyakarenzo', 'Nzahaha', 'Rwimbogo'],
                    'Rutsiro': ['Boneza', 'Gihango', 'Kigeyo', 'Kivumu', 'Manihira', 'Mukura', 'Murunda', 'Musasa', 'Mushonyi', 'Mushubati', 'Nyabirasi', 'Ruhango', 'Rusebeya']
                }
            }
        };

        // Function to update districts based on selected province
        function updateDistricts() {
            const provinceSelect = document.getElementById('province');
            const districtSelect = document.getElementById('district');
            const sectorSelect = document.getElementById('sector');

            // Clear existing options
            districtSelect.innerHTML = '<option value="">Select District</option>';
            sectorSelect.innerHTML = '<option value="">Select Sector</option>';

            const selectedProvince = provinceSelect.value;

            if (selectedProvince && rwandaData[selectedProvince]) {
                const districts = rwandaData[selectedProvince].districts;

                districts.forEach(district => {
                    const option = new Option(district, district);
                    districtSelect.add(option);
                });
            }
        }

        // Function to update sectors based on selected district
        function updateSectors() {
            const provinceSelect = document.getElementById('province');
            const districtSelect = document.getElementById('district');
            const sectorSelect = document.getElementById('sector');

            // Clear existing options
            sectorSelect.innerHTML = '<option value="">Select Sector</option>';

            const selectedProvince = provinceSelect.value;
            const selectedDistrict = districtSelect.value;

            if (selectedProvince && selectedDistrict &&
                rwandaData[selectedProvince] &&
                rwandaData[selectedProvince].sectors[selectedDistrict]) {

                const sectors = rwandaData[selectedProvince].sectors[selectedDistrict];

                sectors.forEach(sector => {
                    const option = new Option(sector, sector);
                    sectorSelect.add(option);
                });
            }
        }

        // Add event listeners when document is loaded
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize province select
            const provinceSelect = document.getElementById('province');
            provinceSelect.innerHTML = '<option value="">Select Province</option>';

            // Add provinces to select
            Object.keys(rwandaData).forEach(province => {
                const option = new Option(province, province);
                provinceSelect.add(option);
            });

            // Add change event listeners
            provinceSelect.addEventListener('change', updateDistricts);
            document.getElementById('district').addEventListener('change', updateSectors);
        });

        // Update the document ready function to initialize both forms
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize both lost and found province selects
            const provinceSelects = ['province', 'found_province'];
            provinceSelects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    select.innerHTML = '<option value="">Select Province</option>';
                    Object.keys(rwandaData).forEach(province => {
                        const option = new Option(province, province);
                        select.add(option);
                    });
                }
            });

            // Add change event listeners for found document form
            const foundProvinceSelect = document.getElementById('found_province');
            const foundDistrictSelect = document.getElementById('found_district');

            if (foundProvinceSelect) {
                foundProvinceSelect.addEventListener('change', function () {
                    updateFoundDistricts();
                });
            }

            if (foundDistrictSelect) {
                foundDistrictSelect.addEventListener('change', function () {
                    updateFoundSectors();
                });
            }
        });

        // Functions for found document form
        function updateFoundDistricts() {
            const provinceSelect = document.getElementById('found_province');
            const districtSelect = document.getElementById('found_district');
            const sectorSelect = document.getElementById('found_sector');

            // Clear existing options
            districtSelect.innerHTML = '<option value="">Select District</option>';
            sectorSelect.innerHTML = '<option value="">Select Sector</option>';

            const selectedProvince = provinceSelect.value;

            if (selectedProvince && rwandaData[selectedProvince]) {
                const districts = rwandaData[selectedProvince].districts;

                districts.forEach(district => {
                    const option = new Option(district, district);
                    districtSelect.add(option);
                });
            }
        }

        function updateFoundSectors() {
            const provinceSelect = document.getElementById('found_province');
            const districtSelect = document.getElementById('found_district');
            const sectorSelect = document.getElementById('found_sector');

            // Clear existing options
            sectorSelect.innerHTML = '<option value="">Select Sector</option>';

            const selectedProvince = provinceSelect.value;
            const selectedDistrict = districtSelect.value;

            if (selectedProvince && selectedDistrict &&
                rwandaData[selectedProvince] &&
                rwandaData[selectedProvince].sectors[selectedDistrict]) {

                const sectors = rwandaData[selectedProvince].sectors[selectedDistrict];

                sectors.forEach(sector => {
                    const option = new Option(sector, sector);
                    sectorSelect.add(option);
                });
            }
        }

        function filterDocuments(type) {
            const documents = document.querySelectorAll('.space-y-4 > div[data-type]');
            documents.forEach(doc => {
                if (type === 'all' || doc.getAttribute('data-type') === type) {
                    doc.style.display = 'flex';
                } else {
                    doc.style.display = 'none';
                }
            });

            // Update active tab
            document.querySelectorAll('.filter-button').forEach(button => {
                button.classList.remove('active-tab');
            });
            document.querySelector(`button[data-filter="${type}"]`).classList.add('active-tab');
        }

        // Add event listeners to buttons
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('button[data-filter="all"]').addEventListener('click', function() {
                filterDocuments('all');
            });
            document.querySelector('button[data-filter="lost"]').addEventListener('click', function() {
                filterDocuments('lost');
            });
            document.querySelector('button[data-filter="found"]').addEventListener('click', function() {
                filterDocuments('found');
            });
        });
    </script>
</body>

</html>