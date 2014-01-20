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

    // check type of submitted files
    if($_POST['type'] == "model")
    {
        $type = "model";
        $typeinfo = createmod();
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

    /** init submitted file-conuter to zero */
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

    
    /** if the form is sending input data, then execute the following if-stmt */
    if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST")
    {
    	// Loop $_FILES to execute all files
        foreach ($_FILES['files']['name'] as $f => $filename) 
        {     
            if ($_FILES['files']['error'][$f] == 4) 
            {
    	        continue; // Skip file if any error found
            }
            /** if a error occurs, then go to the next file */
            if ($_FILES['files']['error'][$f] == 0) 
            {	           
                /** if the file is to large, then go to the next file */
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
                    $filename = strtr( $filename , $replacements );
                    $filename = preg_replace('/[^-a-zA-Z_]/', '',$filename);
                    $name = preg_replace('/[^-a-zA-Z_]/', '',$name);
                    
                    $size = $_FILES['files']['size'][$f];

                    // set filetype for download
                    $fileType = $_FILES['files']['type'][$f];

                    /** create the pathname for the file*/ 
                    $filepath = STRG_PATH."/".$type."/".$id."_".$name."/".$timestamp."/";

                    /** create the pathname for the type*/ 
                    $typepath = STRG_PATH."/".$type."/".$id."_".$name."/";

                    if(!file_exists($filepath) && !is_dir($filepath))
                    {
                        mkdir($filepath, 0755, true);
                    }

                    $filename_w_ext = $filename.".".$ext;
                    $uniqid =  uniqid('f', TRUE);
                    /** create entry in the files table */
                    $sql = "INSERT INTO
                                        ".TBL_PREFIX."files
                                        (fileName, path, type, foreignID, ext, fileType, uploader, timestamp, uniqid, size)
                                   VALUES
                                        ('$filename_w_ext','$filepath','$type','$id','$ext','$fileType','$creator','$timestamp','$uniqid','$size')"; 

                    if($res = $conid->prepare($sql)){
                        $res->execute();
                        $res->store_result();
                        updatetypepath($type,$id,$typepath);
                    }
                    else
                    {
                        echo $conid->error;
                    }

                    $target = $filepath.$filename.".".$ext;

    	            if(move_uploaded_file($_FILES["files"]["tmp_name"][$f], $target))
                    {
                        $count++; // Number of successfully uploaded file
                    }

    	        }
    	    }
    	}
    }
}

function updatetypepath($type,$id,$typepath)
{
    $conid = db_connect();

    if($type == "model")
    {
    $sql = "UPDATE ".TBL_PREFIX."models
            SET path = '$typepath'
            WHERE modelID = '$id'";
    }
    elseif($type == "log")
    {
    $sql = "UPDATE ".TBL_PREFIX."logs
            SET path = '$typepath'
            WHERE logID = '$id'";
    }
    else
    {
        $conid->close();
        return false;
    }

    $res = $conid->prepare($sql);
    $res->execute();

    if ($res->affected_rows==1) 
    {
        $conid->close();
        return true;
    }
    else
    {
        $conid->close();
        return false;
    }
}

/* nearly complex as the function above, but it updates an existing model or log 
 * with new files
 * */


/* get versions of a model or log */
function getversions($type,$typeid)
{
    $conid = db_connect();

    $sql = "SELECT timestamp
            FROM ".TBL_PREFIX."files
            WHERE type = '$type' AND foreignID = '$typeid'";

    if( $res = $conid->query($sql) ){

        while( $row = $res->fetch_assoc() )
        {
            $html = "";
            $html .= date("d.m.Y - H:i:s", strtotime($row['timestamp']));
            $html .= "<br/>";

            echo $html;
        }
    }
    $conid->close();
}


?>
