<?php
//CREATE READ UPDATE DELETE - CRUD
class UserManager{
  private $_db;
  public function __construct(PDO $db){
    $this->setDb($db);
  }
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
  // public function delete(User $user){}
  // public function update(User $user){}
  // public function list(){}
  public function get($info){
    if($info){
      if(is_int($info)){
        // $q = $this->_db->prepare('SELECT C_id, C_password, C_firstname, C_lastname, C_birthdate, C_gender, C_email, C_phoneNumber, C_valid  FROM Customer WHERE C_email = :id');
        $q = $this->_db->prepare('SELECT C_firstname, C_lastname  FROM Customer WHERE C_id = :id');
        $q->bindValue(":id",$info,PDO::PARAM_INT);
        $q->execute();
        //C_id,C_password,C_firstname,C_lastname,C_birthdate,C_gender,C_email,C_phoneNumber,P_id,C_token,C_valid

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
  public function getToken($user){
      $q = $this->_db->prepare('SELECT C_token FROM Customer WHERE C_email = :email');
      $q->bindValue(":email",$user->getEmail());
      $q->execute();
      return $q->fetchAll(PDO::FETCH_COLUMN,0)[0];
  }
  public function validateUser($user){
    $q = $this->_db->prepare("UPDATE Customer SET C_valid = 1, C_token = 0 WHERE C_email = :email");
    $q->bindValue(":email",$user->getEmail());
    $q->execute();
  }
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
  public function exist(User $user){
    if($this->get($user->getEmail())){
      return true;
    }
    return false;
  }
  public function setDb(PDO $db){
    $this->_db = $db;
  }
}
