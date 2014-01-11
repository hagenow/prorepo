<?php
/** initiate db connection
 * */
function db_connect()
{
    $conid = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

    if(!$conid)
    {
        die('Verbindung konnte nicht hergestellt werden ('.mysqli_connect_errno().') : ' . mysqli_connect_error());
    } 
    else
    {
        $conid->set_charset("utf8");
        return $conid;
    }
}

/* Alle Funktionen für das Handling mit Login und Sessions sind hier ausgelagert
 * */
require_once 'functions/userfunc.inc.php';

/* Alle Funktionen für das Handling mit Dateien und deren Zielverzeichnis
 *  */
require_once 'functions/filefunc.inc.php';


/* Alle Funktionen für das Handling mit Dateien und deren Zielverzeichnis
 *  */
require_once 'functions/catfunc.inc.php';
?>
