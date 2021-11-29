<?php

namespace Support\Configuration;

use Hotel\BaseService;


class Configuration
{
  /**
  * Configuration
  *
  * Handles database Configuration
  */
  private $config;
  private static $instance;

  private function __construct()
  {
    /**
    * Constructor
    *
    * Loads the configuration file contents
    */

    // Load config file
    $filePath = __DIR__.'\..\..\..\config\config.json';
    $fileContents = file_get_contents($filePath);
    $this->config = json_decode($fileContents, true);
  }

  public static function getInstance()
  {
    /**
    * Returns the Configuration Object (Instance Accessor)
    */
    self::$instance = self::$instance ?: new Configuration();
    return self::$instance;
  }

  public function getConfig()
  {
    /**
    * Returns the configuration load from the file
    */
    return $this->config;
  }
}