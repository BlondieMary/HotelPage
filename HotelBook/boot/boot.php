<?php

// error_reporting(E_ERROR);
spl_autoload_register(function($class){
  require_once sprintf(__DIR__.'\..\app\%s.php',$class);
});

use Hotel\user;

$user = new User();

// Check if there is a token in the Yaf_Request_Abstract
if(array_key_exists('user_token', $_COOKIE))
{
  $usertoken = $_COOKIE['user_token'];

  if($usertoken)
  {
    if($user->verifyToken($usertoken))
    {
      $userInfo = $user->getTokenPayload($usertoken);
      User::setCurrentUserId($userInfo['user_id']);
    }
  }
}