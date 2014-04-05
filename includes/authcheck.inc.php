<?php 
ob_start(); 

if(!isset($_SESSION)){
    session_start();
}

if (!isset($_SESSION['angemeldet']) || !$_SESSION['angemeldet']) {
     resetsession();
}

if($_SESSION['userAgent'] !== $_SERVER['HTTP_USER_AGENT'] || 
    $_SESSION['IPaddress'] !== $_SERVER['REMOTE_ADDR'] )
     resetsession();


?>
