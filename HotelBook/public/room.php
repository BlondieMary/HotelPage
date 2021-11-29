<?php
require_once __DIR__.'\..\boot\boot.php';
use Hotel\Room;
use Hotel\User;
use Hotel\RoomType;
use Hotel\Favorite;
use Hotel\Review;
use Hotel\Booking;

$newerDate = false;
$currentDate = new DateTime();
$today = $currentDate->format('d-m-Y');
$date = date('Y-m-d');
$endDate = new \DateTime($date);
$endDate->modify("+2 day");
$endDate->format('d-m-Y');


$room = new Room();
$favorite = new Favorite();
$review = new Review();
$roomId = new Room();
// Get room's data
if(array_key_exists('room_id', $_REQUEST) == true)
{
  $roomData = $room->getRoomById($_REQUEST['room_id']);
}
else
{
  $roomData = Array();
}

// Check if a room exists
if(empty($roomData))
{
  $data = Array();
}
else
{
  $data = $roomData[0];
}

// Get current User's Id
$userId = User::getCurrentUserId();

if(array_key_exists('room_id', $_REQUEST) == false)
{
	

    $roomId = 'SELECT * FROM room WHERE room_id IS NOT NULL ';
    
} 

$roomId = $data['room_id'];
$locationLat = $data['location_lat'];
$locationLong = $data['location_long'];

// Check if room is favorite for current userId
$isFavorite = $favorite->isFavorite($data['room_id'], $userId);

// Load all Room Reviews
$allReviews = $review->getReviewsByRoom($roomId);

// Check if given check-in date are before current date to set the datepickers dates
if(array_key_exists('check_in_date', $_REQUEST) == true)
{
  $inDateObj = new DateTime($_REQUEST['check_in_date']);
  if($inDateObj >= $currentDate)
  {
    $checkInDate = $_REQUEST['check_in_date'];
  }
  else
  {
    $checkInDate = $today;
  }
}
else
{
  $checkInDate = $today;
}

// Check if given check-out date are before current date to set the datepickers dates
if(array_key_exists('check_out_date',$_REQUEST) == true)
{
  $outDateObj = new DateTime($_REQUEST['check_out_date']);
  if($outDateObj >= $currentDate)
  {
    $checkOutDate = $_REQUEST['check_out_date'];
    $newerDate=true;
  }
  else
  {
    $checkOutDate = $endDate->format('d-m-Y');
  }
}
else
{
  $checkOutDate = $endDate->format('d-m-Y');
}

// True if both check-in and check-out dates are given/present
$alreadyBook = empty($checkInDate) || empty($checkOutDate);

if(!$alreadyBook)
{
  // Look for bookings if check-in dates and check-out dates are present - get user who booked the room
  $booking = new Booking();
  $alreadyBook = $booking->isBooked($roomId, $checkInDate, $checkOutDate);
  $userBooked = $booking->getBookedUser($roomId, $checkInDate, $checkOutDate);
}

?>

<!DOCTYPE>
<html>
  <head>
    <meta charset= "UTF-8" >
    <meta name= "viewport" content=" width=device-width, initial-scale=1.0">
    <meta name="robots" content="index,follow">
    <link rel="icon" href="assets/css/images/favicon.png" type="image/x-icon">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>College Link </title>
    <style type="text/css">
    body{
    background: #333;
    }
    </style>
    <link href="assets/css/style_one.css" type="text/css" rel="stylesheet"/  >
    <script src="http://code.jquery.com/jquery-2.1.3.js"></script>
    <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script  src="assets/js/script.js" type="javascript"></script>
    <script  src="assets/js/datepickerRoomPage.js" type="text/javascript"></script>
    <script  src="assets/js/review.js" type="text/javascript"></script>
    <script  src="assets/pages/room.js" type="text/javascript"></script>
  </head>
  <body>
    <!-- //check for existing user -->
    <header>
      <div class="primary-menu text-right">
        <p class="main-logo">Hotels</p>
          <div class="nav">
            <label class="togle" for="toggle">&#9776;</label>
            <input type="checkbox" id="toggle"/>
              <div class="menu">
                <ul>
                  <li><a href="index.php" ><i class="fas fa-home"></i>Home</a></li>
                  <?php if (empty(User::getCurrentUserId())){?>
                    <li><a href="login.php"><i class="fas fa-sign-in-alt"></i>Login</a></li>
                    <li><a href="register.php">Register</a></li>
                  <?php } else {?>
                  <li><a href="profile.php"><i class="fas fa-user"></i>Profile</a></li>
                  <li><a href="actions/logout.php"><i class="fas fa-sign-out-alt"></i>Log Out</a></li>
                  <?php } ?>
                </ul>
              </div>
          </div>
      </div>
    </header>
    <main>
      <div class="container-room flex-box">
        <div class="room-top-bar box text-left">
          <div class="room-title">
            <h1><?php echo $data['name']; ?>  -  <?php echo $data['city'];?> , <?php echo $data['area']; ?> | </h1>
          </div>
          <div class="room-review ">
            <span>Reviews :</span>
            <?php
              $roomAvgReview = $data['avg_reviews'];
              for ($i=1; $i<=5; $i++) {
              if ($roomAvgReview >= $i){?>
              <span class="fa fa-star checked"></span>
            <?php }else { ?>
            <span class="fa fa-star "></span>
            <?php } } ?>
            <span> | </span>
          </div>
          <div class="room-favorite box" id="favorite">
            <form name="favoriteForm" method="post" id="favoriteForm" class="favoriteForm" action="actions/favorite.php">
              <input type="hidden" name="room_id" value="<?php echo $data['room_id']; ?>">
              <input type="hidden" name="is_favorite" value="<?php echo $isFavorite ? '1' : '0'; ?>">
              <input type="hidden" name="csrf" value="<?php echo User::getCsrf(); ?>"/>
              <button type="submit" class="favorite-btn box"><i class="fas fa-heart  <?php echo $isFavorite ? 'selected' : ''; ?>"></i></button>
            </form>
          </div>
          <div class="rooms-price box text-right">
            <h1> Per Night : <?php echo $data['price']; ?><span>&#8364;</span></h1>
          </div>
        </div>
        <div class="room-photo flex-box">
          <img src= <?php echo "assets/css/images/".$data['photo_url'] ?> alt="Hotel " width="85%" height="auto" />
        </div>
        <div class="rooms-info box">
          <table>
            <thead>
              <tr>
                <th scope="col"><h1><i class="fas fa-user"></i> <?php echo $data['count_of_guests']; ?></h1></th>
                <th scope="col"><h1><i class="fas fa-bed"></i> <?php echo $data['type_id']; ?></h1></th>
                <th scope="col"><h1><i class="fas fa-warehouse"></i> <?php echo $data['parking']; ?></h1></th>
                <th scope="col"><h1><i class="fas fa-wifi"></i></i>
                <?php if ($data['wifi'] == true){
                  echo "YES";
                }else {
                  echo "NO";
                }; ?></h1></th>
                <th scope="col"><h1><i class="fas fa-paw"></i>
                <?php if ($data['pet_friendly'] == true){
                  echo "YES";
                }else {
                  echo "NO";
                }; ?></h1></th>
              </tr>
              <tr>
                <th><h1>COUNT OF GUESTS</h1> </th>
                <th><h1>TYPE OF ROOM</h1> </th>
                <th><h1>PARKING</h1></th>
                <th><h1>WIFI</h1></th>
                <th><h1>PET FRIENDLY</h1></th>
              </tr>
          </thead>
        </table>
    </div>
    <div class="rooms-info-description box">
      <h1><em>Room Description</em></h1>
      <p><?php echo $data['description_long']; ?></p>
    </div>
    <div class="grid-container">
      <div class="item1 text-center">
        <div class="date-checks-picker">
          <form name="bookingsDates" id="bookingsDates" class="bookingsDates" method="get">
            <input type="hidden" name="room_id" value="<?php echo $roomId; ?>">
              <fieldset class="date-picker " id="date-checks-picker">
                <div class="form-group inline-block">
                  <label class="Check-Dates" for="check_in_date">Check In Date</label><br>
                  <input id="check_in_date" class="check_In_date text-center"  name="check_in_date" placeholder="Check-in Date" value="<?php echo $checkInDate; ?>" type="text" autocomplete="off" >
                </div>
                <div class="form-group inline-block">
                  <label for="check_out_date" class="Check-Dates">Check Out Date</label><br>
                  <input id="check_out_date"  class="check_Out_date text-center"   name="check_out_date" placeholder="Check-out Date" value="<?php echo $checkOutDate; ?>" type="text" autocomplete="off">
                </div><br><br>
                <button class="check-dates-sudmit-buttons" id="check_dates"  type="submit">Check Dates</button>
              </fieldset>
          </form>
        </div>
      </div>
      <div class="item2 text-center">
        <div class="room-booking flex-box">
        <?php
        // check bookings dates
        if ($newerDate == true) {
          if ($alreadyBook) {?>
            <?php
              if (!empty($userBooked) && array_key_exists('user_id',$userBooked) == true){
                if ($userBooked['user_id'] == $userId) {?>
                  <span class="already-book">Already Booked For You</span><br><br>
                <?php }else { ?>
                <span class="already-book">Already Booked </span><br><br>
                <?php  } } } else { ?>
                  <form name="bookingForm" action="actions/book.php" method="post" class="bookingForm">
                    <input type="hidden" name="room_id" value="<?php echo $roomId; ?>">
                    <input type="hidden" name="check_in_date" value="<?php echo $checkInDate; ?>">
                    <input type="hidden" name="check_out_date" value="<?php echo $checkOutDate; ?>">
                    <input type="hidden" name="csrf" value="<?php echo User::getCsrf(); ?>"/>
                    <button class="book-sudmit-button" type="submit">Book Now !</button>
                  </form>
                <?php }} ?>
        </div>
      </div>
    </div>
    <div class="map box">
      <iframe
      width="100%"
      height="500px"
      frameborder="0"
      scrolling="yes"
      marginheight="0"
      marginwidth="0"
      src="https://maps.google.com/maps?q=<?php echo $locationLat; ?>,<?php echo $locationLong; ?>&hl=el&z=16&amp;output=embed" >
      </iframe>
    </div>
    <div id="room-reviews" class="room-reviews box">
      <h1>Reviews</h1><br>
      <?php
        foreach ($allReviews as $counter => $review) {?>
		
          <div id="all-room-review-form">
            <h4><span><?php echo sprintf('%d. %s', $counter + 1, $review['user_name']);?></span>
              <div class="div-reviews">
                <?php
                  for ($i=1; $i<=5; $i++) {
                    if ($review['rate'] >= $i){ ?>
                      <span class="fa fa-star checked"></span>
                <?php }else {  ?>
                  <span class="fa fa-star"></span>
                <?php  } } ?>
              </div>
            </h4>
            <h5>Created at: <?php echo $review['created_time'] ;?></h5>
            <p><?php echo htmlentities($review['comment']) ;?></p>
          </div>
      <?php } ?>
    </div>
    <div class="add-review text-left">
      <h1>Add Review</h1>
          <form name="reviewForm" id="reviewForm" class="reviewForm" action="actions/review.php" method="post">
            <input type="hidden" name="room_id" value="<?php echo $roomId; ?>"/>
            <input type="hidden" name="check_in_date" value="<?php echo $checkInDate; ?>"/>
            <input type="hidden" name="check_out_date" value="<?php echo $checkOutDate; ?>"/>
            <input type="hidden" name="csrf" value="<?php echo User::getCsrf(); ?>"/>
              <h4>
                <fieldset class="rating flex-box">
                  <input type="hidden" name="rate" value="0"/>
                  <input type="radio" class="star1" id="star1" name="rate" value="1"/><label class="fas fa-star" id="fastar1" for="star1" title="Very Bad- 1 stars"></label>
                  <input type="radio" class="star2" id="star2" name="rate" value="2"/><label class="fas fa-star" id="fastar2" for="star2" title="Bad- 2 stars"></label>
                  <input type="radio" class="star3" id="star3" name="rate" value="3"/><label class="fas fa-star" id="fastar3" for="star3" title="Good- 3 stars"></label>
                  <input type="radio" class="star4" id="star4" name="rate" value="4"/><label class="fas fa-star" id="fastar4" for="star4" title="Pretty Good- 4 stars"></label>
                  <input type="radio" class="star5" id="star5" name="rate" value="5"/><label class="fas fa-star" id="fastar5" for="star5" title="Awsome-5 stars"></i></label>
                </fieldset>
              </h4><br>
            <div class="review-textarea">
              <textarea name="comment" id="review_field" class="form-control-textarea" placeholder="Review"></textarea>
            </div>
            <div class="review-sudmit">
              <button type="submit"  disabled  id="btn" name="review-sudmit-button">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </main>
    <footer>
      <p><i class="fas fa-copyright"></i> CollegeLink 2021</p>
    </footer>
    <link href="assets/css/small_monitor.css" type="text/css" rel="stylesheet"/>
    <link href="assets/css/tablet.css" type="text/css" rel="stylesheet"/>
    <link href="assets/css/mobile.css" type="text/css" rel="stylesheet"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link href="assets/css/fontawsome.min.css" rel="stylesheet" >
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  </body>
</html>