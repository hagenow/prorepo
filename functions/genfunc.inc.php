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

function cleaninput($string)
{
    $conid = db_connect();

    // entferne schädlichen Code wie <p>, <script>, etc...
    $string = filter_var($string ,FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);

    // falls es in die Datenbank kommen soll, wird es noch mal escaped
    $conid->real_escape_string( $string );

    // slashes entfernen, falls noch welche vorhanden oder anders codiert
    $string = stripslashes( $string );

    // erlaubt sind nur folgende Zeichen: -,_,a-z,A-Z,0-9,[Leerzeichen] - alles andere
    // wird escaped
    $string = preg_replace('/[^-a-zA-Z0-9_]/', '',$string);

    // db-Verbindung schließen
    $conid->close();

    return $string;

}

?>
