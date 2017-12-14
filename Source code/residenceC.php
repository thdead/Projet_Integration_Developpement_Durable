<?php
require 'includes/session.php';
echo 'Welcome on this test page '.$_SESSION['user']['name'];
//get all info about residences
function getResidence(){
  require 'mysql/connect.php';
  $id = $_SESSION['user']['id'];
  $q = $pdo->prepare('SELECT *
      FROM Residency natural join Residency_Town natural join Town natural join Municipality
      WHERE R_id = (select R_id from Inhabit WHERE C_id= :id)');
  $q->bindValue(':id',$id);
  $q->execute();
  $resp = $q->fetchAll(PDO::FETCH_ASSOC); //allow to know number of residence too
  $nb_res = count($resp);
  var_dump($resp);
  return $resp;
}
getResidence();
