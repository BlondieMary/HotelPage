<?php
  require_once __DIR__.'\..\boot\boot.php';
  use Hotel\Room;
  use Hotel\User;
  use Hotel\RoomType;
  use Hotel\Favorite;
  use Hotel\Review;
  use Hotel\Booking;
  
  $room = new Room();
  $cities = $room->getCities();

  $type = new RoomType();
  $allTypes = $type->getAllTypes();

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	$room = new Room();
$cities = $room->getCities();
$guestCounts =$room->getCount();
$type = new RoomType();
$allTypes = $type->getAllTypes();
$prices = $room->getMinMaxRoomPrices();
$priceMaxDefault = $prices['max'];
$priceMinDefault = $prices['min'];
if(array_key_exists('city',$_REQUEST))
{
  $city = $_REQUEST['city'];
}
else
{
  $city = 'City';
}
$Selectedcity = $city;
if(array_key_exists('room_type',$_REQUEST))
{
  $typeId = $_REQUEST['room_type'];
}
else
{
  $typeId = 'Room';
}
$SelectedRoomType = $typeId;
if(array_key_exists('check_in_date',$_REQUEST))
{
  $checkInDate = $_REQUEST['check_in_date'];
}
else
{
  $date = new DateTime();
  $checkInDate =  $date->format('d-m-Y');
}
if(array_key_exists('check_out_date',$_REQUEST))
{
  $checkOutDate = $_REQUEST['check_out_date'];
}
else
{
  $date = date('Y-m-d');
  $endDate = new \DateTime($date);
  $endDate->modify("+2 day");
  $endDate->format('Y-m-d');
  $checkOutDate = $endDate->format('d-m-Y');
}
if(array_key_exists('count_of_guests',$_REQUEST))
{
  $CountofGuests = $_REQUEST['count_of_guests'];
}
else
{
  $CountofGuests = 'Any';
}
$Selectedcount = $CountofGuests;
//Search for available rooms
$allAvailableRooms = $room->search(new DateTime($checkInDate), new DateTime($checkOutDate), $priceMinDefault, $priceMaxDefault, $city, $typeId, $CountofGuests);
	
	
	


}
?>
<!DOCTYPE>
<html>
  <head>
    <meta charset= "UTF-8" >
    <meta name= "viewport" content=" width=device-width, initial-scale=1.0">
    <meta name="robots" content="index,follow">
    <link rel="icon" href="assets/css/images/favicon.png" type="image/x-icon">
	<link rel="stylesheet" href="../assets/css/font-awesome.min.css">
	
    <title>College Link </title>
    <style type="text/css">
      body{
      background: #333;
      }
    </style>
	<style>
* {
  box-sizing: border-box;
}

body {
  background-color: #f1f1f1;
  padding: 20px;
  font-family: Arial;
}

/* Center website */
.main {
  max-width: 1000px;
  margin: auto;
}

h1 {
  font-size: 50px;
  word-break: break-all;
}

.row {
  margin: 10px -16px;
}

/* Add padding BETWEEN each column */
.row,
.row > .column {
  padding: 8px;
}

/* Create three equal columns that floats next to each other */
.column {
  float: left;
  width: 33.33%;
  display: none; /* Hide all elements by default */
}

/* Clear floats after rows */ 
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Content */
.content {
  background-color: white;
  padding: 10px;
}

/* The "show" class is added to the filtered elements */
.show {
  display: block;
}

/* Style the buttons */
.btn {
  border: none;
  outline: none;
  padding: 12px 16px;
  background-color: white;
  cursor: pointer;
}

.btn:hover {
  background-color: #ddd;
}

.btn.active {
  background-color: #666;
  color: white;
}
</style>
   
    <script src="http://code.jquery.com/jquery-2.1.3.js"></script>
    <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script  src="assets/js/script.js" type="javascript"></script>
    <script  src="assets/js/datepickers.js" type="text/javascript"></script>
    <script  src="assets/pages/index.js" type="text/javascript"></script>
	<script src="assets/pages/search.js"></script>
	<script  src="assets/js/datepickers.js" type="text/javascript"></script>
	<script  src="assets/js/profilePageResponsive.js" type="text/javascript"></script>
	<link href="assets/css/style_one.css" type="text/css" rel="stylesheet"/  >
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.3/themes/hot-sneaks/jquery-ui.css" />
  
  </head>
  <body>
    <header>
      <div class="primary-menu text-right">
		 <span>College Link 2021</span>
		    <h2>A COMFORTABLE & MEMORABLE EXPERIENCE</h2>
        <div class="nav">
          <label class="togle" for="toggle">&#9776;</label>
          <input type="checkbox" id="toggle"/>
          <div class="menu">
            <ul>
              <li><a href="index.php" >Home</a></li>
              <?php if (empty(User::getCurrentUserId())){?>
			  <li><a href = "#rooms">Rooms</a></li>
              <li><a href="login.php">Login</a></li>
              <li><a href="register.php">Register</a></li>
              <?php }
              else {?>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="actions/logout.php">Log Out</a></li>
              <?php } ?>
            </ul>
          </div>
        </div>
      </div>
    </header>
	 <div id = "modal"></div>
    <main class="main-content view-hotel page-home" id="main-content">
      <div class="container">
        <section class="hero">
          <form name="listForm" class="listForm box" id="listForm" method="get" action="#rooms" autocomplete="off" ">
	
            <fieldset class="introduction" id="form-introduction">
              <div class="form-group inline-block">
                <select name="city" id="City_option" class= "check_cities text-center" required>
                  <option>City</option>
                    <?php
                    foreach ($cities as $city) {
                    ?>
                    <option value="<?php echo $city; ?>"><?php echo $city; ?></option>
                    <?php } ?>
                </select>
              </div>
              <div class="form-group inline-block">
                <select class= "check_rooms text-center check_cities" name="room_type">
                  <option>Room</option>
                    <?php
                    foreach ($allTypes as $roomType) {?>
                    <option value="<?php echo $roomType['type_id']; ?>"><?php echo $roomType['name']; ?></option>
                    <?php } ?>
                </select>
              </div>
            </fieldset>
            <fieldset class="date-picker">
              <div class="form-group inline-block">
                <label for="check_in_date"></label>
                <input id="check_in_date" name="check_in_date" placeholder="Check-in Date" type="text" class="text-center" required>
              </div>
              <div class="form-group inline-block">
                <label for="check_out_date"></label>
                <input id="check_out_date" name="check_out_date" placeholder="Check-out Date" type="text" class="text-center" required>
              </div>
            </fieldset>
			<div class="action text-center">
            <a href="#rooms"> <input name="Search" class="submitbutton"   id="submitButton" type="submit" value="Search"> </a>
            </div> 
			
    </main>
	
	 <div class="primary-menu text-center" id = "rooms">
		 <h2>Our rooms are definitely something special!</h2>
                <span>Comfortable & Cozy</span>
				<hr class="dashed">
            </div>
			
			
			<h2>ROOMS</h2>
<div id="myBtnContainer">
  <button class="btn active" onclick="filterSelection('all')"> Show all</button>
  <button class="btn" onclick="filterSelection('athens')"> Athens</button>
  <button class="btn" onclick="filterSelection('heraklion')"> Heraklion</button>
  <button class="btn" onclick="filterSelection('thessaloniki')"> Thessaloniki</button>
</div>

<!-- Portfolio Gallery Grid -->
<div class="row">
  <div class="column athens">
    <div class="content">
      <img src="assets/css/images/12.jpg" alt="Athens" style="width:100%">
      <h4>Athens</h4>
	  <div class="room-text">
	  <h3>Deluxe Acropolis</h3>
	  <ul>
            <li>
                <i class = "fas fa-arrow-alt-circle-right"></i>
                               Deluxe Double Room
                            </li>
                            <li>
                                <i class = "fas fa-arrow-alt-circle-right"></i>
                              Acropolis, Athens
                            </li>
                            <li>
                                <i class = "fas fa-arrow-alt-circle-right"></i>
                            Deluxe Double Room with Acropolis View
                            </li>
                        </ul>
                        <p class = "rate">
                            <span>€350.00 /</span> Per Night
                        </p>
						<div class="room-page text-left">
            <button><a href="room.php?room_id=5"> Go to Room Page!</a></button>
          </div>
                    </div>
					
    </div>
  </div>
  <div class="column athens">
    <div class="content">
      <img src="assets/css/images/13.jpg" alt="Athens" style="width:100%">
      <h4>Athens</h4>
	  <div class="room-text">
	  <h3>Premium Acropolis</h3>
	  <ul>
            <li>
                <i class = "fas fa-arrow-alt-circle-right"></i>
                               Premium Double Room
                            </li>
                            <li>
                                <i class = "fas fa-arrow-alt-circle-right"></i>
                              Acropolis, Athens
                            </li>
                            <li>
                                <i class = "fas fa-arrow-alt-circle-right"></i>
                            Premium Double Room with Acropolis View
                            </li>
                        </ul>
                        <p class = "rate">
                            <span>€410.00 /</span> Per Night
                        </p>
                         <div class="room-page text-left">
            <button><a href="room.php?room_id=6"> Go to Room Page!</a></button>
          </div>
                    </div>
    </div>
  </div>
  <div class="column heraklion">
    <div class="content">
      <img src="assets/css/images/9.jpg" alt="Heraklion" style="width:100%">
      <h4>Heraklion</h4>
	  <div class="room-text">
	  <h3>Deluxe</h3>
	  <ul>
            <li>
                <i class = "fas fa-arrow-alt-circle-right"></i>
                               Deluxe Double Room
                            </li>
                            <li>
                                <i class = "fas fa-arrow-alt-circle-right"></i>
                              Analipsis, Heraklion
                            </li>
                            <li>
                                <i class = "fas fa-arrow-alt-circle-right"></i>
                            Deluxe double room with shared pool
                            </li>
                        </ul>
                        <p class = "rate">
                            <span>€230.00 /</span> Per Night
                        </p>
                       <div class="room-page text-left">
            <button><a href="room.php?room_id=1"> Go to Room Page!</a></button>
          </div>
                    </div>
    </div>
  </div>

  <div class="column heraklion">
    <div class="content">
      <img src="assets/css/images/8.jpg" alt="Heraklion" style="width:100%">
      <h4>Heraklion</h4>
	  <div class="room-text">
	  <h3>Premium Room</h3>
	  <ul>
            <li>
                <i class = "fas fa-arrow-alt-circle-right"></i>
                               Premium Double Room
                            </li>
                            <li>
                                <i class = "fas fa-arrow-alt-circle-right"></i>
                              Analipsis, Heraklion
                            </li>
                            <li>
                                <i class = "fas fa-arrow-alt-circle-right"></i>
                            Premium double room with private pool
                            </li>
                        </ul>
                        <p class = "rate">
                            <span>€340.00 /</span> Per Night
                        </p>
                        <div class="room-page text-left">
            <button><a href="room.php?room_id=3"> Go to Room Page!</a></button>
          </div>
                    </div>
    </div>
  </div>
  
  <div class="column heraklion">
    <div class="content">
      <img src="assets/css/images/7.jpg" alt="Heraklion" style="width:100%">
      <h4>Heraklion</h4>
	  <div class="room-text">
	  <h3>Island Villa</h3>
	  <ul>
            <li>
                <i class = "fas fa-arrow-alt-circle-right"></i>
                               Island Villa
                            </li>
                            <li>
                                <i class = "fas fa-arrow-alt-circle-right"></i>
                              Analipsis, Heraklion
                            </li>
                            <li>
                                <i class = "fas fa-arrow-alt-circle-right"></i>
                            Island Villa with private pool. 
                            </li>
                        </ul>
						<p> Max number of guest = 5. </p>
                        <p class = "rate">
                            <span>€700.00 /</span> Per Night
                        </p>
                         <div class="room-page text-left">
            <button><a href="room.php?room_id=4"> Go to Room Page!</a></button>
          </div>
                    </div>
    </div>
  </div>
  
  
  <div class="column thessaloniki">
    <div class="content">
      <img src="assets/css/images/14.jpg" alt="Thessaloniki" style="width:100%">
      <h4>Thessaloniki</h4>
	  <div class="room-text">
	  <h3>Double Room</h3>
	  <ul>
            <li>
                <i class = "fas fa-arrow-alt-circle-right"></i>
                               Double Room
                            </li>
                            <li>
                                <i class = "fas fa-arrow-alt-circle-right"></i>
                              Aristotelous, Thessaloniki
                            </li>
                            <li>
                                <i class = "fas fa-arrow-alt-circle-right"></i>
                           Double Room in the famous square Aristotelous 
                            </li>
                        </ul>
						
                        <p class = "rate">
                            <span>€50.00 /</span> Per Night
                        </p>
                         <div class="room-page text-left">
            <button><a href="room.php?room_id=7"> Go to Room Page!</a></button>
          </div>
                    </div>
    </div>
  </div>
  
<!-- END GRID -->
</div>
			
			
			
			
			
    <footer class="footer-index">
      <p><i class="fas fa-copyright"></i> CollegeLink 2021</p>
	   
    </footer>
	 <script src="script.js"></script>
	<script>
filterSelection("all")
function filterSelection(c) {
  var x, i;
  x = document.getElementsByClassName("column");
  if (c == "all") c = "";
  for (i = 0; i < x.length; i++) {
    w3RemoveClass(x[i], "show");
    if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], "show");
  }
}

function w3AddClass(element, name) {
  var i, arr1, arr2;
  arr1 = element.className.split(" ");
  arr2 = name.split(" ");
  for (i = 0; i < arr2.length; i++) {
    if (arr1.indexOf(arr2[i]) == -1) {element.className += " " + arr2[i];}
  }
}

function w3RemoveClass(element, name) {
  var i, arr1, arr2;
  arr1 = element.className.split(" ");
  arr2 = name.split(" ");
  for (i = 0; i < arr2.length; i++) {
    while (arr1.indexOf(arr2[i]) > -1) {
      arr1.splice(arr1.indexOf(arr2[i]), 1);     
    }
  }
  element.className = arr1.join(" ");
}


// Add active class to the current button (highlight it)
var btnContainer = document.getElementById("myBtnContainer");
var btns = btnContainer.getElementsByClassName("btn");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function(){
    var current = document.getElementsByClassName("active");
    current[0].className = current[0].className.replace(" active", "");
    this.className += " active";
  });
}
</script>
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
	<link href="assets/css/styles_list.css" type="text/css" rel="stylesheet" />
  </body>
</html>