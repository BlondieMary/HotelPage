<?php
  require_once __DIR__.'\..\boot\boot.php';

  use Hotel\User;
  use Hotel\Favorite;
  use Hotel\Review;
  use Hotel\Booking;

  $userId = User::getCurrentUserId();
  $currentDate = new DateTime();

  if(empty($userId))
  {
    header('Location: index.php');
  }

  $favorite = new Favorite();
  $userFavorites = $favorite->getListByUser($userId);

  $review = new Review();
  $userReviews = $review->getListByUser($userId);

  // Get all user's bookings
  $booking = new Booking();
  $userBookings = $booking->getListByUser($userId);

?>

<!DOCTYPE>
<html>
  <head>
    <meta charset= "UTF-8" >
    <meta name= "viewport" content=" width=device-width, initial-scale=1.0">
    <meta name="robots" content="index,follow">
    <link rel="icon" href="assets/css/images/favicon.png" type="image/x-icon">
    <title>College Link </title>
    <style type="text/css">
      body{
      background: #333;
      }
    </style>
    <link href="assets/css/style_one.css" type="text/css" rel="stylesheet"/  >
    <script  src="assets/js/script.js" type="text/javascript"></script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.3/themes/hot-sneaks/jquery-ui.css" />
    <script src="http://code.jquery.com/jquery-2.1.3.js"></script>
    <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script  src="assets/js/profilePageResponsive.js" type="text/javascript"></script>
  </head>
  <body>
<!-- //check for existing user -->
  <header>
    <div class="primary-menu text-right">
      <p class="main-logo">My Profile</p>
      <div class="nav">
        <label class="togle" for="toggle">&#9776;</label>
        <input type="checkbox" id="toggle"/>
          <div class="menu">
            <ul>
              <li><a href="index.php" ><i class="fas fa-home"></i>Home</a></li>
            <?php if (empty(User::getCurrentUserId())){?>
              <li><a href="login.php"><i class="fas fa-sign-in-alt"></i>LogIn</a></li>
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
  <main class="container-profile ">
    <div class="favorite-review-buttons">
      <button type="button" name="favorite-responsive-button" class="favorite-responsive-button" id="favorite-responsive-button">FAVORITE</button>
      <button type="button" name="review-responsive-button" class="review-responsive-button" id="review-responsive-button">REVIEWS</button>
    </div>
    <aside class="aside-bar box inline-block align-top text-left">
      <section class="favorite-bar">
        <h1><strong>FAVORITES</strong></h1>
          <?php if (count($userFavorites) > 0) { ?>
            <ol>
              <?php foreach ($userFavorites as $favorite) {?>
                <h3><li><a href="room.php?room_id=<?php echo $favorite['room_id']; ?>"><?php echo $favorite['name']; ?></a></li></h3>
              <?php  } ?>
            </ol>
      </section>
          <?php  }else { ?>
                <h3>You don't have any favorite hotels</h3>
          <?php  } ?>
      <section class="review-bar text-center ">
        <h1><strong>REVIEWS</strong></h1>
          <?php if (count($userReviews) > 0) { ?>
            <ol>
              <?php foreach ($userReviews as $reviews) {?>
                <h3><li><a href="room.php?room_id=<?php echo $reviews['room_id']; ?>"><?php echo $reviews['name']; ?></a><br>
                  <?php
                      // $roomAvgReview = $data['avg_reviews'];
                      for ($i=1; $i<=5; $i++) {
                      if ($reviews['rate'] >= $i){
                  ?>
                  <span class="fa fa-star checked"></span>
                  <?php }else { ?>
                  <span class="fa fa-star "></span>
                  <?php } } ?>
                    </li>
                </h3>
            <?php  } ?>
            </ol>
      </section>
      <?php  }else { ?>
        <h3>You haven't made any review yet</h3>
      <?php  } ?>
    </aside>
    <section class="hotel-list box inline-block align-top">
      <header class="page-title box">
        <h2>My bookings</h2>
      </header>
      <?php if (count($userBookings) > 0) { ?>
      <?php foreach ($userBookings as $booking) {?>
      <article class="Hotel">
        <aside class="media" name="city" >
          <img src=<?php echo "assets/css/images/".$booking['photo_url'] ?>  alt="Hotel 1" width="100%" height="auto" />
        </aside>
        <main class="info">
          <h1><?php echo $booking['name'] ?></h1>
          <h2><?php echo $booking['city']?>, <?php echo $booking['area'] ?></h2>
          <p><em><?php echo $booking['description_short'] ?> </em></p>
          <div class="room-page text-right">
            <button><a href="room.php?room_id=<?php echo $booking['room_id']."&check_in_date=".$booking['check_in_date']."&check_out_date=".$booking['check_out_date'];?>"> Go to Room Page</a></button>
          </div>
        </main>
        <div class="bottom-text-info box">
          <div class="room-price">
            <p>Per Night: <?php echo $booking['total_price'] ?> <span>&#8364;</span> </p>
          </div>
          <div class="Check-in_dates" style="border-right:1px solid; padding-right:35px;">
            <p><strong>Check in date</strong><br> <?php echo $booking['check_in_date'] ?> </p>
          </div>
          <div class="check-out-dates" style="border-right:1px solid;padding-left: 35px; padding-right:35px;">
            <p><strong>Check out date</strong><br> <?php echo $booking['check_out_date'] ?></p>
          </div>
          <div class="Room-type-bottom-bar">
            <p><strong>Type of Room</strong><br><?php echo $booking['room_type'] ?></p>
          </div>
          <?php   $outDateObj = new DateTime($booking['check_out_date']);
            if ($outDateObj > $currentDate ) { ?>
          <div class="cancel-booking-button">
            <form name="cancelbookingForm" action="actions/cancelBooking.php" method="post" class="bookingForm">
              <input type="hidden" name="room_id" value="<?php echo $booking['room_id']; ?>">
              <input type="hidden" name="check_in_date" value="<?php echo $booking['check_in_date'] ?>">
              <input type="hidden" name="check_out_date" value="<?php echo $booking['check_out_date']; ?>">
              <input type="hidden" name="csrf" value="<?php echo User::getCsrf(); ?>"/>
              <button class="cancel-book-sudmit-button" type="submit">Cancel Booking</button>
            </form>
          </div>
          <?php }else{ ?>
            <div class="completed-booking-button">
              <span class="complete-book">Completed Booking</span><br><br>
            </div>
            <?php } ?>
        </div>
        <div class="clear"></div>
        <?php } ?>
      </article>
    </section>
    <?php }else{ ?>
      <h3 style="color:red" class="text-center box">You haven't made any booking yet!!!</h3>
    <?php } ?>
  </main>
  <footer>
    <p> <i class="fas fa-copyright"></i> CollegeLink 2021</p>
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