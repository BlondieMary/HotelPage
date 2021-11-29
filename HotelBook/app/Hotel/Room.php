<?php

namespace Hotel;

use PDO;
use DateTime;
use Hotel\BaseService;


class Room extends BaseService
{
  /**
  * Room
  *
  * This class inherits BaseService and handles the rooms
  */

  private $pdo;

  public function getCities()
  {
    /**
    * Fetches all different cities for which there are rooms
    *
    * @return Array an array containing all available distinct cities
    */

    $cities = [];

    // Fetch all distinct cities
    $rows = $this->fetchAll('SELECT DISTINCT city FROM room');

    // Add each city name to a new array to be return as a simple array
    foreach ($rows as $row)
    {
      $cities[] = $row['city'];
    }

    return $cities;
  }

  public function getCount()
  {
    /**
    * Fetches distinct count of guests options
    * @return Array an array containing all available distinct counts of guests options
    */

    $counts = [];

    $rows = $this->fetchAll('SELECT DISTINCT count_of_guests FROM room');

    foreach ($rows as $row)
    {
      $counts[] = $row['count_of_guests'];
    }

    return $counts;
  }

  public function search($checkInDate, $checkOutDate, $priceMin, $priceMax, $city = '', $typeId = '', $CountofGuests = '')
  {
    /**
    * Fetches rooms based on different given parameters
    * @param string $checkInDate check-in date to search for room availability
    * @param string $checkOutDate check-out date to search for room availability
    * @param string $priceMin minimum room price option
    * @param string $priceMax maximum room price option
    * @param string $city the name of the city for which available rooms are searched
    * @param string $typeId the id of the room's selected type to be searched (Optional Search Parameter)
    * @param string $CountofGuests room's count of guests search option (Optional Search Parameter)
    * @return Array an array of all rooms matched by the specified criteria
    */

    // Build an array containing all query parameters
    $parameters = [
      ':check_in_date' => $checkInDate->format(DateTime::ATOM),
      ':check_out_date' => $checkOutDate->format(DateTime::ATOM),
      ':price_min' => $priceMin,
      ':price_max' => $priceMax,
    ];

    // Check for the optional parameters and if any add them to the parameters array
    if(!empty($city) && $city != 'City')
    {
      $parameters[':city'] = $city;
    }
    if(!empty($typeId) && $typeId != 'Room')
    {
      $parameters[':type_id'] = $typeId;
    }
    if(!empty($CountofGuests) && $CountofGuests != 'Any')
    {
      $parameters[':count_of_guests'] = $CountofGuests;
    }

    // Build query based on which parameters are given
    $sql = 'SELECT * FROM room WHERE ';
    if(!empty($city) && $city != 'City')
    {
      $sql .= 'city = :city and ';
    }
    if(!empty($typeId) && $typeId != 'Room')
    {
      $sql .= 'type_id = :type_id AND ';
    }
    if(!empty($CountofGuests) && $CountofGuests != 'Any')
    {
      $sql .= 'count_of_guests = :count_of_guests AND ';
    }

    // Complete the SQL statement
    $sql .= 'price >= :price_min AND 
             price <= :price_max AND 
             room_id NOT IN (
               SELECT room_id 
               FROM booking 
               WHERE check_in_date <= :check_out_date AND 
                     check_out_date >= :check_in_date);';

    // Return all results - rooms
    return $this->fetchAll($sql, $parameters);
  }

  public function getRoomById($roomId)
  {
    /**
    * Fetches a room based on roomId
    * @param string $roomId check-in date to search for room availability
    * @return a row corresponding to the room
    */

    $parameters = [
      ':room_id' => $roomId
    ];

    $sql = 'SELECT * FROM room WHERE room_id = :room_id ';
    return $this->fetchAll($sql, $parameters);
  }

  public function getMinMaxRoomPrices()
  {
    /**
    * Fetches the minimum and the maximum prices for the rooms in the database
    * @param string $roomId check-in date to search for room availability
    * @return Array an array containing the minimum and maximum price of the rooms
    */

    $prices = $this->fetch('SELECT min(price) as min, max(price) as max FROM room', []);
    return $prices;
  }
}