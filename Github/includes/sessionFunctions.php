<?php
require 'functions.php';
# FUNCTION FOR SESSIONS

function sessionCheck(){
// if(sessionExist() && userExist()){
  if(isConnected()){
    if(isset($_SESSION['post'])){
      unset($_SESSION['post']);
    }
    if(isset($_SESSION['errors'])){
      unset($_SESSION['errors']);
    }
    if(isset($_SESSION['visitor'])){
      unset($_SESSION['visitor']);
    }
  }else{
    if(!isset($_SESSION['visitor'])){
      $_SESSION['visitor'] = 1;
    }
  }
}
//Verify if the user is connected by checking if
//connected == 1, user is connected
function isConnected(){
  if($_SESSION['user']['connected'] == 1 && isValid()){
    return true;
  }
  return false;
}
//Verify if the user is valid by getting the info in the database.
function isValid(){
  if(isset($_SESSION['user']['valid'])){
    if($_SESSION['user']['valid'] == 1){
      return true;
    }
  }else{
    require_once 'mysql/connect.php';
    $userManager = new UserManager($pdo);
    $user = new User($_SESSION['user']);
    $valid = $userManager->isValidUser($user);
    $pdo=null;
    $_SESSION['user']['valid'] = $valid;
    if(!empty($valid)){
      return true;
    }
  }
  return false;
}
function getcurrentPage(){
  $full_page = explode('.',basename($_SERVER['PHP_SELF']));
  $ext = '.'.$full_page[1];
  $page = str_replace('/','',$full_page[0]);
  return $page;
}
function checkAccess(){
  $currentPage = getcurrentPage();
  $restricted = ['index','login','registration_form','verify'];
  //ne peut accéder à verify que si il n'est pas encore valide!
  if(isConnected()){
    if(in_array($currentPage,$restricted)){
      header('Location: dashboard.php');
    }
  }else{
    $access = $restricted;
    if(!in_array($currentPage,$access)){
      header('Location: index.php');
    }
  }
}
