<?php
session_start();
require 'includes/sessionFunctions.php';
//SESSION TEST
echo '<pre>'.print_r($_SESSION,1).'</pre>';
echo 'La session existe?'.isConnected().'<br>';
sessionCheck();
echo '<pre>'.print_r($_SESSION,1).'</pre>';
echo '<hr>';
echo 'Valide?'.isValid().'<br>';
echo '<pre>'.print_r($_SESSION,1).'</pre>';
echo '<hr>';
echo '<br>TESTING';
require_once 'mysql/connect.php';
$i = (int)$_SESSION['user']['id'];
var_dump($i);
$userManager = new UserManager($pdo);
$identity = $userManager->get($i);
var_dump($identity);
$_SESSION['user']['name'] = ucfirst($identity['C_firstname']);
$_SESSION['user']['lastname'] = ucfirst($identity['C_lastname']);
