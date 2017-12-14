<?php
require_once('includes/functions.php');
session_start();
if(isset($_POST['sub'])){
  $errors = [];
  $_POST['token'] = bin2hex(random_bytes(16));
  $user = new User($_POST);
  $userErrors = $user->getErrors();
  if(!isset($userErrors)){
    require_once 'mysql/connect.php';
    $manager = new UserManager($pdo);
    if($manager->exist($user)){
      $errors['email'] = ' est déjà utilisé par quelqu\'un d\'autre.';
    }else{
      $manager->add($user);
      if($manager->exist($user)){
        $_SESSION['token'] = $user->getToken();
        require_once 'registration/confmail.php';
      }
    }
      $pdo = null;
  }else{
    foreach($userErrors as $key => $error){
      $errors[$key] = handleErrors($error,$key);
    }
  }
  if(isset($errors)){
    $_SESSION['errors'] = $errors;
    $_SESSION['post'] = $_POST;
  }
  if(isset($_SESSION['success'])){
    $_SESSION['email'] = $_POST['email'];
    unset($_SESSION['errors']);
    unset($_SESSION['post']);
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Inscription</title>
    <?php include('includes/head.php'); ?>
  </head>
  <body>
    <?php include('includes/navbar.php'); ?>

		<div id="stage">
			<div id="stage-registration">
				<div class="container">
					<form method="post" action="">
						<div class="container" style="margin-top:10%;">
							<?php if(isset($_SESSION['success'])): ?>
							<div class="alert alert-success">
								<?php echo $_SESSION['success']['title']; ?>
								<p>Veuillez consulter votre adresse mail
									<i><?php echo (isset($_SESSION['email']) ? $_SESSION['email']:'/'); ?></i>
									et confirmer votre inscription afin de cloturer celle-ci.
								</p>
							</div>
							<?php
								unset($_SESSION);
								session_destroy();
							endif; ?>
              <?php if(isset($_SESSION['errors'])): ?>
              <div class="alert alert-danger">
                <strong>Une erreur est survenue...</strong>
                <?php if(isset($_SESSION['errors']['token'])){
                  echo '<p>Une erreur est survenue lors de la génération du token</p>';
                }else if(isset($_SESSION['errors']['conf_mail'])){
                  echo '<p>'.$_SESSION['errors']['conf_mail'].'</p>';
                }else{
                  echo '<p>Veuillez compléter les champs invalides.</p>';
                }  ?>
              </div>
            <?php endif; ?>
							<h4>Informations personnelles</h4>
							<small class="form-text text-muted">
								Veuillez remplir les champs ci-dessous. Les champs dotés de la mention
								<b style="color:orange;">*</b> sont obligatoires.
							</small>
                            <br>
							<div class="form-group row">
								<label for="lastName" class="col-3 col-form-label  col-form-label-sm">
									Nom<b style="color:orange;">*</b>
								</label>
								<div class="col-9">
									<input name="lastName" id="lastName" class="form-control form-control-sm"
									type="text"
									value="<?= isset($_SESSION['post']['lastName']) ? htmlspecialchars($_SESSION['post']['lastName'])
									: '' ?>" min=1 max=32 required/>
									<?php if(isset($_SESSION['errors']['lastName'])){
									echo '<p style="font-size:9px;color:#FAAC58;">';
									echo 'Votre nom'.$_SESSION['errors']['lastName'];
									echo '</p>';
									}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-3 col-form-label  col-form-label-sm">
								Prénom<b style="color:orange;">*</b>
								</label>
								<div class="col-9">
									<input name="firstName" id="firstName" class="form-control form-control-sm"
									type="text" value="<?= isset($_SESSION['post']['firstName']) ? htmlspecialchars(
									$_SESSION['post']['firstName']) : '' ?>" min=1 max=32 required/>
									<?php if(isset($_SESSION['errors']['firstName'])){
									echo '<p style="font-size:9px;color:#FAAC58;">';
									echo 'Votre prénom'.$_SESSION['errors']['firstName'];
									echo '</p>';
									}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label for="firstName" class="col-3 col-form-label  col-form-label-sm">
								Mot de passe<b style="color:orange;">*</b>
								</label>
								<div class="col-9">
									<input name="password" id="password" class="form-control form-control-sm"
									type="password" min=8 max=20 required/>
									<?php if(isset($_SESSION['errors']['password'])){
									echo '<p style="font-size:9px;color:#FAAC58;">';
									echo 'Votre mot de passe'.$_SESSION['errors']['password'];
									echo '</p>';
									}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label for="birthDate" class="col-3 col-form-label col-form-label-sm">
								Date de naissance<b style="color:orange;">*</b>
								</label>
								<div class="col-9">
									<input name="birthDate" id="birthDate" type="date"
									class="form-control form-control-sm"
                  placeholder="jj-mm-aaaa"
									value="<?= isset($_SESSION['post']['birthDate']) ? htmlspecialchars($_SESSION['post']['birthDate'])
									: '' ?>" required/>
									<?php if(isset($_SESSION['errors']['birthDate'])){
									echo '<p style="font-size:9px;color:#FAAC58;">';
									echo 'Votre date de naissance '.$_SESSION['errors']['birthDate'];
									echo '</p>';
									}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label for="email" class="col-3 col-form-label  col-form-label-sm">
								Adresse mail<b style="color:orange;">*</b>
								</label>
								<div class="col-9">
									<input name="email" id="email" type="email" class="form-control
									form-control-sm"
									value="<?= isset($_SESSION['post']['email']) ? htmlspecialchars($_SESSION['post']['email'])
									: '' ?>" required/>
									<?php if(isset($_SESSION['errors']['email'])){
									echo '<p style="font-size:9px;color:#FAAC58;">';
									echo 'Votre email'.$_SESSION['errors']['email'];
									echo '</p>';
									}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label for="gender" class="col-3 col-form-label col-form-label-sm">
								Sexe<b style="color:orange;">*</b>
								</label>
								<div class="col-9">
									<select class="form-control form-control-sm" name="gender" required>
									<option value="M" <?php if(isset($_SESSION['post']['gender']) && $_SESSION['post']['gender'] == "M")echo 'selected' ?>>Homme</option>
									<option value="W" <?php if(isset($_SESSION['post']['gender']) && $_SESSION['post']['gender'] == "W")echo 'selected' ?>>Femme</option>
									<option value="O" <?php if(isset($_SESSION['post']['gender']) && $_SESSION['post']['gender'] == "O")echo 'selected' ?>>Autre</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="phone" class="col-3 col-form-label col-form-label-sm">
								N° de téléphone
								</label>
								<div class="col-9">
									<input name="phone" id="phone" type="number"
									class="form-control form-control-sm"/>
									<?php if(isset($_SESSION['errors']['phoneNumber'])){
									echo '<p style="font-size:9px;color:#FAAC58;">';
									echo 'Votre numéro'.$_SESSION['errors']['phoneNumber'];
									echo '</p>';
									}
									?>
									</div>
							</div>
							<div class="form-group col">
								<button name="sub" type="submit" class="btn btn-outline-success" value="subscribe">
								M'inscrire
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

    <!-- JAVASCRIPT BOOTRAP -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  </body>
</html>
