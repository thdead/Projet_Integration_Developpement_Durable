<?php
if($_SESSION){
  #CREATING TOKEN FOR THIS SESSION
  //Getting information that we need to send the mail
  $email = $_SESSION['post']['email'];
  if(isset($_SESSION['post']['gender']) == 'M'){
    $gender = 'monsieur';
  }else if($_SESSION['post']['gender'] == 'W'){
    $gender = 'madame';
  }
  $name = $_SESSION['post']['lastName'];
  //HEADER
  $header = 'Content-type: text/html; charset=utf-8'."\r\n";
  $header.= 'From: registration-system@emonitor.be'."\r\n";
  $header.= 'MIME-Version: 1.0 '."\r\n";
  $header.= 'X-Mailer: PHP/'.phpversion();
  //end
  $to = $email;
  $subject = 'Confirmation d\'inscription';
  $message = '
  Bonjour '.$gender.' '.$name.',
  </br>
  Nous vous remercions pour votre inscription.
  </br>
  Afin de confirmer votre inscription et activer votre compte,
  veuillez s\'il vous plait, cliquer sur lien suivant:<br>
  <a href="http://'.$_SERVER['SERVER_ADDR'].'/registration/verify.php?email='.$email.'&token='.$_SESSION['token'].'">
  Valider mon inscription</a>';
  if(mail($to, $subject, $message, $header)){
    //SENDED BUT NOT SURE THAT IT WILL ARRIVE
    $_SESSION['success'] = ['title' => '<strong>Votre inscription est terminée.</strong>'];
    $_SESSION['success'] = ['message' => '<p>Veuillez consulter votre adresse mail
      <i>'.$email.'</i> et confirmer votre inscription afin de cloturer celle-ci.
    </p>'];
  }else{
    $errors['conf_mail']= 'Echec de l\'envoi du mail de confirmation lors de la
    création de votre compte.<br>Cliquer <a href="">ici</a> pour vous renvoyer un mail.';
  }
}
