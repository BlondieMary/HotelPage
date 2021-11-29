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


$prices = $room->getMinMaxRoomPrices();
$priceMaxDefault = $prices['max'];
$priceMinDefault = $prices['min'];


if(array_key_exists('price_min', $_REQUEST))
{
  $price_min = $_REQUEST['price_min'];
}
else
{
  $price_min = $priceMinDefault;
}

if(array_key_exists('price_max', $_REQUEST))
{
  $price_max = $_REQUEST['price_max'];
}
else
{
  $price_max = $priceMaxDefault;
}

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
  $checkInDate = '';
}

if(array_key_exists('check_out_date',$_REQUEST))
{
  $checkOutDate = $_REQUEST['check_out_date'];
}
else
{
  $checkOutDate = '';
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
$allAvailableRooms = $room->search(new DateTime($checkInDate), new DateTime($checkOutDate), $price_min, $price_max, $city, $typeId, $CountofGuests);
?>
<!DOCTYPE>
<html>
  <head>
    <meta charset= "UTF-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index,follow">
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
    <script  src="assets/pages/search.js" type="text/javascript"></script>
    <script  src="assets/js/datepicker_list_page.js" type="text/javascript"></script>
    <script  src="assets/js/listfilterResponsive.js" type="text/javascript"></script>
    <script>
      $(document).ready(function() {
        var price_max_default = $('#price_max_default').val();
        var price_min_default = $('#price_min_default').val();
        $( "#mySlider" ).slider({
        range: true,
        min: 0,
        max: Number(price_max_default),
        values: [ price_min_default, price_max_default],
        slide: function( event, ui ) {
        $( "#price" ).val( "\u20AC" + ui.values[ 0 ] + "          \u20AC " + ui.values[ 1 ] );
        $("#price").css("border", "2px solid #ff764b");
        $("#price").css("color", "gray");
        $("#price").css("font-size", "15px");
        }
        });
        var values = $( "#mySlider" ).slider( "option", "values");
        $("#price_min").attr("value", values[0]);
        $("#price_max").attr("value", values[1]);

        $( "#price" ).val( "\u20AC" + $( "#mySlider" ).slider( "values", 0 ) + "         \u20AC"
        + $( "#mySlider" ).slider( "values", 1 ) );
        $("#price").css("color", "gray");
        $("#price").css("font-size", "15px");
        $("#price").css("border", "2px solid #ff764b");
        $("#mySlider").on("slidechange",function(e,ui){
        var values = ui.values;
        $("#price_min").attr("value", values[0]);
        $("#price_max").attr("value", values[1]);
        });
      });
    </script>
  </head>
  <body>
  <!-- //check for existing user -->
    <div class="container-search">
      <div class="container-search-list">
      <button type="button" name="filter-search" class="filter-search" id="filter-search"><i class="fas fa-filter"></i></button>
        <aside class="hotel-search box inline-block align-top">
          <div class="title-search-bar">
            <h1><strong>FIND THE HOTEL</strong></h1>
          </div>
          <form method="get" class="searchForm" name="searchForm" action="list.php" autocomplete="off" >
            <div class="introduction" id="formSearch">
              <div class="form-group ">
                <select name="count_of_guests" class= "count_of_guests text-center">
                  <option>Any</option>
                  <<?php
                    foreach ($guestCounts as $count) { ?>
                    <option <?php echo $Selectedcount == $count ? 'selected = selected': '' ?> value="<?php echo $count; ?>"><?php echo $count; ?></option>
                  <?php } ?>
                </select>
              </div>
              <select class= "check_rooms text-center check_cities" name="room_type">
                <option>Room</option>
                <<?php
                  foreach ($allTypes as $roomType) {?>
                    <option <?php echo $SelectedRoomType == $roomType['type_id'] ? 'selected = selected': '' ?> value="<?php echo $roomType['type_id']; ?>"><?php echo $roomType['name']; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-groupi-city">
              <select name="city" id="City_option_list" class= "check_cities text-center">
                <option>City</option>
                  <<?php
                    foreach ($cities as $city) {  ?>
                      <option <?php echo $Selectedcity == $city ? 'selected = selected': '' ?>  value="<?php echo $city; ?>"><?php echo $city; ?></option>
                  <?php } ?>
              </select>
            </div>
            <label for="price" style="font-family:Verdana;">Price Range:</label>
            <input type="text" id="price" class="price" disabled value="price" >
            <input type="hidden" id="price_max_default" class="price_max_default" value="<?php echo $price_max; ?>" >
            <input type="hidden" id="price_min_default" class="price_min_default" value="<?php echo $price_min; ?>" >
            <input name="price_min" type="hidden" id="price_min" class="price_min" value="price_min" >
            <input name="price_max" type="hidden" id="price_max" class="price_max" value="price_max" >
            <style>
              .ui-slider-horizontal .ui-slider-range {
              background: antiquewhite;
              }
              .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
              border: 1px solid #ffffff;
              background: #ff764b;
              color: #000000;
              border-radius: 100%;
              }
            </style>
            <div id="mySlider" class="mySlider"></div>
            <div class="minprice text-left">
              <h6>PRICE MIN.</h6>
            </div>
            <div class="maxprice text-right">
              <h6>PRICE MAX.</h6>
            </div>
            <fieldset class="date-picker-list">
              <div class="form-group-list inline-block">
                <label for="check_in_date"></label>
                <input name="check_in_date" id="check_in_date_list" value="<?php echo $checkInDate ;?>" class="text-center" placeholder="Check-in Date" type="text">
              </div>
              <div class="form-group-list inline-block">
                <label for="check_out_date"></label>
                <input name="check_out_date" id="check_out_date_list" value="<?php echo $checkOutDate ;?>" class="text-center" placeholder="Check-out Date" type="text">
              </div>
            </fieldset>
            <div class="action">
              <input name="submit" id="submitButtonList" enabled type="submit" value="FIND HOTEL">
            </div>
          </form>
        </aside>
        <section  class="hotel-list box inline-block align-top">
          <header class="page-title">
            <h2>Search Result</h2>
          </header>
          <article id="search-results-container" class="Hotel">
            <?php
              foreach ($allAvailableRooms as $allAvailableRoom) { ?>
                <aside class="media" name="city" >
                  <img src= <?php echo "assets/css/images/rooms/".$allAvailableRoom['photo_url'] ?> alt="Hotel 1" width="100%" height="auto" />
                </aside>
                <main class="info box">
                    <h1><?php echo $allAvailableRoom['name']; ?></h1>
                    <h2><?php echo $allAvailableRoom['city'] .", ". $allAvailableRoom['area'] ?> </h2>
                    <p><em><?php echo $allAvailableRoom['description_short'] ; ?> </em></p>
                      <div class="room-page text-right">
                        <button><a href=<?php echo "room.php?room_id=".$allAvailableRoom['room_id']."&check_in_date=".$checkInDate."&check_out_date=".$checkOutDate ?>> Go to Room Page</a></button>
                      </div>
                </main>
                <div class="bottom-text-info">
                  <div class="room-price">
                    <p>Per Night: <?php echo $allAvailableRoom['price']; ?><span>&#8364;</span> </p>
                  </div>
                  <div class="Count-guests">
                    <p>Count of Guests: <?php echo $allAvailableRoom['count_of_guests']; ?> </p>
                  </div>
                  <div class="Room-type-bottom-bar ">
                    <p>Type of Room: <?php if ($allAvailableRoom['type_id'] == 1){
                      echo "Single Room";
                    }elseif ($allAvailableRoom['type_id'] == 2 ) {
                      echo "Double Room";
                    }elseif ($allAvailableRoom['type_id'] == 3 ) {
                      echo "Triple Room";
                    }else {
                      echo "Fourfold Room";
                    }
                    ?></p>
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
        </section>
      </div>
    <footer class="footer-list">
      <p> Copyright <i class="fas fa-copyright"></i> CollegeLink 2021</p>
    </footer>
    </div>
  </body>
</html>