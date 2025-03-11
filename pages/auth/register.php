<?php
session_start();
include('../../config/db.php');
include '../../config/google_config.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "aine123";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($email) || empty($password)) {
        header("Location: register.php?error=fill_all_fields");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $search_user = $stmt->get_result();

    if ($search_user->num_rows > 0) {
        header("Location: register.php?error=email_exists");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;

        $payload = [
            "user_id" => $_SESSION['user_id'],
            "username" => $_SESSION['username'],
            "email" => $_SESSION['email'],
            "exp" => time() + (60 * 30), // Token expires in 30 minutes
        ];
        
        $jwt = JWT::encode($payload, $secret_key, 'HS256');
        
        // Store JWT in a secure HTTP-only cookie
        setcookie("jwt", $jwt, time() + (60 * 30), "/", "", false, true);
        
        // Redirect if using Google Sign-Up
        if (isset($_SESSION['google_access_token'])) {
            header("Location: /../dashboard/dashboard.php");
            exit();
        }

        header("Location: register.php?success=registration_successful");
        exit();
    } else {
        echo "<script>alert('An error occurred')</script>";
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="flex items-center justify-center">
    <form action="register.php" class="bg-white p-10 shadow-lg rounded-lg flex flex-col gap-5 items-center justify-center w-full max-w-xl" method="post">
        <!-- Display error message if any -->
        <?php if (isset($_GET['error'])): ?>
            <?php
                // Set the error message and class
                $error_message = '';
                $error_class = 'bg-red-500';
                
                switch ($_GET['error']) {
                    case 'fill_all_fields':
                        $error_message = 'Please fill in all fields.';
                        break;
                    case 'email_exists':
                        $error_message = 'Email already exists. Please try another one.';
                        break;
                    default:
                        $error_message = 'An unknown error occurred.';
                        $error_class = 'bg-yellow-500';
                        break;
                }
            ?>
            <div class="<?php echo $error_class; ?> text-white p-3 rounded-md w-full text-center mb-5">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Display success message if any -->
        <?php if (isset($_GET['success'])): ?>
            <?php
                $success_message = '';
                $success_class = 'bg-green-500';

                switch ($_GET['success']) {
                    case 'registration_successful':
                        $success_message = 'Registration successful! You can now log in.';
                        break;
                    default:
                        $success_message = 'Success!';
                        break;
                }
            ?>
            <div class="<?php echo $success_class; ?> text-white p-3 rounded-md w-full text-center mb-5">
                <?php echo $success_message; ?>
            </div>

            <script>
                // Redirect to the dashboard after a 3-second delay
                setTimeout(function() {
                    window.location.href = "../dashboard/dashboard.php";
                }, 2000);
            </script>
        <?php endif; ?>

        <h1 class="text-xl font-semibold text-center" style="color:#102b48">
            Welcome to Lostify <br/> 
            <span class="text-md p-4 text-gray-400">Create an account to get started</span>
        </h1>

        <div class="flex flex-col items-start justify-center gap-2 w-full">
            <p>Enter your name</p>   
            <input type="text" 
                name="username"
                class="bg-white bg-opacity-40 w-full rounded-md px-5 py-3 ring-1 focus:ring-2 ring-blue-600 focus:outline-none border-blue-600 text-black" 
                placeholder="Enter your name" 
            />
        </div>

        <div class="flex flex-col items-start justify-center gap-2 w-full">
            <p>Enter your email</p>   
            <input type="email" 
                name="email"
                class="bg-white bg-opacity-40 w-full rounded-md px-5 py-3 ring-1 focus:ring-2 ring-blue-600 focus:outline-none border-blue-600 text-black" 
                placeholder="Enter your email" 
            />
        </div>

        <div class="flex flex-col items-start justify-center gap-2 w-full">
            <p>Enter your password</p>   
            <input type="password" 
                name="password"
                class="bg-white bg-opacity-40 w-full rounded-md px-5 py-3 ring-1 focus:ring-2 ring-blue-600 focus:outline-none border-blue-600 text-black" 
                placeholder="Enter your password" 
            />
        </div>

        <button class="w-full flex items-center text-white justify-center rounded-md px-5 py-3" style="background-color: #102b48;">
            Register
        </button>

        <a href="login.php" class="text-md text-gray-600">
            Already have an account? <span style="color: #102b48;">Login</span>
        </a>
        <a href="google_login.php" class="w-full flex items-center text-white justify-center rounded-md px-5 py-3" style="background-color: yellow">
            Register with Google
        <a/>
    </form>
</body>
