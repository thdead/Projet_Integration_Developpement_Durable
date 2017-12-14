<?php
//Functions for residence handling
//get ALL the information about a residence
function getResidenceDetails($rid){
  // require 'mysql/connect.php';
  require $_SERVER['DOCUMENT_ROOT'].'/mysql/connect.php';
  $q = $pdo->prepare('SELECT M_zipCode, R_area, R_inhabitants, R_nbRooms, R_residencyType, L_address, T_name, M_name
      FROM Residency natural join Residency_Town natural join Town natural join Municipality
      WHERE R_id = :rid');
  $q->bindValue(':rid',$rid);
  $q->execute();
  $resp = $q->fetchAll(PDO::FETCH_ASSOC); //allow to know number of residence too
  $pdo=null;
  return $resp;
}
//get only couple info about residences
function getResidenciesInfo(){
  require 'mysql/connect.php';
  $id = $_SESSION['user']['id'];
  $q = $pdo->prepare('SELECT L_address, T_name, M_name, R_id
      FROM Customer natural join Inhabit natural join Residency natural join Residency_Town
      natural join Town natural join Municipality
      WHERE C_id = :id  AND R_actif = 1');
  $q->bindValue(':id',$id);
  $q->execute();
  $resp = $q->fetchAll(PDO::FETCH_ASSOC); //allow to know number of residence too
  $pdo=null;
  return $resp;
}
function getR_idByC_id($id){
  if($id){
    require $_SERVER['DOCUMENT_ROOT'].'/mysql/connect.php';
    $req = $pdo->prepare('SELECT R_id FROM Inhabit WHERE C_id = :id');
    $req->bindValue(':id',$id);
    $executed = $req->execute();
    $pdo=null;
    $data = $req->fetchAll(PDO::FETCH_NUM);
    if($data){
      return $data; //returns an array with numeric keys
    }
  }
  return null;
}
//VERIFY if the client is the owner of the residence.
//Returns true if yes, no if not.
function isOwner($cid,$rid){
  $rids = getR_idByC_id($cid);
  foreach($rids as $value){
    if(in_array($rid,$value)){
      return true;
    }
  }
  return false;
}

//Get most of the details of one residence.
//$rid, integer identifier who represent the residence.
//return all the details of the residence in HTML
//Return an error if not.
function details($rid){
  //WE NEED TO VERIFY IF THE RESIDENCE IS OWNED BY THE USER !
  $included =get_included_files();
  if(!in_array('includes/session.php',$included)){
    require 'session.php';
  }
  $residence = getResidenceDetails($rid);
  if(isOwner($_SESSION['user']['id'],$rid)){
    $notDisplayed = ['T_id','R_id'];
    $content = '<h4>Détails de votre résidence</h4>';
    $content .= '<div>';
    $content.='<div class="row">';
    $i = 0;
    foreach($residence[0] as $key=>$value){
      if(in_array($key,$notDisplayed)){break;}
      $div= '<div class="col" style="margin:1%">';
      $div.='<h6>'.$key.'</h6>';
      $div.=$value.'</div>';
      if($i++%2 == 0 && $i!=0){ $content .= '</div><div class="row">'.$div;}
      else{ $content.=$div;}

    }
    $content .= '</div>';
    return $content;
  }else{
    $error = '<hr>
    <div class="alert alert-danger">
    Vous n\'êtes pas le propriétaire de cette résidence.
    </div>';
    return $error;
  }
}
//VERIFY IF THE DATA OF THE FORM ARE CORRECTS
//USED FOR RESIDENCE ADD AND UPDATE
//IF NO ERRORS, RETURN A RESIDENCE Object
//IF ERRORS, RETURN ERRORS ARRAY
function verifyForm($post){
  $errors = [];
  $formType;
  $inputs = array_keys($post);
  if(in_array('add_r',$inputs)){
    unset($inputs[array_search('add_r',$inputs)]);
    $formType = 'add';
  }else if(in_array('mod_r',$inputs)){
    unset($inputs[array_search('mod_r',$inputs)]);
    $formType = 'mod';
  }
  if(isset($post) && !empty($post)){
    //Are inputs not empty?
    foreach($inputs as $pos=>$input){
      if(empty($post[$input])){
        $errors[$input] = ' est vide';
      }
    }
    if(empty($errors)){
      //INPUT VERIFICATION
      $residence = new Residence($post);
      $resErrors = $residence->getErrors();
      if(empty($resErrors)){
        if($formType == 'mod'){
          if(exist($residence)){

          }
        }else{
          if(exist($residence)){
            $errors['exist'] = 'La résidence à l\'adresse '.$residence->getL_address().' existe déjà.';
          }
          if(!isset($errors['exist']) && empty(getTownId($residence->getT_name(),$residence->getM_zipCode()))){
            $errors['exist'] = 'La ville ou le code postal indiqué ne font pas partie de notre base de données.';
          }
        }

      }else{
        foreach($resErrors as $key => $error){
          $errors[$key] = $error;
        }
      }
    }
  }else{
    $errors['important'] = "FATAL ERROR: Data not sent";
  }
  if(empty($errors)){
    return $residence;
  }
  return $errors;
}
function exist($Residence){
  require 'mysql/connect.php';
  $req = $pdo->prepare('SELECT R_id from Residency_Town natural join Town
    WHERE L_address = :address
    AND M_zipCode = :zip');
  $req->bindValue(':address',$Residence->getL_address());
  $req->bindValue(':zip',$Residence->getM_zipCode());
  $res = $req->execute();
  $resp = (bool)$req->fetch();
  $pdo=null;
  return $resp;
}
function getResidenceId($address){
  require 'mysql/connect.php';
  $req = $pdo->prepare('SELECT R_id from Residency_Town WHERE L_address = :address');
  $req->bindValue(':address',$address);
  $res = $req->execute();
  $resp = $req->fetch()[0];
  $pdo=null;
  return $resp;
}
function getTownId($town,$zip){
  require 'mysql/connect.php';
  $req = $pdo->prepare('SELECT T_id FROM Town WHERE T_name= :town AND M_zipCode = :zip');
  $req->bindValue(':town',$town);
  $req->bindValue(':zip',$zip);
  $req->execute();
  $resp = $req->fetch()[0];
  $pdo = null;
  return $resp;
}
//Get the Town ID and name thanks to the zipCode.
//$zip, the zipCode
//Returns an array containing either the result or the error if one occurs.
function getTownsByZip($zip){
  $data['M_zipCode']=$zip;
  $res = new Residence($data);
  $error = $res->getErrors();
  if(empty($error)){
    require 'mysql/connect.php';
    $req = $pdo->prepare('SELECT T_id, T_name FROM Town WHERE M_zipCode = :zip');
    $req->bindValue(':zip',$zip);
    $req->execute();
    $resp = $req->fetchAll(PDO::FETCH_ASSOC);
    $pdo=null;
    return $resp;
  }else{
    return $error;
  }
}
//This function allow us to add a VALID residence.
//$residence must be Object of type Residence
//Returns an error state in case of failure and delete the added residence.
function addResidence($Residence){
  $id = $_SESSION['user']['id'];
  if($Residence instanceof Residence){
    require 'mysql/connect.php';
    $req = $pdo->prepare('INSERT INTO Residency(R_area,R_inhabitants,R_nbRooms,R_residencyType)
    VALUES (:area,:inhabitants,:nbRooms,:type)');
    $req->bindValue(':area',$Residence->getR_area());
    $req->bindValue(':inhabitants',$Residence->getR_inhabitants());
    $req->bindValue(':nbRooms',$Residence->getR_nbRooms());
    $req->bindValue(':type',$Residence->getR_residencyType());
    $continue = (bool)$req->execute();
    if($continue){
      $rid = $pdo->lastInsertId();
      if(!empty($id) && !empty($rid)){
        $req = $pdo->prepare('INSERT INTO Inhabit(C_id,R_id,Inh_date) VALUES (:id,:rid,NOW())');
        $req->bindValue(':id',$id);
        $req->bindValue(':rid',$rid);
        $continue = (bool)$req->execute();
        $tid = getTownId($Residence->getT_name(),$Residence->getM_zipCode());
        if($continue && !empty($tid)){
          $req = $pdo->prepare('INSERT INTO Residency_Town(R_id,T_id,L_address) VALUES (:rid,:tid,:address)');
          $req->bindValue(':rid',$rid);
          $req->bindValue(':tid',$tid);
          $req->bindValue(':address',$Residence->getL_address());
          $req->execute();
        }else{
          if(!$continue){
            $continue = -1;
          }
        }
      }

    }
  }
  if(!$continue){ return $continue;}
  return null;
}
function modifyResidence($Residence){
  $id = $_SESSION['user']['id'];
  $rid = $_SESSION['residence']['R_id'];
  if(isset($id) && isset($rid)){
    require 'mysql/connect.php';
    $req = $pdo->prepare('UPDATE Residency SET R_area = :area,
      R_inhabitants = :inhabitants, R_nbRooms = :nbRooms, R_residencyType = :type
      WHERE R_id = :rid');
      $req->bindValue(':area',$Residence->getR_area());
      $req->bindValue(':inhabitants',$Residence->getR_inhabitants());
      $req->bindValue(':nbRooms',$Residence->getR_nbRooms());
      $req->bindValue(':type',$Residence->getR_residencyType());
      $req->bindValue(':rid',$rid);
      $continue = (bool)$req->execute();
      if($continue){
        $req = $pdo->prepare('UPDATE Residency_Town SET L_address = :address WHERE R_id = :rid');
        $req->bindValue(':address',$Residence->getL_address());
        $req->bindValue(':rid',$rid);
        $continue = (bool)$req->execute();
      }

  }
  return $continue;

}
function compareData($old,$new){
  unset($old['M_name']);
  unset($old['M_zipCode']);
  unset($old['T_name']);
  return (bool)($diff = array_diff($old,$new));
}
//Delete a residence
function deleteR($rid){
  $included =get_included_files();
  if(!in_array('includes/session.php',$included)){
    require 'session.php';
  }
  $id = $_SESSION['user']['id'];
  if(isOwner($id,$rid)){
    //then delete
    require '../mysql/connect.php';
    $req = $pdo->prepare('UPDATE Residency SET R_actif = 0 WHERE R_id = :rid');
    $req->bindValue(':rid',$rid);
    $req->execute();
  }
  return isDeleted($rid);

  //we must verify that the residence has been deleted.
}
function isDeleted($rid){
  require '../mysql/connect.php';
  return (bool)$pdo->query('SELECT R_actif FROM Residency WHERE R_id='.$rid);
}
function getTownsByZipCode($zip){
  $included =get_included_files();
  if(!in_array('user/Residence.php',$included)){
    require '../user/Residence.php';
  }
  $res = new Residence(['M_zipCode'=>$zip]);
  if(!empty($res->getM_zipCode())){
    require '../mysql/connect.php';
    $req = $pdo->prepare('SELECT T_name FROM Town WHERE M_zipCode = :zip');
    $req->bindValue(':zip',$zip);
    $req->execute();
    $pdo=null;
    return $req->fetchAll(PDO::FETCH_ASSOC);
  }
  return null;
}
function getDefault($id){
  require 'mysql/connect.php';
  $req = $pdo->prepare('SELECT R_id FROM Inhabit natural join Residency WHERE C_id = :id AND R_default = 1');
  $req->bindValue(':id',$id);
  $req->execute();
  $defRes = $req->fetch(PDO::FETCH_NUM);
  $pdo = null;
  if($defRes){return (int)$defRes[0];}
  return false;
}
//Get the specified meter that match both rid and type.
function getMeter($rid, $type = 'Electrique'){
  require 'mysql/connect.php';
  $req = $pdo->prepare('SELECT Me_id FROM Meter WHERE R_id = :rid AND Me_type = :mType');
  $req->bindValue(':rid',$rid);
  $req->bindValue(':mType',$type);
  $req->execute();
  $meter_id = $req->fetch(PDO::FETCH_NUM);
  $pdo=null;
  return (int)$meter_id[0];
}
//Nouveau  Get the water meter that match both rid and type.
function getMeterEau($rid, $type = 'Eau'){
  require 'mysql/connect.php';
  $req = $pdo->prepare('SELECT Me_id FROM Meter WHERE R_id = :rid AND Me_type = :mType');
  $req->bindValue(':rid',$rid);
  $req->bindValue(':mType',$type);
  $req->execute();
  $meter_id = $req->fetch(PDO::FETCH_NUM);
  $pdo=null;
  return (int)$meter_id[0];
}
//Nouveau  Get the gaz meter that match both rid and type.
function getMeterGaz($rid, $type = 'Gas'){
  require 'mysql/connect.php';
  $req = $pdo->prepare('SELECT Me_id FROM Meter WHERE R_id = :rid AND Me_type = :mType');
  $req->bindValue(':rid',$rid);
  $req->bindValue(':mType',$type);
  $req->execute();
  $meter_id = $req->fetch(PDO::FETCH_NUM);
  $pdo=null;
  return (int)$meter_id[0];
}
//Get the last residence for the user id.
function getLastR_id($id){
  require 'mysql/connect.php';
  $list = getR_idByC_id($id);
  return $list[count($list)-1][0];
}
function getData($rid,$action = '1'){
  require 'mysql/connect.php';
  $meter_id = getMeter($rid);
  $list = null;
  if($action == 1){
    $data = $pdo->query('SELECT Con_measure, Con_time 
    FROM emonitor.Control 
    WHERE Me_id = '.$meter_id);
  }else if($action == 2){
    $data = $dataD = $pdo->query("SELECT Con_measure, Con_time, Con_alert, Con_used, Con_id 
    FROM emonitor.Control 
    WHERE Me_id = $meter_id");
  }
  foreach ($data as $row){
    $list[] = $row;
  }
  #var_dump($list);
  $pdo = null;
  return $list;
}
//nouveau pour eau
function getDataEau($rid,$action = '1'){
  require 'mysql/connect.php';
  $meter_id = getMeterEau($rid);
  $list = null;
  if($action == 1){
    $data = $pdo->query('SELECT Con_measure, Con_time 
    FROM emonitor.Control 
    WHERE Me_id = '.$meter_id);
  }else if($action == 2){
    $data = $dataD = $pdo->query("SELECT Con_measure, Con_time, Con_alert, Con_used, Con_id 
    FROM emonitor.Control 
    WHERE Me_id = $meter_id");
  }
  foreach ($data as $row){
    $list[] = $row;
  }
  #var_dump($list);
  $pdo = null;
  return $list;
}
//nouveau pour gaz
function getDataGaz($rid,$action = '1'){
  require 'mysql/connect.php';
  $meter_id = getMeterGaz($rid);
  $list = null;
  if($action == 1){
    $data = $pdo->query('SELECT Con_measure, Con_time 
    FROM emonitor.Control 
    WHERE Me_id = '.$meter_id);
  }else if($action == 2){
    $data = $dataD = $pdo->query("SELECT Con_measure, Con_time, Con_alert, Con_used, Con_id 
    FROM emonitor.Control 
    WHERE Me_id = $meter_id");
  }
  foreach ($data as $row){
    $list[] = $row;
  }
  #var_dump($list);
  $pdo = null;
  return $list;
}
//Verify if the meter of the residence have data.
function hasData($rid){
  if(!empty(getData($rid))){
    return true;
  }
  return false;
}
//Nouveau Verify if the water meter of the residence have data.
function hasDataEau($rid){
  if(!empty(getDataEau($rid))){
    return true;
  }
  return false;
}
//Nouveau Verify if the gaz meter of the residence have data.
function hasDataGaz($rid){
  if(!empty(getDataGaz($rid))){
    return true;
  }
  return false;
}