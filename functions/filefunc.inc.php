<?php
/** this is a huge and complex function to upload files in their target 
 * directory.
 * This function creates the path, creates the database entry, links the ID from 
 * the model to the category, links the file to the model and so on.
 * Read the comments to understand this procedure!
 * */
function uploadfiles_new()
{
    $conid = db_connect();

    /** define the allowed extensions to prevent an attack */
    $valid_formats =array();
    $type = '';
    $typeinfo = array();
    if($_POST['type'] == "model")
    {
        $type = "model";
        $typeinfo = createmodel();
        $valid_formats = array("pdf", "png", "pnml", "xml", "svg", "eps");
    }
    elseif($_POST['type'] == "log")
    {
        $type = "log";
        $typeinfo = createlog();
        $valid_formats = array("xes", "mxml", "csv");
    }
    else
    {
        $message[] = "something wrent wrong with the type of the uploaded files!";
    }    

    /** replace the given chars with their equivalents */
    $replacements = array( 'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss', ' ' => '_' );

    /** use the FILESIZE from config.inc.php */
    $max_file_size = FILESIZE;
    $count = 0;

    /** get category ID */
    $catid = $_POST['catid'];

    /** get submitted date */
    $timestamp = $typeinfo['timestamp'];

    /** get model or log ID, if not exists, create a model or log instead and 
        * return the ID*/
    $id = $typeinfo['id'];
    $name = $typeinfo['name'];

    /** set creator to current logged in user */
    $creator = $_SESSION['user'];

    
    if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST")
    {
    	// Loop $_FILES to execute all files
        foreach ($_FILES['files']['name'] as $f => $filename) 
        {     
            if ($_FILES['files']['error'][$f] == 4) 
            {
    	        continue; // Skip file if any error found
            }
    
            if ($_FILES['files']['error'][$f] == 0) 
            {	           
                if ($_FILES['files']['size'][$f] > $max_file_size) 
                {
    	            $message[] = "$filename is too large!.";
    	            continue; // Skip large files
    	        }
                elseif( ! in_array(pathinfo($filename, PATHINFO_EXTENSION), $valid_formats) )
                {
    				$message[] = "$filename is not a valid format";
    				continue; // Skip invalid file formats
    			}
                else // No error found! Move uploaded files 
                {
                    // extract extension
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);

                    /** clean up the filename */
                    $filename = pathinfo($filename, PATHINFO_FILENAME);
                    $filename = strtr( strtolower( $filename), $replacements );
                    $filename = cleaninput($filename);
                    
                    $size = $_FILES['files']['size'][$f];

                    /** create the filename */ 
                    $path = STRG_PATH."/".$type."/".$id."_".$name."/".$timestamp."/";
                    if(STRG_DEST == "local")
                    {
                        mkdir($path, 0755, true);
                        /** create local folder-path 
                        if (!mkdir($path, 0755, true)) {
                            die('Erstellung der Verzeichnisse schlug fehl...');
                        } */
                    }
                    elseif(STRG_DEST == "remote")
                    {
                        /** create remote folder-path eg. with sftp or ftp
                         * */
                    }
                    else
                    {
                        $messages[] = "can't create folders, because the STRG parameter is not set correctly via setup!";
                    }

                    /** create entry in the files table */
                    $sql = "INSERT INTO
                                        ".TBL_PREFIX."files
                                        (fileName, type, foreignID, fileType, uploader, timestamp, size)
                                   VALUES
                                        ('$filename','$type','$id','$ext','$creator','$timestamp','$size')"; 

                    if($res = $conid->prepare($sql)){
                        $res->execute();
                        $res->store_result();
                    }
                    else
                    {
                        echo $conid->error;
                    }

                    $target = $path.$filename.".".$ext;

                    // upload the file to the repository
    	            if(move_uploaded_file($_FILES["files"]["tmp_name"][$f], $target))
    	            $count++; // Number of successfully uploaded file
    	        }
    	    }
    	}
    }
}

/** this is a deprecated crappy function */
function uploadfiles()
{
    // Whiteliste Dateiendungen und Ersetzungen
    $allowed_ext = array( "jpg", "gif", "zip" );
    $replacements = array( 'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss', ' ' => '_' );
    // Pruefen ob die hochgeladenen Datei mehr als 0 Byte hat
    // Hat sie das nicht, wurde auch nichts hochgeladen, logisch, was?! ;)
    if ($_SESSION['datei']['size'] > 0)
    {
        // Dateiendung der hochgeladenen Datei abtrennen
        $file_ext = array_pop( explode( ".", strtolower( $_SESSION['datei']['name'] ) ) );
        // Schauen ob die Endung der hochgeladenen Datei in der Whitelist steht
        if (!in_array( $file_ext, $allowed_ext ))
        {
            die( "Die angeh&auml;ngte Datei hat eine nicht erlaubte Dateiendung!" );
        }
        // Neuer Dateiname erzeugen indem Umlaute und Leerzeichen umgewandelt werden
        $filename_new = strtr( strtolower( $_SESSION['modelName'] ), $replacements );

        /** clean up the modelname */
        $modelname = strtr( strtolower( $_POST['datei']['name'] ), $replacements );
        $modelname = $cleaninput( $modelname );

        $pathname = STRG_PATH."/".$_POST['type']."/".$modelid."_".$modelname."/".$_POST['date']."/";
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
