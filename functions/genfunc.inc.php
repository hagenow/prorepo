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
    // TAGs entfernen
    $string = strip_tags($string);
    $string = filter_var($string ,FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);

    // falls es in die Datenbank kommen soll, wird es noch mal escaped
    $conid->real_escape_string( $string );

    // slashes entfernen, falls noch welche vorhanden oder anders codiert
    $string = stripslashes( $string );

    // erlaubt sind nur folgende Zeichen: -,_,a-z,A-Z,0-9,[Leerzeichen] - alles andere
    // wird escaped
    $string = preg_replace('/[^-a-zA-ZäÄöÖüÜß0-9_[:space:].]/', '',$string);

    // db-Verbindung schließen
    $conid->close();

    return $string;
}

function cleantags($string)
{
    $conid = db_connect();

    // entferne schädlichen Code wie <p>, <script>, etc...
    // TAGs entfernen
    $string = strip_tags($string);
    $string = filter_var($string ,FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);

    // falls es in die Datenbank kommen soll, wird es noch mal escaped
    $conid->real_escape_string( $string );

    // slashes entfernen, falls noch welche vorhanden oder anders codiert
    $string = stripslashes( $string );

    // erlaubt sind nur folgende Zeichen: -,_,a-z,A-Z,0-9,[Leerzeichen] - alles andere
    // wird escaped
    $string = preg_replace('/[^-a-zA-Z0-9,]/', '',$string);

    // db-Verbindung schließen
    $conid->close();

    return $string;
}

// create guid (UUID) for Groups
function guid(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
        return $uuid;
    }
}

?>
