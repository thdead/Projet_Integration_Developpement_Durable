<?php
//www-data@vps460777.ovh.net
$_SESSION['email'] = 'ipes.lagache@gmail.com';
$tok = 'ezfhuihhgirhgrregoegreoier';
$email = $_SESSION['email'];
$headers = 'Content-Type: text/html; charset=utf-8'." \r\n";
$headers.= 'From: registration-system@emonitor.be'." \r\n";
$headers.= 'MIME-Version: 1.0 '." \r\n";
$headers.= 'Content-Trasnfert-Encoding: 8bit'." \r\n";
//end
$to = 'ipes.lagache@gmail.com';
$subject = 'Confirmation de votre inscription';
$message = '
Bonjour,
<br>
<p>Nous vous remercions pour votre inscription.</p>
<p>Afin de confirmer votre inscription et activer votre compte,
veuillez s\'il vous plait, cliquer sur le lien suivant:<br>
<a href="http://'.$_SERVER['SERVER_ADDR'].'/verify.php?email='.$email.'&token="'.$tok.'">
<br>
Bien à vous,';
 echo mail($to,$subject,$message, $headers);
