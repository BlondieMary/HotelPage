<?php
require_once __DIR__.'\..\..\boot\boot.php';

use Hotel\Room;
use Hotel\User;
use Hotel\Booking;

$userId = User::getCurrentUserId();
$roomId = $_REQUEST['room_id'];
$checkInDate = $_REQUEST['check_in_date'];
$checkOutDate = $_REQUEST['check_out_date'];


$booking = new Booking();
$CheckingBooks = $booking->isBooked($roomId, $checkInDate, $checkOutDate);
$userBooked = $booking->getBookedUser($roomId, $checkInDate, $checkOutDate);
?>

<div class="room-booking">
  <?php
  if($CheckingBooks)
  { ?>
    <?php
    if(!empty($userBooked) && array_key_exists('user_id', $userBooked) == true)
    {
      if($userBooked['user_id'] == $userId)
      { ?>
        <span class="already-book">Already Booked For You</span><br><br>
        <?php
      }
      else
      { ?>
        <span class="already-book">Already Booked</span><br><br>
        <?php
      }
    }
  }
  else
  { ?>
    <form name="bookingForm" action="actions/book.php" method="post" class="bookingForm">
      <input type="hidden" name="room_id" value="<?php echo $roomId; ?>">
      <input type="hidden" name="check_in_date" value="<?php echo $checkInDate; ?>">
      <input type="hidden" name="check_out_date" value="<?php echo $checkOutDate; ?>">
      <input type="hidden" name="csrf" value="<?php echo User::getCsrf(); ?>"/>
      <button class="book-sudmit-button" type="submit">Book Now !</button>
    </form>
    <?php
  } ?>
</div>