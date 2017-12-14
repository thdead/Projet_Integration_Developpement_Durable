<?php
if($_SESSION){
  #CREATING TOKEN FOR THIS SESSION
  //Getting information that we need to send the mail
  // $_SESSION['post']['email'] = $_POST['email'];
  $email = $_POST['email']; //$_SESSION['post']['email'] WASNT WORKING...
  //Definition of gender.
  if(isset($_SESSION['post']['gender']) == 'M'){
    $gender = 'monsieur';
  }else if($_SESSION['post']['gender'] == 'W'){
    $gender = 'madame';
  }
  $name = $_SESSION['post']['lastName'];
  //HEADER
  $headers = 'Content-Type: text/html; charset=utf-8'." \r\n";
  $headers.= 'From: registration-system@emonitor.be'." \r\n";
  $headers.= 'MIME-Version: 1.0 '." \r\n";
  $headers.= 'Content-Trasnfert-Encoding: 8bit'." \r\n";
  //end
  //MAIL-PART
  $to = $email;
  $subject = 'Confirmation de votre inscription';
  $message = '
  Bonjour '.$gender.' '.$name.',
  <br>
  <p>Nous vous remercions pour votre inscription.</p>
  <p>Afin de confirmer votre inscription et activer votre compte,
  veuillez s\'il vous plait, cliquer sur le lien suivant:<br>
  <a href="http://'.$_SERVER['SERVER_ADDR'].'/verify.php?email='.$email.'&token='.$_SESSION['token'].'">
  Valider mon inscription</a></p>
  <br>
  Bien à vous,';
  //END-MAIL
  if(mail($to, $subject, $message, $headers)){
    //SENDED BUT NOT SURE THAT IT WILL ARRIVE
    $_SESSION['success'] = ['title' => '<strong>Votre inscription est terminée.</strong>'];
    $_SESSION['success'] = ['message' => '<p>Veuillez consulter votre adresse mail
      <i>'.$email.'</i> et confirmer votre inscription afin de cloturer celle-ci.
    </p>'];
  }else{ //IF ERROR DURING SENDING
    $errors['conf_mail']= 'Echec de l\'envoi du mail de confirmation lors de la
    création de votre compte.<br>Cliquer <a href="">ici</a> pour vous renvoyer un mail.';
  }
}
