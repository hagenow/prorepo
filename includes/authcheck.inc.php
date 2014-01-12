<?php 
ob_start(); 

if(!isset($_SESSION)){
        session_start();
}

 if (!isset($_SESSION['angemeldet']) || !$_SESSION['angemeldet']) {
     resetsession();
 }
?>
