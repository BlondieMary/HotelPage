<?php
require_once __DIR__.'\..\..\boot\boot.php';

use Hotel\user;

if(strtolower($_SERVER['REQUEST_METHOD']) != 'post')
{
  header('Location : /');
  return;
}

// Add a new user based on Request's data
$user = new User();
$user->insert($_REQUEST['name'], $_REQUEST['email'], $_REQUEST['password']);

// Retrieve new User and generate user's token
$userInfo = $user->getByEmail($_REQUEST['email']);
$token = $user->generateToken($userInfo['user_id']);

// Set user's cookie with the generated token to keep him logged in after registration
setcookie('user_token', $token, time() + (30*24*60*60), '/' );

// Return to Home Page
header(sprintf('Location: /public/index.php'));