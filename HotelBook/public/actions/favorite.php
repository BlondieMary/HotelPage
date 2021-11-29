<?php
require_once __DIR__.'\..\..\boot\boot.php';

use Hotel\user;
use Hotel\Favorite;

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

// Check roomId sent with the Request
$roomId = $_REQUEST['room_id'];

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

// Set favorite
$favorite = new Favorite();

// Add or Remove room from favorite status

// Get Request's data
$isFavorite = $_REQUEST['is_favorite'];
$roomId = $_REQUEST['room_id'];
$checkInDate = $_REQUEST['check_in_date'];
$checkOutDate = $_REQUEST['check_out_date'];

# Check if the room should be add as favorite or removed from favorite
if(!$isFavorite)
{
  $favorite->addFavorite($roomId, User::getCurrentUserId());
}
else
{
  $favorite->removeFavorite($roomId, User::getCurrentUserId());
}

header(sprintf('Location: ../room.php?room_id=%s&check_in_date=%s&check_out_date=%s', $roomId, $checkInDate, $checkOutDate));