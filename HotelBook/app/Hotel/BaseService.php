<?php

namespace Hotel;
use Exception;
use PDO;
use Support\Configuration\Configuration;


class BaseService
{
  private static $pdo;

  public function __construct()
  {
    /**
    * BaseService Constructor
    */
    
    $this->initializePdo();
  }

  protected function initializePdo()
  {
    /**
    * Initializes PDO to connect to the database
    */

    // Check  if pdo is already initializePdo
    if (!empty(self::$pdo))
    {
      return;
    }
    // Load database configuration
    $config = Configuration::getInstance();
    $databaseConfig = $config->getConfig()['database'];

    // Connect to database
    try
    {
      self::$pdo = new PDO(sprintf('mysql:host=%s;dbname=%s;charset=UTF8', $databaseConfig['host'],
      $databaseConfig['dbname']), $databaseConfig['username'],
      $databaseConfig['password'], [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"]);
    }
    catch(\PDOException $ex)
    {
      throw new \Exception(sprintf("Could not connect to database. Error: %s", $ex->getMessage()));
    }
  }

  protected function fetchAll($sql, $parameters = [], $type = PDO::FETCH_ASSOC)
  {
    /**
    * Executes a query and fetches all matched rows using prepared statement
    *
    *  @param string $sql the SQL statement to be turned into prepared statement
    *  @param Array $parameters  the parameters to be binded to the prepared statement
    *  @param PDO::FETCH_* $type the return mode of the PDO
    *  @return Array an array of database rows
    */

    // Prepare statement
    $statement = $this->getPdo()->prepare($sql);

    // Bind parameters one by one
    foreach ($parameters as $key => &$value)
    {
      $statement ->bindParam($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }

    // Execute statement and check status. Throw exception if an error occured
    $status = $statement->execute();
    if (!$status)
    {
      throw new \Exception($statement->errorInfo()[2]);
    }

    // Return all rows matched by the query
    return $statement->fetchAll($type);
  }

  protected function fetch($sql, $parameters = [], $type = PDO::FETCH_ASSOC)
  {
    /**
    * Executes a query and fetches a row using prepared statement
    *
    *  @param string $sql the SQL statement to be turned into prepared statement
    *  @param Array $parameters  the parameters to be binded to the prepared statement
    *  @param PDO::FETCH_* $type the return mode of the PDO
    *  @return  one database row
    */

    // Prepare statement
    $statement = $this->getPdo()->prepare($sql);

    // Bind parameters
    foreach ($parameters as $key => &$value)
    {
      $statement->bindParam($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }

    // Execute statement and check status. Throw exception if an error occured
    $status = $statement->execute();
    if (!$status)
    {
      throw new \Exception($statement->errorInfo()[2]);
    }

    // Fetch a row as indicated by mode - value of $type
    return $statement->fetch($type);
  }

  protected function execute($sql, $parameters)
  {
    /**
    * Executes a query and returns the execution status using prepared statement
    *
    *  @param string $sql the SQL statement to be turned into prepared statement
    *  @param Array $parameters  the parameters to be binded to the prepared statement
    *  @return integer the status of SQL statement execution
    */

    // Prepare the statement
    $statement = $this->getPdo()->prepare($sql);

    // Bind parameters
    foreach ($parameters as $key => &$value)
    {
      $statement ->bindParam($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }

    // Execute prepared statement for given parameters and check status. Throw exception if an error occured
    $status = $statement->execute();
    if (!$status)
    {
      throw new \Exception($statement->errorInfo()[2]);
    }

    // Return the status
    return $status;
  }

  protected function getPdo()
  {
    /**
    * PDO Object Accessor
    * @return PDO the PDO Object of the class
    */
    return self::$pdo;
  }
}