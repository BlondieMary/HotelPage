<?php
require_once __DIR__.'\..\..\boot\boot.php';

use Hotel\user;
use Hotel\Review;

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
$rate = $_REQUEST['rate'];
$checkInDate = $_REQUEST['check_in_date'];
$checkOutDate = $_REQUEST['check_out_date'];

if(empty($roomId))
{
  header('Location: \public\index.php');
  return;
}

// Verify CSRF token
$csrf = $_REQUEST['csrf'];
if($csrf || !User::verifyCsrf($csrf))
{
  return;
}

// Check if the given rate is valid 
if($rate == 0)
{
  header(sprintf('Location: ../room.php?room_id=%s&check_in_date=%s&check_out_date=%s&norate=1', $roomId, $checkInDate, $checkOutDate));
}
else
{
  // Add a review based on given data
  $review = new Review();
  $review->insert($roomId, User::getCurrentUserId(), $_REQUEST['rate'], $_REQUEST['comment']);
  header(sprintf('Location: ../room.php?room_id=%s&check_in_date=%s&check_out_date=%s', $roomId, $checkInDate, $checkOutDate));
}

