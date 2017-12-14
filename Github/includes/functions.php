<?php
function handleErrors($error,$input){
  if(!empty($error)){
    switch($error){
      case User::TOO_SHORT:
        $errors = ' est trop court.';
        break;
      case User::TOO_LONG:
        $errors = 'est trop long.';
        break;
      case User::INVALID_FORMAT:
        $errors = ' est invalide.';
        break;
      default: $errors = ' est incorrect.';
    }
    return $errors;
  }
}
function classLoader($class){
  require 'user/'.$class.'.php';
}
spl_autoload_register('classLoader');

//load uesefull data of specified user for sessions.
// function loadUserData(){
//
// }
