<?php
// include our OAuth2 Server object
date_default_timezone_set('UTC');
require_once __DIR__ . '/server.php';

header("Content-type: text/html; charset=utf-8");
// 指定允许其他域名访问  
header('Access-Control-Allow-Origin:*');  
// 响应类型  
header('Access-Control-Allow-Methods:*');  
// 响应头设置  
header('Access-Control-Allow-Headers:x-requested-with,content-type');
$con = mysqli_connect('localhost', 'root', 'root');
if (!$con) {
  die('Could not connect: ' . mysqli_error($con));
}
mysqli_select_db($con, "user");
mysqli_set_charset($con, "utf8");


$request = OAuth2\Request::createFromGlobals();

$response = new OAuth2\Response();

 

// validate the authorize request

if (!$server->validateAuthorizeRequest($request, $response)) {

  $response->send();

  die;

}

// display an authorization form

if (empty($_POST)) {

  exit('
<p></p>
<form method="post">
  token: <input type="input"
  name="user_id">
  <input type="submit"
value="submit">
</form>');

}

function user_md5($str, $auth_key = '')
{
    return '' === $str ? '' : md5(sha1($str) . $auth_key);
}

// $username = $_POST['username'];
// $password = $_POST['password'];
$user_id = $_POST['user_id'];
// if ($username != 'admin' && $password != 'admin') {
//   $response->send();
//   die;
// }
// $getUser = "select * from user where username = '" . $username . "'";
// $userList = mysqli_query($con, $getUser);
// $results = array();
// while ($row = mysqli_fetch_assoc($userList)) {
//   $results[] = $row;
// }
// if($results[0]["password"] != user_md5($password)){
//   echo "Please enter the correct account password";
//   echo ('1:'.$results[0]["password"].'.2:'. md5(sha1($password)));
//   $response->send();
//   die;
// }




// print the authorization code if the user has authorized your client

// $is_authorized = ($_POST['authorized'] === 'yes');
$is_authorized = true;

$server->handleAuthorizeRequest($request, $response, $is_authorized,$user_id);

if ($is_authorized) {

  // this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
  $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=') + 5, 40);
  $state = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=') + 52, 40);
  $arr = ['response' => $response, 'code' => $code, 'state' => $state];

  echo (json_encode($arr));

}

$response->send();