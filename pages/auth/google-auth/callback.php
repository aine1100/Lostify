<?php 

// google_callback.php

session_start();
include "../../../config/google_config.php";
echo "hello user";
if (!isset($_GET['code'])) {
    die("Authorization code not received.");
}


if (isset($_GET['code'])) {
    // Exchange the authorization code for an access token
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['google_access_token'] = $token;
    

    // Set the access token to the client
    $client->setAccessToken($token);

    // Retrieve user information
    $google_service = new Google_Service_Oauth2($client);
    $google_user = $google_service->userinfo->get();

    // Save user information in session
    $_SESSION['google_user'] = [
        'id' => $google_user->id,
        'name' => $google_user->name,
        'email' => $google_user->email,
        'picture' => $google_user->picture,
    ];



    echo "<script>alert('information retreived successfully')</script>";

    // Redirect to the registration page for processing
    header("Location: register.php");
    exit();
}


?>