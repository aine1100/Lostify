<?php


session_start();
require '../../vendor/autoload.php'; 
require_once '../../config/db.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "aine123";

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
    // Redirect if token is invalid or expired
    header("Location: ../auth/login.php?error=invalid_token");
    exit();
}

// Function to fetch recent documents
function fetchRecentDocuments($user_id, $conn) {
    $documents = [];

    // Fetch lost documents
    $stmt = $conn->prepare("SELECT *, 'lost' AS document_type FROM lost_documents WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $documents[] = $row;
    }
    $stmt->close();

    // Fetch found documents
    $stmt = $conn->prepare("SELECT *, 'found' AS document_type FROM found_documents WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $documents[] = $row;
    }
    $stmt->close();

    return $documents;
}

// Fetch user's recent documents
$recentDocuments = fetchRecentDocuments($_SESSION['user_id'], $conn);

// Fetch total document counts
$totalLostDocuments = 0;
$totalFoundDocuments = 0;

$stmt = $conn->prepare("SELECT COUNT(*) FROM lost_documents WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($totalLostDocuments);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) FROM found_documents WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($totalFoundDocuments);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lostify - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="/../../"></script>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col">
            <div class="flex flex-col flex-grow pt-5 px-3 bg-white overflow-y-auto shadow-lg">
                <div class="flex items-center flex-shrink-0 px-4">
                    <h1 class="text-2xl font-bold " style="color:#102b48 ;">Lostify</h1>
                </div>
                <div class="mt-8 flex-grow flex flex-col">
                    <nav class="flex-1 px-2 space-y-1">
                        <a href="dashboard.php" style="color:#102b48" class="bg-blue-50 group flex items-center gap-5 px-2 py-2 text-sm font-medium rounded-md" id="home-tab">
                            <i class="fas fa-home h-4 w-4"></i>
                            Home
                        </a>
                        <a href="document.php" class="text-gray-600 group flex items-center gap-5 px-2 py-2 text-sm font-medium rounded-md" onclick="navigateTo('document.php')">
                            <i class="fas fa-folder h-4 w-4"></i>
                            My Documents
                        </a>
                        <a href="alldocuments.php" class="text-gray-600 group flex items-center gap-5 px-2 py-2 text-sm font-medium rounded-md" onclick="navigateTo('alldocuments.php')">
                            <i class="fas fa-bell h-4 w-4"></i>
                            All Documents
                        </a>
                        <a href="profile.php" class="text-gray-600 gap-4 hover:bg-gray-50 group flex items-center px-2 py-2 text-sm font-medium rounded-md" onclick="navigateTo('profile.php')">
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
                        <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                    <div class="flex items-center gap-5">
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
        </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div id="home-content" class="space-y-6">
                    <!-- Welcome Section -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-2xl font-bold text-gray-800">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                        <p class="text-gray-600 mt-1">Here's an overview of your lost documents.</p>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-gray-500 text-sm">Total Lost Documents</h3>
                            </div>
                            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo $totalLostDocuments; ?></p>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-gray-500 text-sm">Total Found Documents</h3>
                            </div>
                            <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo $totalFoundDocuments; ?></p>
                        </div>
                    </div>

                    <!-- Recent Documents -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Documents</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <?php if (empty($recentDocuments)): ?>
                                    <div class="text-center py-12">
                                        <div class="text-gray-400 mb-4">
                                            <i class="fas fa-folder-open fa-3x"></i>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900">No recent documents</h3>
                                        <p class="text-gray-500 mt-1">Start by reporting a lost or found document</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($recentDocuments as $document): ?>
                                        <div class="flex items-center p-4 bg-gray-50 rounded-lg" data-type="<?php echo $document['document_type']; ?>">
                                            <div class="flex-shrink-0">
                                                <img src="../../<?php echo htmlspecialchars($document['document_image']); ?>" alt="Document" class="h-16 w-16 object-cover rounded">
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <h4 class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($document['category']); ?></h4>
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
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
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
                </div>
            </main>
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
                    <input type="text" placeholder="Type your message..." class="flex-1 border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    <button class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        <i class="fas fa-paper-plane"></i>
                                </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            // Add mobile sidebar toggle logic
        }

        function toggleChatbot() {
            const chatbot = document.getElementById('chatbot');
            chatbot.classList.toggle('hidden');
        }

        // Tab switching logic
        document.querySelectorAll('nav a').forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                // Remove active class from all tabs
                document.querySelectorAll('nav a').forEach(t => {
                    t.classList.remove('bg-green-50', 'text-green-600');
                    t.classList.add('text-gray-600', 'hover:bg-gray-50');
                });
                // Add active class to clicked tab
                tab.classList.remove('text-gray-600', 'hover:bg-gray-50');
                tab.classList.add('bg-green-50', 'text-green-600');
                // Show corresponding content
                // Add content switching logic here
            });
        });

        function navigateTo(page) {
            window.location.href = page; // Navigate to the specified page
        }
    </script>
</body>
</html>
