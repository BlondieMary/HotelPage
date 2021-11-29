<?php
require_once __DIR__.'\..\..\boot\boot.php';

use Hotel\user;
use Hotel\Booking;

if(strtolower($_SERVER['REQUEST_METHOD']) != 'post')
{
  header('Location : /');
  return;
}

if(empty(User::getCurrentUserId()))
{
  header('Location: \public\index.php');
  return;
}

// Get Request's data 
$roomId = $_REQUEST['room_id'];
$checkInDate = $_REQUEST['check_in_date'];
$checkOutDate = $_REQUEST['check_out_date'];

if(empty($roomId))
{
  header('Location: \public\index.php');
  return;
}

// Verify CSRF token
$csrf = $_REQUEST['csrf'];
if(empty($csrf) || !User::verifyCsrf($csrf))
{
  return;
}

// Remove Booking based on the given data
$booking = new Booking();
$booking->remove($roomId, User::getCurrentUserId(), $checkInDate, $checkOutDate);
header(sprintf('Location: ../profile.php'));