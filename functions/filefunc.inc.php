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

                    // run validation process
                    $valid = "2";

                    if(isset($_POST['checkboxes']) && $_POST['checkboxes'] == "validatepnml" && $ext == "pnml")
                    {
                        $valid = validatePNML($_FILES["files"]["tmp_name"][$f]);
                    }
                    if(isset($_POST['checkboxes-0']) && $_POST['checkboxes-0'] == "validatexes" && $ext == "mxml")
                    {
                        $valid = validateMXML($_FILES["files"]["tmp_name"][$f]);
                    }
                    if(isset($_POST['checkboxes-1']) && $_POST['checkboxes-1'] == "validatemxml" && $ext == "xes")
                    {
                        $valid = validateXES($_FILES["files"]["tmp_name"][$f]);
                    }


                    $filename_w_ext = $filename.".".$ext;
                    $uniqid =  uniqid('f', TRUE);
                    /** create entry in the files table */
                    $sql = "INSERT INTO
                                        ".TBL_PREFIX."files
                                        (fileName, path, type, foreignID, ext, fileType, uploader, timestamp, uniqid, size, valid)
                                   VALUES
                                        ('$filename_w_ext','$filepath','$type','$id','$ext','$fileType','$creator','$timestamp','$uniqid','$size','$valid')"; 

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

/* nearly complex like uploadfiles_new .
 * This function uploads files and link them to the given model, it updates 
 * values from the model */
function uploadfiles_existing()
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
        editmodel($_GET['modelID']);
        $typeinfo = viewmodel($_GET['modelID']);
        $valid_formats = array("pdf", "png", "pnml", "xml", "svg", "eps");
    }
    elseif($_POST['type'] == "log")
    {
        $type = "log";
        editlog($_GET['logID']);
        $typeinfo = viewlog($_GET['logID']);
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
    $catid = $typeinfo['catID'];

    /** get submitted date */
    $timestamp = $_POST['timestamp'];

    /** get model or log ID */
    $id = $typeinfo['id'];
    $name = $typeinfo['name'];

    /** set creator to current logged in user */
    $creator = $typeinfo['creator'];

    
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

                    /** filepath from given parameters */ 
                    
                    $filepath = $typeinfo['path']."/".$timestamp."/";

                    if(!file_exists($filepath) && !is_dir($filepath))
                    {
                        mkdir($filepath, 0755, true);
                    }

                    // run validation process
                    $valid = "2";

                    if(isset($_POST['checkboxes']) && $_POST['checkboxes'] == "validatepnml" && $ext == "pnml")
                    {
                        $valid = validatePNML($_FILES["files"]["tmp_name"][$f]);
                    }
                    if(isset($_POST['checkboxes-0']) && $_POST['checkboxes-0'] == "validatexes" && $ext == "mxml")
                    {
                        $valid = validateMXML($_FILES["files"]["tmp_name"][$f]);
                    }
                    if(isset($_POST['checkboxes-1']) && $_POST['checkboxes-1'] == "validatemxml" && $ext == "xes")
                    {
                        $valid = validateXES($_FILES["files"]["tmp_name"][$f]);
                    }

                    $filename_w_ext = $filename.".".$ext;
                    $uniqid =  uniqid('f', TRUE);
                    /** create entry in the files table */
                    $sql = "INSERT INTO
                                        ".TBL_PREFIX."files
                                        (fileName, path, type, foreignID, ext, fileType, uploader, timestamp, uniqid, size, valid)
                                   VALUES
                                        ('$filename_w_ext','$filepath','$type','$id','$ext','$fileType','$creator','$timestamp','$uniqid','$size','$valid')"; 

                    if($res = $conid->prepare($sql)){
                        $res->execute();
                        $res->store_result();
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

    $sql = "SELECT DISTINCT(timestamp)
            FROM ".TBL_PREFIX."files
            WHERE type = '$type' AND foreignID = '$typeid'
            ORDER BY timestamp
            DESC";

    if( $res = $conid->query($sql) ){

        while( $row = $res->fetch_assoc() )
        {
            $html = "";
            $html .= "<option>".$row['timestamp']."</option>";

            echo $html;
        }
    }
    $conid->close();
}

/* returns files by given type (model/log), the foreignID als typeid, the 
    * fileextension for filtering and the upper bound as date, if no date is 
    * given, then all files will be displayed
    * */
function viewfiles($type,$typeid,$ext,$date)
{
    $conid = db_connect();

    // $date = date("d.m.Y - H:i:s", $date);

    $sql = "SELECT *
            FROM ".TBL_PREFIX."files
            WHERE type = '$type' AND foreignID = '$typeid' AND ext = '$ext' AND timestamp <= '$date'
            ORDER BY timestamp
            DESC";

    $validation_type = array("pnml","mxml","xes");
    $other_type = array("xml","png","jpg","pdf","eps","svg","csv");

    if( $res = $conid->query($sql) ){

        while( $row = $res->fetch_assoc() )
        {
            $date = date("d.m.Y - H:i:s", strtotime($row['timestamp']));
            $html = "";
            $html .= "<tr>";
            $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=download&id=".$row['uniqid']."\">".$row['fileName']."</a></td>";
            if(in_array($ext, $validation_type))
            {
                if($row['valid'] == 0)
                {
                    $html .= "<td class=\"text-center\"><span class=\"label label-warning\">invalid</span></td>";
                }
                elseif($row['valid'] == 1)
                {
                    $html .= "<td class=\"text-center\"><span class=\"label label-success\">valid</span></td>";
                }
                elseif($row['valid'] == 2)
                {
                    $html .= "<td class=\"text-center\"><span class=\"label label-primary\">unknown</span></td>";
                }
            }
            if(in_array($ext, $other_type))
            {
                $html .= "<td class=\"text-center\"></td>";
            }
            $html .= "<td class=\"text-center\">".round(($row['size'] / 1024), 2)." KB</td>";
            $html .= "<td class=\"text-center\">".$date."</td>";
            $html .= "<td class=\"text-center\"><a href=\"".$_SERVER['PHP_SELF']."?show=usershow&name=".$row['uploader']."\">".$row['uploader']."</td>";
            $html .= "</tr>";

            echo $html;
        }
    }
    $conid->close();
}
?>
