<?php

namespace Hotel;

use PDO;
use Hotel\BaseService;
use Support\Configuration\Configuration;


class User extends BaseService
{
  /**
  * User
  *
  * This class inherits BaseService and handles Users
  */
  const TOKEN_KEY = 'asfdhkgjlr;ofijhgbfdklfsadf';
  private static $currentUserId;
  private $pdo;

  public function getByUserId($userId)
  {
    /**
    * Fetches a user with a specific id
    *
    * @param string $userId the id to used to get the user
    * @return a row representing the User with the specified id
    */

    // Build an array containing query's parameter
    $parameters = [
      ':user_id' => $userId
    ];

    // Execute query and return row - User
    return $this->fetch('SELECT * FROM users WHERE user_id = :user_id', $parameters);
  }

  public function getByEmail($email)
  {
    /**
    * Fetches a user with a specific email address
    *
    * @param string $email the email to used to get its user
    * @return a row representing the User with the specified email
    */

    // Build an array containing the query parameter
    $parameters = [
      ':email' => $email
    ];

    // Execute query and return the row - User
    return $this->fetch('SELECT * FROM users WHERE email = :email', $parameters);
  }

  public function getList()
  {
    /**
    * Fetches all users in the database
    *
    * @return Array an array containing all users
    */

    // Execute array and return all rows - Users
    return $this->fetchAll('SELECT * FROM users', [], PDO::FETCH_ASSOC);
  }

  public function insert($name, $email, $password)
  {
    /**
    * Inserts a new user in the database
    *
    * @param string $name the given name of the user
    * @param string $email the given email of the user
    * @param string $password the given password of the user
    * @return boolean the statement's execution status - True if the row is inserted successfully
    */

    // Prepare statement
    $statement = $this->getPdo()->prepare('INSERT INTO users (name, email, password)
                                           VALUES (:name, :email, :password)');

    // Hash given password
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Bind parameters manually
    $statement->bindParam(':name', $name, PDO::PARAM_STR);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->bindParam(':password', $passwordHash, PDO::PARAM_STR);

    // Execute statement, get and return the execution status
    $rows = $statement->execute();
    return $rows == 1;
  }


  public function verify($email, $password)
  {
    /**
    * Verifies by email and password
    *
    * @param string $email the email of the user to be used to verify the user
    * @param string $password the password of the user to be used to verify the user
    * @return boolean True if the user is verified successfully
    */

    // Step 1: Get the user that has the given email
    $user = $this->getByEmail($email);

    // Step 2: Verify password based on user's stored password hash and given password
    return password_verify($password, $user['password']);
  }

  public function generateToken($userId, $token = '')
  {
    /**
    * Generates unique token for the user based on user's id
    *
    * @param integer userId the id of the user for whom the token will be generated
    * @param string the CSRF token (Optional)
    * @return string the generated JWT token
    */

    // Create token payload
    $payload = [
      'user_id' => $userId,
      'csrf' => $token ?: md5(time()),
    ];

    // Encode token payload and create token signature
    $payloadEncoded = base64_encode(json_encode($payload));
    $signature = hash_hmac('sha256', $payloadEncoded, self:: TOKEN_KEY);

    // Return a string containing encoded payload and its signature separated by dot: <payload>.<signature>
  	return sprintf('%s.%s', $payloadEncoded, $signature);
  }

  public static function getTokenPayload($token)
  {
    /**
    * Decodes JWT token and gets the token payload
    *
    * @param string $token the token to be decoded
    * @return Array the payload of the token
    */

    // Separate payload and signature
    [$payloadEncoded] = explode('.', $token);

    // Get payload and return decoded payload
    return json_decode(base64_decode($payloadEncoded), true);
  }

  public function verifyToken($token)
  {
    /**
    * Verifies token
    *
    * @param string $token the token to be verified
    * @return boolean True if the token is valid
    */

    // Get payload
    $payload = $this->getTokenPayload($token);
    $userId = $payload['user_id'];
    $csrf = $payload['csrf'];

    // Generate the token and verify if this token and the given token are the same
    return $this->generateToken($userId, $csrf) == $token;
  }

  public static function verifyCsrf($csrf)
  {
    /**
    * Verifies CSRF token
    *
    * @param string $csrf the CSRF token to be verified
    * @return boolean True if CSRF token is valid
    */
    return self::getCsrf() == $csrf;
  }

  public static function getCsrf()
  {
    /**
    * Returns the CSRF generated token
    *
    * @return string the CSRF token
    */

    // Get the token from the cookie and then get the payload from the token
    $token = $_COOKIE['user_token'];
    $payload = self::getTokenPayload($token);

    // Return the CSRF token from the payload
    return $payload['csrf'];
  }

  public static function getCurrentUserId()
  {
    /**
    * Returns the id of the current User
    *
    * @return integer the id of the current User
    */
    return self::$currentUserId;
  }

  public static function setCurrentUserId($userId)
  {
    /**
    * Sets the current User by using the user's id
    */
    return self::$currentUserId = $userId;
  }
}