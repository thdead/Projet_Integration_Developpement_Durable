<?php
require 'includes/functions.php';
if(isset($_GET['email']) && isset($_GET['token'])){
    if(!empty($_GET['email']) && !empty($_GET['token'])){
       $user = new User($_GET);
       if(empty($user->getErrors())){
          require_once 'mysql/connect.php';
          $userManager = new UserManager($pdo);
          if($userManager->exist($user)){
            $token = $userManager->getToken($user);
            if(!empty($token) && $user->tokenMatch($token)){
              //user is validate
              $userManager->validateUser($user);
              if($userManager->isValidUser($user)){
                $success = 'Votre compte a été activé avec succès.';
              }else{
                $error['message'] = 'Echec de validation de votre compte. Veuillez réessayer.';
              }
            }else{
              $error['message'] = 'Une erreur est survenue lors de la correspondance des tokens.';
            }
          }else{
            $error['message'] = 'Le compte que vous tentez de confirmer n\'existe pas.';
          }
       }else{
         $error['message'] = 'Les paramètres de ce lien de validation sont incorrects.';
       }
    }else{
      $error['message'] = 'Ce lien de validation est incorrect.';
    }
}else{
  $error['message'] = 'Ce lien de validation est invalide.';
}
?>
<!DOCTYPE html>
<html>
<head>
  <?php include 'includes/head.php'; ?>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  <div style="margin:5%;"></div>
  <?php if(isset($success)): ?>
  <div class="alert alert-success">
  <h4>Validation complèté!</h4>
  <p><strong><?= $success ?></strong></p>
  </div>
  <?php endif; ?>
  <?php if(isset($error)): ?>
  <div class="alert alert-danger">
  <h4>Une erreur est survenue...</h4>
  <p><strong><?= $error['message'] ?></strong></p>
  </div>
  <?php endif; ?>
</body>
</html>
