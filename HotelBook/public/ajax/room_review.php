<?php
require_once __DIR__.'\..\..\boot\boot.php';

use Hotel\user;
use Hotel\Review;

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

// Get Request's data
$roomId = $_REQUEST['room_id'];
$rate = $_REQUEST['rate'];
$checkInDate = $_REQUEST['check_in_date'];
$checkOutDate = $_REQUEST['check_out_date'];

// Verify CSRF token
$csrf = $_REQUEST['csrf'];
if(empty($csrf) || !User::verifyCsrf($csrf))
{
  echo "This is an invalid request";
  return;
}

// Add a review based on given data
$review = new Review();
$review->insert($roomId, User::getCurrentUserId(), $_REQUEST['rate'], $_REQUEST['comment']);

// Get user to update the Reviews section
$user = new User;
$userInfo = $user->getByUserId(User::getCurrentUserId());

// Get all Reviews of the Room
$roomReviews = $review->getReviewsByRoom($roomId);
$counter = count($roomReviews);


?>
<div id="all-room-review-form">
  <h4>
    <span><?php echo sprintf('%d. %s', $counter, $userInfo['name']);?></span>
      <div class="div-reviews">
        <?php
          for ($i=1; $i<=5; $i++)
        {
          if ($_REQUEST['rate'] >= $i)
        { ?>
          <span class="fa fa-star checked"></span>
        <?php } else { ?>
          <span class="fa fa-star "></span>
        <?php } } ?>
      </div>
  </h4>
  <h5>Created at: <?php echo (new DateTime())-> format('Y-m-d H:i:s') ;?></h5>
  <p><?php echo htmlentities($_REQUEST['comment']) ;?></p>
</div>