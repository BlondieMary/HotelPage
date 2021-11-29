<?php
require_once __DIR__.'\..\..\boot\boot.php';

use Hotel\user;
use Hotel\Booking;

if(strtolower($_SERVER['REQUEST_METHOD']) != 'post')
{
  header('Location : /');
  return;
}

// Check if there is a User logged in - Redirect to HOME page if not
if(empty(User::getCurrentUserId()))
{
  header('Location: \public\index.php');
  return;
}

// Get Request's data
$roomId = $_REQUEST['room_id'];
$checkInDate = $_REQUEST['check_in_date'];
$checkOutDate = $_REQUEST['check_out_date'];

// Make sure a room id is given - redirect to HOME page if not
if (empty($roomId))
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

// Insert new Booking for the specified user, room and check in-out dates
$booking = new Booking();
$booking->insert($roomId, User::getCurrentUserId(), $checkInDate, $checkOutDate);
header(sprintf('Location: ../room.php?room_id=%s&check_in_date=%s&check_out_date=%s', $roomId, $checkInDate, $checkOutDate));