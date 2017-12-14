<?php
class Residence{
  protected $R_residencyType;
  protected $R_nbRooms;
  protected $R_area;
  protected $R_inhabitants;
  protected $L_address;
  protected $T_name;
  protected $M_zipCode;
  protected $errors;


  public function __construct(array $data){
    $this->hydrate($data);
  }
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
  public function setR_residencyType($value){
    if(is_string($value)){
      $value = strtolower($value);
      if($value == 'maison' || $value == 'appartement' || $value == 'kot'){
        $this->R_residencyType = strtolower($value);
      }else{
        return 'Veuillez choisir un type de résidence.';
      }
    }else{
      return 'Format invalide';
    }
  }
  public function setR_nbRooms($value){
    $value = (int)$value;
    if(is_int($value)){
      if($value < 1){
        return 'Votre résidence doit au minimum posséder une pièce.';
      }else if($value > 30){
        return 'Votre résidence ne peut pas posséder plus de 30 pièces.';
      }else{
        $this->R_nbRooms = $value;
      }
    }else{
      return 'Format invalide';
    }
  }
  public function setR_area($value){
    $value = (int)$value;
    if(is_int($value)){
      if($value < 10){
        return 'La surface de votre résidence ne peut être inférieur à 10m².';
      }else if($value > 300){
        return 'La surface de votre résidence ne peut être supérieur à 300m².';
      }else{
        $this->R_area = $value;
      }
    }else{
      return 'Format invalide';
    }
  }
  public function setR_inhabitants($value){
    $value = (int)$value;
    if(is_int($value)){
      if($value < 1){
        return 'Au moins une personne doit vivre dans la résidence.';
      }else if($value > 20){
        return 'Le nombre d\'habitants est trop élevés pour qu\'il s\'agisse d\'une résidence';
      }else{
        $this->R_inhabitants = $value;
      }
    }else{
      return 'Format invalide';
    }
  }
  public function setL_address($value){
    if(is_string($value)){
      if(strlen($value) < 5){ //KOT = 3
        return 'Votre adresse est trop courte.';
      }else if(strlen($value) > 64){
        return 'Votre adresse ne peut pas faire plus de 64 caractères';
      }else{
        $this->L_address = $value;
      }
      // else if(preg_match("/^[a-zA-Z -]+$/",$value)){
      //   $this->L_address = $value;
      // }
      //Problème avec numéro de boite
      // else{
      //   return 'Format invalide';
      // }
    }else{
      return 'Format invalide';
    }
  }
  public function setT_name($value){
    if(is_string($value)){
      if(strlen($value) < 3){ //KOT = 3
        // return self::TOO_SHORT;
      }else if(strlen($value) > 16){
        // return self::TOO_LONG;
      }else if(preg_match("/^[a-zA-Z -]+$/",$value)){
        $this->T_name = $value;
      }else{
        return 'Format invalide';
      }
    }else{
      return 'Format invalide';
    }
  }
  public function setM_zipCode($value){
    $value = (int)$value;
    if(is_int($value)){
      if($value < 1000 || $value > 9999){
        return 'Le code postal belge est compris entre 1000 et 9999.';
      }else{
        $this->M_zipCode = $value;
      }
    }else{
      return 'Format invalide';
    }
  }
  public function setErrors($key,$error){
    if(is_string($key) && !empty($error)){
      $this->errors[$key] = $error;
    }
  }

  public function getR_residencyType(){
    return $this->R_residencyType;
  }
  public function getR_nbRooms(){
    return $this->R_nbRooms;
  }
  public function getR_area(){
    return $this->R_area;
  }
  public function getR_inhabitants(){
    return $this->R_inhabitants;
  }
  public function getL_address(){
    return $this->L_address;
  }
  public function getT_name(){
    return $this->T_name;
  }
  public function getM_zipCode(){
    return $this->M_zipCode;
  }
  public function getErrors(){
    return $this->errors;
  }
}
