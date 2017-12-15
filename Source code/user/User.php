<?php
/**
 * A user is recognized by his email and can do several actions.
 */
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
  /**
  * This constructor allows
  */
  public function __construct(array $userData){
    $this->hydrate($userData);
  }
  /**
  * Reads array $data, transform each key X into a $method.
  * If the method 'setX' exist, then the value of X is set to the attribute
  * that match the key.
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
  /**
   * Set the errors that can occurs in set* functions.
   * @return error, an array containing the name of the attribute
   * aas key and the error code as value.
   */
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
  /**
   * Set the id of  the users, the id must be unique and gth 0.
   * @param value, the id of the user,
   * @return TOO_SHORT if too short value is given.
   */
  public function setId($value){
    $value = (int) $value;
    if($value > 0){
      $this->id = $value;
    }else{
      return self::TOO_SHORT;
    }
  }
  /**
   * Set the first name of the user.
   * @param value, the first name,
   * @return TOO_SHORT if the value is too short,
   * @return TOO_LONG if the value is too long,
   * @return INVALID_FORMAT if the value is invalid.
   */
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
  /**
   * Set the last name of the user.
   * @param value, the last name,
   * @return TOO_SHORT if the value is too short,
   * @return TOO_LONG if the value is too long,
   * @return INVALID_FORMAT if the value is invalid.
   */
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
  /**
   * Set the crypted password of the user.
   * @param value, the passwor,
   * @return TOO_SHORT if the value is too short
   * @return TOO_LONG if the value is too long
   */
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
  /**
   * Verify if the password in plaintext matches the hashed password.
   * @param plaintext, the clear text password,
   * @param hash, the hashed password,
   * @return true, if the password match,
   * @return false, if the password doesn't match.
   */
  public function passwordMatch($plaintext,$hash){
    $plaintext = (string) $plaintext;
      return password_verify($plaintext,$hash);
  }
  /**
   * Verify if the token gave matches the instance token.
   * @param token, the token given by user,
   * @return true, if the token match,
   * @return false, if the token doesn't match.
   */
  public function tokenMatch($token){
    if(!empty($token)){
      return (bool)($token === $this->getToken());
    }
  }
  /**
   * Set the birthdate of the user, the user must be 16.
   * @param value, the birtdate of the user,
   * @return TOO_LONG, if the user is older than 100,
   * @return TOO_SHORT, if the user is yunger than 16
   * @return TOO_INVALID, if value is in invalid format
   */
  public function setBirthDate($value){
    $date = explode('-',$value);
    if(count($date) == 3){
      if(checkdate($date[1],$date[2],$date[0])){
        if($date[0] < (date('Y')-16)){
          if((date('Y')-100) <= $date[0]){
            //YYYY-MM-DD
            $correctDate = $date[0].'/'.$date[1].'/'.$date[2];
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
  /**
   * Set the gender of the user, eitheir male, female or other.
   * @param value, the gender,
   * @return INVALID_FORMAT, if the gender is not matching M, W or O.
   */
  public function setGender($value){
    $value = strtoupper($value);
    if($value == 'M' || $value == 'W' || $value == 'O'){
      $this->gender = $value;
    }else{
      return self::INVALID_FORMAT;
    }
  }
  /**
   * Set the phone number of the user.
   * @param value, the phone number,
   * @return INVALID_FORMAT, if the phone number is invalid.
   */
  public function setPhoneNumber($value){
    if(!empty($value)){
      if(preg_match('/^0[0-9]{3}[0-9]{6}$/', $value)){
        $this->phoneNumber = $value;
      }else{
        return self::INVALID_FORMAT;
      }
    }
  }
  /**
   * Set the user's email that must be a valid email address.
   * @param value, the email address,
   * @return INVALID_FORMAT, if the email is not valid.
   */
  public function setEmail($value){
    if(filter_var($value, FILTER_VALIDATE_EMAIL)){
      $this->email = strtolower($value);
    }else{
      return self::INVALID_FORMAT;
    }
  }
  /**
   * Set the user's token. This token is generated outside & given in param.
   * @param value, the generated token,
   * @return INVALID_FORMAT, if the token is not 32 characters long.
   */
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
