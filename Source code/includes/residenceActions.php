<?php
 // && isset($_POST['resnum'])
if(isset($_GET['action'])){
  require 'residence.functions.php';
  switch($_GET['action']){
    case 'details': $r['details'] = details($_GET['rid']);
      break;
    case 'modify': $r = 'modification.';
      break;
    case 'delete': $r = 'suppression.';
      break;
    case 'add': $r = implode("\n",file('addResidence.php'));
      break;
      default: $r= 'nothing';
  }
  if($r){
    echo json_encode($r);
  }
}