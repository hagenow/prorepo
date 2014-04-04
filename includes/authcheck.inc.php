<?php 

ob_start(); 

if(!isset($_SESSION)){
    session_start();
}

if (!isset($_SESSION['angemeldet']) || !$_SESSION['angemeldet']) {
     resetsession();
}
if(isset($_SESSION))
{
    if($_SESSION['IPaddress'] != $_SERVER['REMOTE_ADDR'])
        resetsession();

    if($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT'])
        resetsession();

    if($_SESSION['EXPIRES'] < time())
        resetsession();
    else
        $_SESSION['EXPIRES'] = time() + LIFETIME;


}


?>
