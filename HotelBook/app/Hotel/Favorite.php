<?php

namespace Hotel;


use Hotel\BaseService;


class Favorite extends BaseService
{
  /**
  * Favorite
  *
  * This class inherits BaseService and handles favorites of the user
  */

  public function isFavorite($roomId, $userId)
  {
    /**
    * Checks if a specific room is favorited by a specific user
    *
    * @param string $roomId the id of the room to be checked whether it is favorited or not
    * @param string $userId the id of the user for whom to check whether the room is favorited or not
    * @return boolean True if the specified room is favorited by the specified user
    */

    // Build an array containing query parameters
    $parameters = [
      ':room_id' => $roomId,
      ':user_id' => $userId,
    ];

    // Executed the query and fetches a row if any 
    $favorite = $this->fetch('SELECT * FROM favorite WHERE room_id = :room_id AND user_id = :user_id', $parameters);

    // Return True if there is at least 1 row (that means the user has favorited the room)
    return !empty($favorite);
  }

  public function addFavorite($roomId, $userId)
  {
    /**
    * Adds a specific room as favorite for a specific user
    *
    * @param string $roomId the id of the room which will be added as favorite for the user
    * @param string $userId the id of the user for whom the room will be added as favorite
    * @return boolean execution status - True if the room is added as favorite for the user
    */

    // Build an array containing all INSERT statement's parameters
    $parameters = [
      ':room_id' => $roomId,
      ':user_id' => $userId,
    ];

    // Execute INSERT statement, ignoring any error that might occur
    $rows = $this->execute('INSERT IGNORE INTO favorite (room_id, user_id)
                            VALUES (:room_id, :user_id)',
                           $parameters);

    // Return statement's executeing status
    return $rows == 1;
  }

  public function removeFavorite($roomId, $userId)
  {
    /**
    * Removes a specific room as favorite for a specific user
    *
    * @param string $roomId the id of the room which will be removed as favorite for the user
    * @param string $userId the id of the user for whom the room will be removed as favorite
    * @return boolean execution status - True if the room is removed as favorite for the user
    */

    // Build an array containing DELETE statement's parameters
    $parameters = [
      ':room_id' => $roomId,
      ':user_id' => $userId,
    ];

    // Execute DELETE statement to remove the favorited room for the user
    $rows = $this->execute('DELETE FROM favorite WHERE room_id = :room_id AND user_id = :user_id', $parameters);

    // Return DELETE statement's execution status
    return $rows == 1;
  }

  public function getListByUser($userId)
  {
    /**
    * Fetched all favorite rooms for a specific user
    *
    * @param string $userId the id of the user for whom all favorite rooms will be fetched
    * @return Array an array containing user's all favorite rooms
    */

    // Build an array containing query's parameters
    $parameters = [
      ':user_id' => $userId,
    ];

    // Execute query and return all rows - info for favorited rooms
    return $this->fetchAll('SELECT favorite.*, room.name
                            FROM favorite
                            INNER JOIN room ON favorite.room_id = room.room_id
                            WHERE user_id = :user_id',
                           $parameters);
  }
}