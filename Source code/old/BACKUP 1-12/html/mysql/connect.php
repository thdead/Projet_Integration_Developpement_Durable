<?php
//CONNECTION TO database
$db_user = 'root';
$db_password = 'integ';
$db = 'emonitor';
$db_host = 'localhost';
try{
  $pdo = new PDO('mysql:host='.$db_host.';dbname='.$db,$db_user,$db_password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}catch(PDOException $e){
  print "erreur: ".$e->getMessage()."<br/>";
  die();
}
