<?php
//IN THIS CLASS YOU'LL FIND ALL USERS FUNCTIONS AND UTILITIES.
//USERMANAGER IS USED FOR DATABASE CONNECTION
class User{
  protected $id;
  protected $firstName;
  protected $password;
  protected $lastName;
  protected $birthDate;
  protected $email;
  protected $gender;
  protected $phoneNumber;
  protected $token;
  protected $errors;
  const TOO_SHORT = 1;
  const TOO_LONG = 2;
  const INVALID_FORMAT = 3;
  /*
  This constructor allows
  */
  public function __construct(array $userData){
    $this->hydrate($userData);
  }
  /*
  Reads array $data, transform each key X into a $method.
  If the method 'setX' exist, then the value of X is set to the attribute
  that match the key.
  */
  public function hydrate(array $data){
    $errors = [];
      foreach($data as $key=>$value){
        $method = 'set'.ucfirst($key);
        if(method_exists($this,$method)){
          $error = $this->$method($value);
          if(!empty($error)){
            $this->setErrors($key,$error);
          }
        }
      }
  }
  public function setErrors($key,$error){
    if(is_string($key) && (($error) == self::INVALID_FORMAT ||
     ($error) == self::TOO_SHORT || ($error) == self::TOO_LONG)){
      $this->errors[$key] = $error;
    }
  }
  public function getErrors(){
    return $this->errors;
  }
  #SETTERS
  public function setId($value){
    $value = (int) $value;
    if($value > 0){
      $this->id = $value;
    }else{
      return self::TOO_SHORT;
    }
  }
  public function setFirstName($value){
    if(is_string($value)){
      if(strlen($value) < 2)
      {
        return self::TOO_SHORT;
      }
      else if(strlen($value) > 32)
      {
        return self::TOO_LONG;
      }
      else if(preg_match("/^[a-zA-Z -]+$/",$value))
      {
        $this->firstName = strtolower($value);
      }else{
        return self::INVALID_FORMAT;
      }
    }else{
      return self::INVALID_FORMAT;
    }
  }
  public function setLastName($value){
    if(is_string($value)){
      if(strlen($value) < 2)
      {
        return self::TOO_SHORT;
      }
      else if(strlen($value) > 32)
      {
        return self::TOO_LONG;
      }
      else if(preg_match('/^[a-zA-Z ]+$/',$value))
      {
        $this->lastName = strtolower($value);
      }else{
        return self::INVALID_FORMAT;
      }
    }else{
      return self::INVALID_FORMAT;
    }
  }
  public function setPassword($value){
    $pwd = (string) $value;
    if(strlen($pwd) < 8){
      return self::TOO_SHORT;
    }else if(strlen($pwd) > 20){
      return self::TOO_LONG;
    }else{
      $this->password = password_hash($pwd, PASSWORD_BCRYPT);
    }
  }
  public function passwordMatch($plaintext,$hash){
    $plaintext = (string) $plaintext;
      return password_verify($plaintext,$hash);
  }
  public function tokenMatch($token){
    if(!empty($token)){
      return (bool)($token === $this->getToken());
    }
  }
  public function setBirthDate($value){
    $date = explode('-',$value);
    if(count($date) == 3){
      if(checkdate($date[1],$date[2],$date[0])){
        if($date[0] < (date('Y')-16)){
          if((date('Y')-100) <= $date[0]){
            $correctDate = $date[0].'/'.$date[1].'/'.$date[2]; //YYYY-MM-DD
            $this->birthDate = $correctDate;
          }else{
            return self::TOO_LONG;
          }
        }else{
          return self::TOO_SHORT;
        }
      }else{
        return self::INVALID_FORMAT;
      }
    }else{
      return self::INVALID_FORMAT;
    }
  }
  public function setGender($value){
    $value = strtoupper($value);
    if($value == 'M' || $value == 'W' || $value == 'O'){
      $this->gender = $value;
    }else{
      return self::INVALID_FORMAT;
    }
  }
  public function setPhoneNumber($value){
    if(!empty($value)){
      if(preg_match('/^0[0-9]{3}[0-9]{6}$/', $value)){
        $this->phoneNumber = $value;
      }else{
        return self::INVALID_FORMAT;
      }
    }
  }
  public function setEmail($value){
    if(filter_var($value, FILTER_VALIDATE_EMAIL)){
      $this->email = strtolower($value);
    }else{
      return self::INVALID_FORMAT;
    }
  }
  public function setToken($value){
    if(strlen($value) == 32){
      $this->token = $value;
    }else{
      return self::INVALID_FORMAT;
    }
  }
  public function getToken(){
    return $this->token;
  }
  #GETTERS
  public function getId(){
    return $this->id;
  }
  public function getFirstName(){
    return $this->firstName;
  }
  public function getLastName(){
    return $this->lastName;
  }
  public function getPassword(){
    return $this->password;
  }
  public function getBirthDate(){
    return $this->birthDate;
  }
  public function getGender(){
    return $this->gender;
  }
  public function getEmail(){
    return $this->email;
  }
  public function getPhoneNumber(){
    return $this->phoneNumber;
  }
}
