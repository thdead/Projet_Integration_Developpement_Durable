<?php
require_once('includes/functions.php');
session_start();
//BEGINNING
if(isset($_POST['login'])){
  $errors = [];
  if(empty($_POST['email'])){
    $errors['email'] = 'vide...';
  }
  if(empty($_POST['password'])){
    $errors['password'] = 'vide...';
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
        $pdo = new PDO('mysql:host=localhost;dbname=emonitor', 'root', 'integ');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $userManager = new UserManager($pdo);
        if($userManager->exist($user)){

          //getting the hash password from database
          $hashed_pwd = $userManager->getPassword($user);
          var_dump($hashed_pwd);
          if(!empty($hashed_pwd)){
            if($user->passwordMatch($_POST['password'],$hashed_pwd)){
              //connected
              header('location: index.php');
            }else{
              //wrong password
              $errors['password']='  <b>incorrect.</b>';
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

  <div id="stage">
        <div id="stage-login">
            <div class="container">
              <!-- ERROR SECTION -->
              <!-- IF USER EMAIL DOES NOT EXIST -->
                <?php if(isset($_SESSION['errors']['userNotExist'])): ?>
                <div class="alert alert-danger row">
                  <p><?= $_SESSION['errors']['userNotExist']; ?></p>
                </div>
                <?php endif; ?>
                <!-- END -->
                <div class="row">
                  <form action="#" method="post">
                    <h4>Formulaire de connexion</h4>
                    <div class="form-group col">
                      <div class="form-group">
                        <label class="col-form-label"for="email">
                          Adresse mail
                        </label>
                        <input name="email" id="email" type="email" class="form-control"
                         value="<?= isset($_SESSION['post']['email']) ?
                         htmlspecialchars($_SESSION['post']['email']): ""; ?>"
                         required>
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
                        <input name="password" id="password" type="password"
                        class="form-control" min="8" max="20" value="" required>
                        <?php if(isset($_SESSION['errors']['password'])): ?>
                         <p style="font-size:10px;color:#FAAC58;">
                          <?= 'Votre mot de passe est '.$_SESSION['errors']['password'] ?>
                         </p>
                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="form-group col">
                        <button name="login" type="submit"
                        class="btn btn-outline-success btn-success">Connexion</button>
                    </div>
                  </form>
                </div>
              </div>
        </div>
    </div>
</body>
</html>
