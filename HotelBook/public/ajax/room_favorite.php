<?php
require_once __DIR__.'\..\..\boot\boot.php';

use Hotel\user;
use Hotel\Favorite;

if(strtolower($_SERVER['REQUEST_METHOD']) != 'post')
{
  die;
}

if(empty(User::getCurrentUserId()))
{
  echo "No current user for this operation";
  die;
}

$roomId = $_REQUEST['room_id'];
if(empty($roomId))
{
  echo "No room given";
  die;
}

// Verify Token
$csrf = $_REQUEST['csrf'];
if(empty($csrf) || !User::verifyCsrf($csrf))
{
  return;
}

// Add or remove from favorite
$favorite = new Favorite();
$isFavorite = $_REQUEST['is_favorite'];
$roomId = $_REQUEST['room_id'];

# Check if the room should be add as favorite or removed from favorite
if(!$isFavorite)
{
  $status = $favorite->addFavorite($roomId, User::getCurrentUserId());
}
else
{
  $status = $favorite->removeFavorite($roomId, User::getCurrentUserId());
}

// Return status to AJAX Call
header('Content-Type: application/json');
echo json_encode([
  'status' => $status,
  'is_favorite' => !$isFavorite,
]);