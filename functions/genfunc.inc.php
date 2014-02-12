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

function mailtogfx($email)
{
    $size = 4;
    $textwidth = imagefontwidth($size) * strlen($email);
    $textheight  = imagefontheight($size);
     
    // header('Content-Disposition: Attachment;filename=mail.png'); 
    header("Cache-Control: no-store, no-cache, must-revalidate");  
    header("Cache-Control: post-check=0, pre-check=0", false);  
    //header('Content-type: image/png');
    $pic = imagecreatetruecolor ($textwidth , $textheight);
     
    $bg_col  = imagecolorallocate ($pic, 222, 222, 222);
    imagefill($pic, 0, 0, $bg_col);
    imagecolortransparent($pic,$bg_col);
     
    $text_col        = imagecolorallocate ($pic, 0, 0, 0);
    imagestring ($pic, $size, 0, 0, $email, $text_col);
    ob_start();
    imagepng ($pic);
    imagedestroy($pic);
    $output = ob_get_contents();
    ob_end_clean();

    echo '<img src="data:image/png;base64,'.base64_encode($output).'" />';
}

// extract given zip file to given target directory
function extractZip($zipfile,$targetdir)
{
    $zip = new ZipArchive;
    if ($zip->open($zipfile) === TRUE) 
    {
        $zip->extractTo($targetdir);
        $zip->close();
        return true;
    } 
    else 
    {
        return false;
    }
}
// recursively remove a directory
function rrmdir($dir) {
    foreach(glob($dir . '/*') as $file) 
    {
        if(is_dir($file))
            rrmdir($file);
        else
            unlink($file);
    }
    rmdir($dir);
}
?>
