<?php
require_once('includes/functions.php');
session_start();
if(isset($_POST['kill_session'])){
  unset($_SESSION);
  session_destroy();
}
//BEGINNING
if(isset($_POST['login'])){
  $errors = [];
  if(empty($_POST['email'])){
    $errors['email'] = 'Veuillez rentrer votre email.';
  }
  if(empty($_POST['password'])){
    $errors['password'] = 'Veuillez rentrer votre mot de passe.';
  }
  if(empty($errors)){
    $user = new User($_POST);
    $userErrors = $user->getErrors();
    if($userErrors){
      foreach($userErrors as $key => $error){
         $errors[$key] = handleErrors($error,$key);
      }
    }else{
      // EMAIL & PASSWORD FORMATS ARE VALID.
      // CHECKING IF USER IS EXISTING IN DATABASE. ID=email
      try{
        $pdo = new PDO('mysql:host=localhost;dbname=emonitor', 'root', 'integ'); //TO CHANGE
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $userManager = new UserManager($pdo);
        if($userManager->exist($user)){
          
          //getting the hash password from database
          $hashed_pwd = $userManager->getPassword($user);
          var_dump($hashed_pwd);
          if(!empty($hashed_pwd)){
            if($user->passwordMatch($_SESSION['post']['password'],$hashed_pwd)){
              //connected
            }else{
              //wrong password
              $errors['password']=' est incorrect.';
            }
          }
        }else{
          //user not eisting
          $errors['userNotExist']= 'Aucun utilisateur ne correspond à
          <i>'.$user->getEmail().'</i>.';
        }
        $pdo = null;
      }catch(PDOException $e){

      }
    }
  }
  if(isset($errors)){
    var_dump($errors);
    $_SESSION['errors']= $errors;
    $_SESSION['post'] = $_POST;
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Connexion</title>
  <?php include('includes/head.php'); ?>
</head>
<body>
  <?php include('includes/navbar.php'); ?>
  <div class="container">
    <div class="alert alert-info row">
    Cette page est en cours de construction.
    </div>
    <div class="row">
      <!-- NEW  -->
      <?php if(isset($_SESSION['errors']['userNotExist'])): ?>
        <div class="alert alert-danger">
          <p><?= $_SESSION['errors']['userNotExist']; ?></p>
        </div>
      <?php endif; ?>
      <!-- END  -->
      <form action="#" method="post">
        <h4>Formulaire de connexion</h4>
        <div class="form-group col">
          <div class="form-group">
            <label class="col-form-label"for="email">Adresse mail</label>
            <input name="email" id="email" type="email" class="form-control"
             value="<?= isset($_SESSION['post']['email']) ? $_SESSION['post']['email']: ""; ?>">
             <?php if(isset($_SESSION['errors']['email'])): ?>
              <p style="font-size:10px;color:#FAAC58;">
               <?= 'Votre adresse mail est '.$_SESSION['errors']['email'] ?>
              </p>
             <?php endif; ?>
          </div>
        </div>
        <div class="form-group col">
          <div class="form-group">
            <label for="password">Mot de passe</label>
            <input name="password" id="password" type="password" class="form-control"
            value="">
            <?php if(isset($_SESSION['errors']['password'])): ?>
             <p style="font-size:10px;color:#FAAC58;">
              <?= 'Votre mot de passe est '.$_SESSION['errors']['password'] ?>
             </p>
            <?php endif; ?>
          </div>
        </div>
        <div class="form-group col">
          <div class="form-group col-md-6">
            <button name="login" type="submit" class="btn btn-primary">Connexion</button>
          </div>
        </div>
          <button name="kill_session" type="submit" class="btn btn-primary">kill session</button>
          <!-- debbug -->
      </form>
    </div>
  </div>
  <div class="">
    <h4>Debbug</h4>
    <?php if(isset($_SESSION))var_dump($_SESSION);?>
  </div>
</body>
</html>
