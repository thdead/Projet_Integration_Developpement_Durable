<?php
session_start();
require 'sessionFunctions.php';
sessionCheck();
checkAccess();
if(isConnected()){
  //ACTION TO DO IF USER
  //IS USER VALID?
  //IF USER IS NOT VALID, RESTRICTED ACCESS.

  //DISCONNECTION
  if(isset($_POST['disconnect'])){
    unset($_SESSION);
    session_destroy();
    header('Location: index.php');
  }
}else{
  //ACTION TO DO IF VISITOR
}
