<?php

session_start();
include("../../config/google_config.php");
$loginUrl=$client->createAuthUrl();
header('Location: '.$loginUrl);
exit();

?>