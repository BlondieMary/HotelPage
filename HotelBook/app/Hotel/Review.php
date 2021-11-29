<?php

namespace Hotel;

use PDO;
use Hotel\BaseService;


class Review extends BaseService
{
  /**
  * Review
  *
  * This class inherits BaseService and handles reviews of the rooms
  */

  public function insert($roomId, $userId, $rate, $comment)
  {
    /**
    * Adds a review for specific room by a specific user
    *
    * @param string $roomId the id of the room for which the review will be added by the user
    * @param string $userId the id of the user whose review for the room will be added
    * @param string $rate the rate that the user has given for the room
    * @param string $comment the comment that the user has made for the room
    */

    // Start a transction
    $this->getPdo()->beginTransaction();

    // Prepare parameters - build an array containing INSERT statement's parameters
    $parameters = [
      ':room_id' => $roomId,
      ':user_id' => $userId,
      ':rate' => $rate,
      ':comment' => $comment,
    ];

    // Execute INSERT statement
    $this->execute('INSERT INTO review (room_id, user_id, rate, comment)
                    VALUES (:room_id, :user_id, :rate, :comment)',
                   $parameters);

    // After inserting a new review, the average rate and rate count of the room must be recalculated and updated

    // Rebuild parameters for a new query to get room's average rate and rate count
    $parameters = [
      ':room_id' => $roomId
    ];

    // At first execute a query to get average rate and number of total given rates for the specified room
    $roomAverage = $this->fetch('SELECT avg(rate) as avg_reviews, count(*) as count
                                 FROM review
                                 WHERE room_id = :room_id',
                                $parameters);

    // Build a new array containg parameters for the UPDATE statement
    $parameters = [
      ':room_id' => $roomId,
      ':avg_reviews' => $roomAverage['avg_reviews'],
      ':count_reviews' => $roomAverage['count'],
    ];

    // Execute UPDATE statement to update room's average review rate and count as recalculated before
    $status = $this->execute('UPDATE room
                              SET avg_reviews = :avg_reviews, count_reviews = :count_reviews
                              WHERE room_id = :room_id',
                             $parameters);
    // Commit transction
    $this->getPdo()->commit();
  }

  public function getReviewsByRoom($roomId)
  {
    /**
    * Fetches all review's made for specific room
    *
    * @param string $roomId the id of the room for which all reviews will be fetched
    * @return Array an array of all reviews made for the room
    */

    // Build an array containing all query's parameters
    $parameters = [
      ':room_id' => $roomId
    ];

    // Execute query and return all rows - info for the reviews - ordered by newest to oldest
    return $this->fetchAll('SELECT review.*, users.name as user_name
                            FROM review INNER JOIN users ON review.user_id = users.user_id
                            WHERE room_id = :room_id
                            ORDER BY created_time DESC',
                           $parameters);
  }

  public function getListByUser($userId)
  {
    /**
    * Fetches all review's made by a specific user
    *
    * @param string $userId the id of the user for whom all reviews made will be fetched
    * @return Array an array of all reviews made by the user
    */

    // Build an array containing all query's parameter
    $parameters = [
      ':user_id' => $userId,
    ];

    // Execute query and return all rows - info for the reviews made by the user
    return  $this->fetchAll('SELECT review.*, room.name
                             FROM review INNER JOIN room ON review.room_id = room.room_id
                             WHERE user_id = :user_id',
                            $parameters);
  }
}