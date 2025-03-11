<?php
session_start();
include('../../config/db.php');
include '../../config/google_config.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "aine123";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: login.php");
        exit();
    }

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $search_user = $stmt->get_result();

    if ($search_user->num_rows == 0) {
        $_SESSION['error'] = "Invalid Email. Please Register.";
        header("Location: login.php");
        exit();
    }

    // Fetch user data correctly
    $user = $search_user->fetch_assoc();
    $db_password = $user['password']; // Hashed password from DB

    // Verify the password
    if (password_verify($password, $db_password)) {
        // Store session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];

        // Generate JWT token
        $payload = [
            "user_id" => $_SESSION['user_id'],
            "username" => $_SESSION['username'],
            "email" => $_SESSION['email'],
            "exp" => time() + (60 * 30), // Token expires in 30 minutes
        ];

        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        // Store JWT in a secure HTTP-only cookie
        setcookie("jwt", $jwt, time() + (60 * 30), "/", "", false, true);

        $_SESSION['success'] = "Login successful! Redirecting to dashboard...";

        // Redirect to dashboard
        header("Location: ../dashboard/dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid Password.";
        header("Location: login.php");
        
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lostify - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center flex flex-col items-center">
            <h1 class="text-2xl font-bold ml-2" style="color:#102b48;">Lostify</h1>
        </div>

        <form action="login.php" method="post" class="bg-white rounded-lg shadow-sm p-8 space-y-6">
            <!-- Error/Success Messages -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg text-sm">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
                <script>
                    setTimeout(function() {
                        window.location.href = "../dashboard/dashboard.php";
                    }, 2000);
                </script>
            <?php endif; ?>

            <!-- Email Field -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" class="w-full px-4 py-3 focus:outline-none rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 transition-colors text-gray-900" placeholder="name@example.com" required />
            </div>

            <!-- Password Field -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 focus:outline-none rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 transition-colors text-gray-900" placeholder="Enter your password" required />
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3 px-4 rounded-lg text-white font-medium transition-colors" style="background-color: #102b48;">
                Login
            </button>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Or continue with</span>
                </div>
            </div>

            <!-- Google Sign Up -->
            <a href="google_login.php" class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-5 w-5 mr-2" alt="Google Logo">
                Sign up with Google
            </a>

            <!-- Login Link -->
            <p class="text-center text-sm text-gray-600 mt-4">
                Don't have an account?
                <a href="register.php" class="font-medium" style="color:#102b48;">Sign up</a>
            </p>
        </form>
    </div>
</body>
</html>
