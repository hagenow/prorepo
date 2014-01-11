<?php

function uploadfiles($arr,$params)
{
    // Whiteliste Dateiendungen und Ersetzungen
    $allowed_ext = array( "jpg", "gif", "zip" );
    $replacements = array( 'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss', ' ' => '_' );
    // Pruefen ob die hochgeladenen Datei mehr als 0 Byte hat
    // Hat sie das nicht, wurde auch nichts hochgeladen, logisch, was?! ;)
    if ($arr['datei']['size'] > 0)
    {
        // Dateiendung der hochgeladenen Datei abtrennen
        $file_ext = array_pop( explode( ".", strtolower( $arr['datei']['name'] ) ) );
        // Schauen ob die Endung der hochgeladenen Datei in der Whitelist steht
        if (!in_array( $file_ext, $allowed_ext ))
        {
            die( "Die angeh&auml;ngte Datei hat eine nicht erlaubte Dateiendung!" );
        }
        // Neuer Dateiname erzeugen indem Umlaute und Leerzeichen umgewandelt werden
        $filename_new = strtr( strtolower( $arr['datei']['name'] ), $replacements );
        $pathname = $params['type'];
        $target = $pathname."/".$filename_new;
        echo $target;
        // UMASK resetten um Dateirechte zu ändern (wird nur fuer Linux benoetigt, Windows ignoriert das)
        $umask_alt = umask( 0 );
        // Hochgeladenen Datei verschieben
        if (@move_uploaded_file( $arr['datei']['tmp_name'], $target ))
        {
            // Die Datei wurde erfolgreich an ihren Bestimmungsort verschoben
            /* ***************************************************************************************** */
            /* *** Hier koennte Code stehen um Email zu versenden oder Datenbank-Eintraege zu machen *** */
            /* ***************************************************************************************** */

            // Dateirechte setzen, damit man später die Datei wieder vom FTP bekommt und die UMASK auf den alten Wert setzen
            @chmod( $filename_new, 0755 );
            umask( $umask_alt );
        }
        else
        {
            // UMASK resetten
            umask( $umask_alt );
            // Hier steht Code der ausgefuehrt wird, wenn der Upload fehl schlug
        }
    }

}
?>
