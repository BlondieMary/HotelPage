<?php

namespace Hotel;

use \DateTime;
use Hotel\BaseService;


class Booking extends BaseService
{
  /**
  * Booking
  *
  * This class inherits BaseService and handles bookings in/out of the database
  */

  public function insert($roomId, $userId, $checkInDate, $checkOutDate)
  {
    /**
    * Inserts a booking for specific room, user and dates in the database using prepared statement
    *
    * @param string $roomId the id of the room to be booked
    * @param string $userId the id of the user who makes the booking
    * @param string $checkInDate the selected check-in date for the booking
    * @param string $checkOutDate the selected check-out date for the booking
    */

    // Initiate a transaction
    $this->getPdo()->beginTransaction();

    // Build an array containing the parameters to execute a query to fetch information
    // for the room selected
    $parameters = [
      ':room_id' => $roomId,
    ];

    // Execute query to fetch the information for the selected room and get the price of the room
    $roomInfo = $this->fetch('SELECT * FROM room WHERE  room_id = :room_id', $parameters);
    $price = $roomInfo['price'];

    // Calculate final price based on room's price and selected dates's difference
    $checkInDateTime = new DateTime($checkInDate);
    $checkOutDateTime = new DateTime($checkOutDate);
    $daysDiff = $checkOutDateTime->diff($checkInDateTime)->days;
    $totalPrice = $price * $daysDiff;

    // Build a new array containing all data for the booking - INSERT statement
    $parameters = [
      ':room_id' => $roomId,
      ':user_id' => $userId,
      ':total_price' => $totalPrice,
      ':check_in_date' => $checkInDateTime->format(DateTime::ATOM),
      ':check_out_date' => $checkOutDateTime->format(DateTime::ATOM),
    ];

    // Execute INSERT statement using BaseService's method
    $this->execute('INSERT INTO booking (room_id, user_id, total_price, check_in_date, check_out_date)
                    VALUES (:room_id, :user_id, :total_price, :check_in_date, :check_out_date)',
                   $parameters);

    // Commit the transaction if a error hasn't occured
    $this->getPdo()->commit();
  }

  public function isBooked($roomId, $checkInDate, $checkOutDate)
  {
    /**
    * Checks if there is a booking for a specific room between two given dates
    *
    * @param string $roomId the id of the room to be check whether it is booked or not
    * @param string $checkInDate a check-in date to be checked for the booking
    * @param string $checkOutDate a check-out date to be checked for the booking
    * @return boolean True if the specified room is booked within the specified dates
    */

    // Turn string dates into DateTime objects
    $checkInDatetime = new DateTime($checkInDate);
    $checkOutDateTime = new DateTime($checkOutDate);

    // Build an array of the query parameters
    $parameters = [
      ':room_id' => $roomId,
      ':check_in_date' => $checkInDatetime->format(DateTime::ATOM),
      ':check_out_date' => $checkOutDateTime->format(DateTime::ATOM),
    ];

    // Execute query and fetch all rows if any
    $rows = $this->fetchAll('SELECT room_id
                             FROM booking
                             WHERE room_id = :room_id AND
                                   check_in_date <= :check_out_date AND
                                   check_out_date >= :check_in_date',
                            $parameters);

    // Return True if there is at least 1 row (1 Booking) by counting the rows of the results
    return count($rows) > 0;
  }

  public function getListByUser($userId)
  {
    /**
    * Fetches all bookings for a specific user
    *
    * @param string $userId the id of the user for who will fetch all rows
    * @return Array an array of all bookings for the specified user
    */

    // Build an array for the query paramemeters
    $parameters = [
      ':user_id' => $userId,
    ];

    // Execute query and return all fetched rows
    return  $this->fetchAll('SELECT booking.*, room.*, room.photo_url, room_type.name as room_type
                             FROM booking
                             INNER JOIN room ON booking.room_id = room.room_id
                             INNER JOIN room_type ON room.type_id = room_type.type_id
                             WHERE user_id = :user_id',
                            $parameters);
  }

  public function getBookedUser($roomId, $checkInDate, $checkOutDate)
  {
    /**
    * Fetches the id of the user who has booked a specific room between specific dates
    *
    * @param string $roomId the id of the room for which booking will be checked
    * @param string $checkInDate a check-in date to be checked for the booking
    * @param string $checkOutDate a check-out date to be checked for the booking
    * @return a row containing the id of the user who did the booking
    */

    // Turn string dates into Datetime objects
    $checkInDatetime = new DateTime($checkInDate);
    $checkOutDateTime = new DateTime($checkOutDate);

    // Build an array containing the query parameters
    $parameters = [
      ':room_id' => $roomId,
      ':check_in_date' => $checkInDatetime->format(DateTime::ATOM),
      ':check_out_date' => $checkOutDateTime->format(DateTime::ATOM),
    ];

    // Execute query
    $rows = $this->fetch('SELECT user_id
                          FROM booking
                          WHERE room_id = :room_id AND
                                check_in_date <= :check_out_date AND
                                check_out_date >= :check_in_date',
                         $parameters);

    // Return all fetched rows
    return $rows;
  }

  public function remove($roomId, $userId, $checkInDate, $checkOutDate)
  {
    /**
    * Removes a booking for a specific user, room and booking dates (Booking Cancellation)
    *
    * @param string $userId the id of the room for whom booking will be removed
    * @param string $roomId the id of the room for which booking will be removed
    * @param string $checkInDate a check-in date for the booking to be removed
    * @param string $checkOutDate a check-out date for the booking to be removed
    */

    // Initiate a transaction
    $this->getPdo()->beginTransaction();

    // Turn string dates into Datetime objects
    $checkInDateTime = new DateTime($checkInDate);
    $checkOutDateTime = new DateTime($checkOutDate);

    // Build an array containing DELETE statement's parameters
    $parameters = [
      ':room_id' => $roomId,
      ':user_id' => $userId,
      ':check_in_date' => $checkInDateTime->format(DateTime::ATOM),
      ':check_out_date' => $checkOutDateTime->format(DateTime::ATOM),
    ];

    // Execute the DELETE statement
    $this->execute('DELETE FROM booking
                    WHERE room_id = :room_id AND
                          user_id = :user_id AND
                          check_in_date = :check_in_date AND
                          check_out_date = :check_out_date',
                   $parameters);

    // Commit the transaction if an error hasn't occured at this point
    $this->getPdo()->commit();
  }
}