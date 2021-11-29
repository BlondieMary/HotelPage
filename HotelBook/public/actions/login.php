<?php
require_once __DIR__.'\..\..\boot\boot.php';

use Hotel\user;

if(strtolower($_SERVER['REQUEST_METHOD']) != 'post')
{
  header('Location : /');
  return;
}

if(!empty(User::getCurrentUserId()))
{
  header('Location: \public\index.php');
}

// Verify user by email and password
$user = new User();
$verified = $user->verify($_REQUEST['email'], $_REQUEST['password']);

// Check verification status, generate token and set user's cookie if verified - returns to HOME Page
if($verified == true)
{
  $userInfo = $user->getByEmail($_REQUEST['email']);
  $token = $user->generateToken($userInfo['user_id']);
  $verifiedToken = $user->verifyToken($token);
  
  if($verifiedToken == true)
  {
    setcookie('user_token', $token, time() + (30*24*60*60), '/' );
  }
  else
  {
    setcookie('user_token', null, time() + 0, '/' );
    header('Location: ..\index.php');
  }

  //Return to Home Page
  header(sprintf('Location: ../index.php'));
}
else
{
  header('Location: \public\login.php?error=1');
}