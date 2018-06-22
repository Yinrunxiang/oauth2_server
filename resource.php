<?php
// include our OAuth2 Server object

require_once
__DIR__.'/server.php';

 

// Handle a request for an OAuth2.0 Access Token and send the response to the client

if
(!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {

    $server->getResponse()->send();

    die;

}

$token = $server->getAccessTokenData(OAuth2\Request::createFromGlobals());

echo
"User ID associated with this token is {$token['user_id']}";