<?php
namespace Hotel;

use PDO;
use DateTime;
use Hotel\BaseService;

class RoomType extends BaseService
{
  /**
  * RoomType
  *
  * This class inherits BaseService and handles room types
  */
  public function getAllTypes()
  {
  	/**
  	* Fetches all room Types
  	*/
    return $this->fetchAll('SELECT * FROM room_type');
  }
}