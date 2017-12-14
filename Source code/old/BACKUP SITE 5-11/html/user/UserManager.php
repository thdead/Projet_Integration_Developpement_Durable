<?php
//CREATE READ UPDATE DELETE - CRUD
class UserManager{
  private $_db;
  public function __construct(PDO $db){
    $this->setDb($db);
  }
  public function add(User $user){
    $q = $this->_db->prepare('INSERT INTO Customer
      (C_password,C_firstname,C_lastname,C_birthdate,C_gender,C_email,C_phoneNumber) VALUES
      (:password,:firstName,:lastName,:birthDate,:gender,:email,:phoneNumber)');
      $q->bindValue(':password',$user->getPassword());
      $q->bindValue(':firstName',$user->getFirstName());
      $q->bindValue(':lastName',$user->getLastName());
      $q->bindValue(':birthDate',$user->getBirthDate());
      $q->bindValue(':gender',$user->getGender());
      $q->bindValue(':email',$user->getEmail());
      $q->bindValue(':phoneNumber',$user->getPhoneNumber());
      $q->execute();
  }
  // public function delete(User $user){}
  // public function update(User $user){}
  // public function list(){}
  public function get($info){
    if($info){
      if(is_int($info)){
        $q = $this->_db->prepare('SELECT * FROM Customer WHERE C_email = :id');
        $q->bindValue(":id",$info,PDO::PARAM_INT);
        $q->execute();
      }
      else if(filter_var($info, FILTER_VALIDATE_EMAIL)){
        $q = $this->_db->prepare('SELECT * FROM Customer WHERE C_email = :email');
        $q->bindValue(":email",$info);
        $q->execute();
      }
      return $q->fetchAll(PDO::FETCH_COLUMN,0);
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
