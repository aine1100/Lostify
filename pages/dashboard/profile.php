<?php
session_start();
require '../../vendor/autoload.php';
require '../../config/db.php';

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

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lostify - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col">
            <div class="flex flex-col flex-grow pt-5 px-3 bg-white overflow-y-auto shadow-lg">
                <div class="flex items-center flex-shrink-0 px-4">
                    <h1 class="text-2xl font-bold" style="color:#102b48;">Lostify</h1>
                </div>
                <div class="mt-8 flex-grow flex flex-col">
                    <nav class="flex-1 px-2 space-y-1">
                        <a href="dashboard.php" class="text-gray-600 group flex items-center gap-5 px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-home h-4 w-4"></i>
                            Home
                        </a>
                        <a href="document.php" class="text-gray-600 group flex items-center gap-5 px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-folder h-4 w-4"></i>
                            My Documents
                        </a>
                        <a href="alldocuments.php" class="text-gray-600 group flex items-center gap-5 px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-bell h-4 w-4"></i>
                            All Documents
                        </a>
                       
                        <a href="profile.php" style="color:#102b48;" class="bg-blue-50 gap-4 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
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
                            <input type="text" placeholder="Search documents..." class="w-64 pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
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
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['username']); ?>" alt="">
                            </div>
                            <div class="">
                                <p class="text-sm font-medium text-gray-700"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center space-x-4">
                        <img class="h-24 w-24 rounded-full" src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['username']); ?>" alt="Profile Picture">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($user['username']); ?></h2>
                            <p class="text-gray-600"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-800">Profile Information</h3>
                        <form action="update_profile.php" method="POST" class="mt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['username']); ?>" class="mt-1 block w-full rounded-md border-gray-300 p-2 focus:outline-none shadow-sm focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                           
                           
                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Update Profile</button>
                            </div>
                        </form>
                    </div>
                   
                </div>
            </main>
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
    </script>
</body>
</html>
