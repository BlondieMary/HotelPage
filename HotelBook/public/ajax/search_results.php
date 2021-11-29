<?php
require_once __DIR__.'\..\..\boot\boot.php';

use Hotel\Room;
use Hotel\User;
use Hotel\RoomType;

$room = new Room();
$cities = $room->getCities();
$guestCounts =$room->getCount();
$type = new RoomType();
$allTypes = $type->getAllTypes();

// Get Request's data 
$priceMin = $_REQUEST['price_min'];
$priceMax = $_REQUEST['price_max'];
$city = $_REQUEST['city'];
$Selectedcity = $city;
$typeId = $_REQUEST['room_type'];
$SelectedRoomType = $typeId;
$checkInDate = $_REQUEST['check_in_date'];
$checkOutDate = $_REQUEST['check_out_date'];

if(array_key_exists('count_of_guests',$_REQUEST))
{
  $CountofGuests = $_REQUEST['count_of_guests'];
}
else
{
  $CountofGuests = 'Any';
}
$Selectedcount = $CountofGuests;

//Search for available rooms using given data
$allAvailableRooms = $room->search(new DateTime($checkInDate), new DateTime($checkOutDate), $priceMin, $priceMax, $city, $typeId, $CountofGuests);

?>
<article  id="search-results-container" class="Hotel">
  <?php
   foreach ($allAvailableRooms as $allAvailableRoom) { ?>
    <aside class="media" name="city" >
      <img src= <?php echo "assets/css/images/rooms/".$allAvailableRoom['photo_url'] ?> alt="Hotel 1" width="100%" height="auto" />
    </aside>
    <main class="info">
      <h1><?php echo $allAvailableRoom['name']; ?></h1>
      <h2><?php echo $allAvailableRoom['city'] .", ". $allAvailableRoom['area'] ?> </h2>
      <p><em><?php echo $allAvailableRoom['description_short'] ; ?> </em></p>
        <div class="room-page text-right">
          <button><a href=<?php echo "room.php?room_id=".$allAvailableRoom['room_id']."&check_in_date=".$checkInDate."&check_out_date=".$checkOutDate ?>> Go to Room Page</a></button>
        </div>
    </main>
      <div class="bottom-text-info">
        <div class="room-price">
          <p>Per Night : <?php echo $allAvailableRoom['price']; ?><span>&#8364;</span> </p>
        </div>
      <div class="Count-guests">
        <p>Count of Guests :<?php echo $allAvailableRoom['count_of_guests']; ?> </p>
      </div>
      <div class="Room-type-bottom-bar ">
        <p>Type of Room :<?php 
          if ($allAvailableRoom['type_id'] == 1){
            echo "Single Room";
          }elseif ($allAvailableRoom['type_id'] == 2 ) {
            echo "Double Room";
          }elseif ($allAvailableRoom['type_id'] == 3 ) {
            echo "Triple Room";
          }else {
          echo "Fourfold Room";
          } ?>
        </p>
      </div>
    </div>
    <div class="clear"></div>
      <?php } ?>
        <section>
          <?php if (count($allAvailableRooms) == 0){ ?>
          <h3 class="check-search-rooms">There are no Rooms </h2>
          <?php } ?>
        </section>
</article>