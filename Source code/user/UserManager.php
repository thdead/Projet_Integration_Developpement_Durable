<?php
/**
 * This class is the database handler for User instances.
 * It respect the CRUD (Create, Read, Update, Delete) method.
 */
class UserManager{
  private $_db;
  public function __construct(PDO $db){
    $this->setDb($db);
  }
  /**
   * Add a user to database.
   * @param user, the user object.
   */
  public function add(User $user){
    $q = $this->_db->prepare('INSERT INTO Customer
      (C_password,C_firstname,C_lastname,C_birthdate,C_gender,C_email,C_phoneNumber,C_token) VALUES
      (:password,:firstName,:lastName,:birthDate,:gender,:email,:phoneNumber,:token)');
      $q->bindValue(':password',$user->getPassword());
      $q->bindValue(':firstName',$user->getFirstName());
      $q->bindValue(':lastName',$user->getLastName());
      $q->bindValue(':birthDate',$user->getBirthDate());
      $q->bindValue(':gender',$user->getGender());
      $q->bindValue(':email',$user->getEmail());
      $q->bindValue(':phoneNumber',$user->getPhoneNumber());
      $q->bindValue(':token',$user->getToken());
      $q->execute();
  }
  /**
   * Get information about users with a search condition.
   * @param info, the search condition,
   * @return null, if an error occurs in the query,
   * @return array containing information.
   * 
   */
  public function get($info){
    if($info){
      if(is_int($info)){
        $q = $this->_db->prepare('SELECT C_firstname, C_lastname  FROM Customer WHERE C_id = :id');
        $q->bindValue(":id",$info,PDO::PARAM_INT);
        $q->execute();
      }
      else if(filter_var($info, FILTER_VALIDATE_EMAIL)){
        $q = $this->_db->prepare('SELECT C_id FROM Customer WHERE C_email = :email');
        $q->bindValue(":email",$info);
        $q->execute();
      }
      return $q->fetch(PDO::FETCH_ASSOC);
    }
    return '';
  }
  /**
   * Get the hashed password of a specified user, if the user
   * object has a password.
   * @param user, the user,
   * @return null, if the user doesn't have any password.
   * @return password, the hashed password
   */
  public function getPassword($user){
    $pwd = $user->getPassword();
    if(!empty($pwd)){
      $q = $this->_db->prepare('SELECT C_password FROM Customer WHERE C_email = :email');
      $q->bindValue(":email",$user->getEmail());
      $q->execute();
      return $q->fetchAll(PDO::FETCH_COLUMN,0)[0];
    }
    return '';
  }
  /**
   * Get the user's token stored in database.
   * @param user, the user,
   * @return token, if the token is found for this user or 
   * @return null if not.  
   */
  public function getToken($user){
      $q = $this->_db->prepare('SELECT C_token FROM Customer WHERE C_email = :email');
      $q->bindValue(":email",$user->getEmail());
      $q->execute();
      return $q->fetchAll(PDO::FETCH_COLUMN,0)[0];
  }
  /**
   * Validate a user.
   * @param user, the user to validate.
   */
  public function validateUser($user){
    $q = $this->_db->prepare("UPDATE Customer SET C_valid = 1, C_token = 0 WHERE C_email = :email");
    $q->bindValue(":email",$user->getEmail());
    $q->execute();
  }
  /**
   * Verify if a user is valid or not.
   * @param user, the user to verify,
   * @return true, if the user is valid,
   * @return false, if not.
   */
  public function isValidUser($user){
    if(!empty($user->getEmail())){
      $q = $this->_db->prepare("SELECT C_id FROM Customer WHERE C_email = :email
        AND C_valid = 1 AND C_token = 0");
      $q->bindValue(":email",$user->getEmail());
      $q->execute();
    }else if(!empty($user->getId())){
      $q = $this->_db->prepare("SELECT C_id FROM Customer WHERE C_id = :id
        AND C_valid = 1 AND C_token = 0");
      $q->bindValue(":id",$user->getId());
      $q->execute();
    }
    return (bool)$q->fetchAll();
  }
  /**
   * Verify if the user's email of the user given exist in database.
   * @param user, the user object to verify,
   * @return true, if the email, so the user exist,
   * @return false, if not.
   */
  public function exist(User $user){
    if($this->get($user->getEmail())){
      return true;
    }
    return false;
  }
  /**
   * Initialise the database by setting the db attribut.
   * @param db, the PDO object.
   */
  public function setDb(PDO $db){
    $this->_db = $db;
  }
}
