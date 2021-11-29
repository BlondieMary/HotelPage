<?php
require_once __DIR__.'\..\..\boot\boot.php';

use Hotel\user;
if (strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
die;}

$user = new User();
$email = $_REQUEST['email'];
$row = $user->getByEmail($email);

if(!empty($row))
{
  echo 1 ;
}
else
{
  echo 0;
}

?>