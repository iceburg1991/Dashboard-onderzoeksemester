<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hugozonderland
 * Date: 08-05-13
 * Time: 17:30
 * To change this template use File | Settings | File Templates.
 */

// Setup Google Client => authentication
$gClient = new Google_Client(); // GoogleClient init
$gClient->setAccessType(ACCESS_TYPE); // default: offline
$gClient->setApplicationName(APPLICATION_NAME); // Title
$gClient->setClientId(CLIENT_ID); // ClientId
$gClient->setClientSecret(CLIENT_SECRET); // ClientSecret
$gClient->setRedirectUri(REDIRECT_URI); // Where to redirect to after authentication
$gClient->setDeveloperKey(DEVELOPER_KEY); // Developer key

// $service implements the client interface, has to be set before auth cal
$service = new Google_AnalyticsService($gClient);

// All Google Client  GET parameters
if (isset($_GET['logout'])) { // logout: destroy token
    unset($_SESSION['token']);
    header("Location: index.php");
}

if (isset($_GET['code'])) { // we received the positive auth callback, get the token and store it in session
    $gClient->authenticate();
    $_SESSION['token'] = $gClient->getAccessToken();
}

if (isset($_SESSION['token'])) { // extract token from session and configure client
    $token = $_SESSION['token'];
    $gClient->setAccessToken($token);
}

if (!$gClient->getAccessToken()) { // auth call to google
    $authUrl = $gClient->createAuthUrl();
    header("Location: " . $authUrl);
    die;
}
?>