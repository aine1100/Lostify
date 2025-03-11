<?php

require_once __DIR__.'/../vendor/autoload.php';

$client=new Google_Client();
$client->setClientId("795597976880-9mgc61o440ctv8ulmbi2uljvf4ufdfft.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost/pages/auth/google-auth/callback.php");
$client->addScope("email");
$client->addScope("profile");
?>