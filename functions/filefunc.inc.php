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
        $valid_formats = array("pdf", "png", "pnml", "xml", "svg", "eps", "tpn");
    }
    elseif($_POST['type'] == "log")
    {
        $type = "log";
        $typeinfo = createlog();
        $valid_formats = array("xes", "mxml", "csv", "txt");
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
                    $filename = preg_replace('/[^-0-9a-zA-Z_]/', '',$filename);
                    $name = preg_replace('/[^-0-9a-zA-Z_]/', '',$name);
                    
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
                                        (fileName, path, type, foreignID, ext, fileType, uploader, timestamp, uniqid, size, valid, deletable)
                                   VALUES
                                        ('$filename_w_ext','$filepath','$type','$id','$ext','$fileType','$creator','$timestamp','$uniqid','$size','$valid','1')"; 

                    if($res = $conid->prepare($sql)){
                        $res->execute();
                        $res->store_result();
                        updatetypepath($type,$id,$typepath);
                    }
                    else
                    {
                        echo $conid->error;
                        $conid->close();
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
    $conid->close();
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
        $valid_formats = array("pdf", "png", "pnml", "xml", "svg", "eps", "tpn");
    }
    elseif($_POST['type'] == "log")
    {
        $type = "log";
        editlog($_GET['logID']);
        $typeinfo = viewlog($_GET['logID']);
        $valid_formats = array("xes", "mxml", "csv", "txt");
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
                    $filename = preg_replace('/[^-0-9a-zA-Z_]/', '',$filename);
                    $name = preg_replace('/[^-0-9a-zA-Z_]/', '',$name);
                    
                    $size = $_FILES['files']['size'][$f];

                    // set filetype for download
                    $fileType = $_FILES['files']['type'][$f];

                    /** filepath from given parameters */ 
                    
                    $filepath = $typeinfo['path'].$timestamp."/";

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
                                        (fileName, path, type, foreignID, ext, fileType, uploader, timestamp, uniqid, size, valid, deletable)
                                   VALUES
                                        ('$filename_w_ext','$filepath','$type','$id','$ext','$fileType','$creator','$timestamp','$uniqid','$size','$valid','1')"; 

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
    $conid->close();
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
            $html .= "<td><a href=\"http://" . $_SERVER['HTTP_HOST'].PATHINFO."d/".$row['uniqid']."\">".$row['fileName']."</a></td>";
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
            if($row['deletable'] == "1")
                $html .= "<td class=\"text-center\"><a href=\"".$_SERVER['PHP_SELF']."?show=filedelete&id=".$row['uniqid']."\"><span class=\"glyphicon glyphicon-remove\"></span></td>";
            else
                $html .= "<td class=\"text-center\"></td>";
            $html .= "</tr>";

            echo $html;
        }
    }
    $conid->close();
}

// recursively read directory structure and return an array 
function find_all_files($dir)
{
    $root = scandir($dir);
    foreach($root as $value)
    {
        if($value === '.' || $value === '..') 
        {
            continue;
        }
        if(is_file("$dir/$value")) 
        {
            $result[]="$dir/$value";
            continue;
        }
        foreach(find_all_files("$dir/$value") as $value)
        {
            $result[]=$value;
        }
    }
    return $result;
} 

function batchimport_step1($result,$targetdir)
{
    // define arrays for storaging the values
    $models = array();
    $logs = array();
    $files = array();

    //define arrays for temporary storaging of the values
    $filenames = array();
    $filetypes = array();
    $typenames = array();
    $mimetypes = array();
    $extensions = array();
    $sizes = array();
    $tmp_paths = array();

    // file extensions
    $log_extensions = array("xes", "mxml", "csv", "txt");
    $model_extensions = array("pdf", "png", "pnml", "xml", "svg", "eps", "tpn");
    $check_extensions = array_merge($log_extensions, $model_extensions);

    $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension

    foreach($result as $res)
    {
        $path_parts = pathinfo($res);

        if(in_array($path_parts['extension'], $check_extensions))
        {
            if(in_array($path_parts['extension'], $model_extensions))
            {
                $filetype = "model";

                // extract modelname
                $tmp = strstr(str_replace($targetdir.'/', '', $res), '/', 1);
                if(!in_array($tmp, $models) && !empty($tmp))
                {
                    $typename = $tmp;
                    array_push($models, $tmp);
                }
                elseif(!in_array($tmp, $models) &&  !in_array($path_parts['filename'], $models) && empty($tmp))
                {
                    $typename = $path_parts['filename'];
                    array_push($models, $typename);
                }
            }
            elseif(in_array($path_parts['extension'], $log_extensions))
            {
                $filetype = "log";

                // extract logname
                $tmp = strstr(str_replace($targetdir.'/', '', $res), '/', 1);
                if(!in_array($tmp, $logs) && !empty($tmp))
                {
                    $typename = $tmp;
                    array_push($logs, $tmp);
                }
                elseif(!in_array($tmp, $logs) && !in_array($path_parts['filename'], $logs) && empty($tmp))
                {
                    $typename = $path_parts['filename'];
                    array_push($logs, $typename);
                }
            }

            $filename = $path_parts['basename'];
            $extension = $path_parts['extension'];
            $size = filesize($res);
            $mimetype = finfo_file($finfo, $res);

            array_push($filenames, $filename);
            array_push($filetypes, $filetype);
            array_push($typenames, $typename);
            array_push($mimetypes, $mimetype);
            array_push($extensions, $extension);
            array_push($sizes, $size);
            array_push($tmp_paths, $res);
        }
        else
        {
            continue;
        }
        
    }
    $files['filename'] = $filenames;
    $files['filetype'] = $filetypes;
    $files['mimetype'] = $mimetypes;
    $files['tmp_path'] = $tmp_paths;
    $files['typename'] = $typenames;
    $files['extension'] = $extensions;
    $files['size'] = $sizes;

    array_multisort($files['filetype'], SORT_DESC, SORT_STRING, $files['filename'],$files['typename'],
                    $files['mimetype'],$files['tmp_path'],$files['typename'],$files['extension'],$files['size']);

    finfo_close($finfo);

    $_SESSION['files'] = $files;
    $_SESSION['models'] = $models;
    $_SESSION['logs'] = $logs;

}

function batchimport_step2()
{
    $models = $_SESSION['models'];
    $logs = $_SESSION['logs'];
    $models_assoc = array();
    $logs_assoc = array();
    unset($_SESSION['models']);
    unset($_SESSION['logs']);

    $timestamp = $_POST['timestamp'];
    $_SESSION['timestamp'] = $timestamp;

    $catid = $_SESSION['cid'];
    unset($_SESSION['cid']);
    unset($_SESSION['cname']);

    // iterate over existing models, check whether it exists, else create a new 
    // one and save the id in an associative array
    foreach($models as $name)
    {
        $modval = checkmodelexist($name);

        if($modval['id'] != 0)
        {
            $models_assoc[$name] = $modval;
        }
        else
        {
            $modval = array();
            $modval['id'] = batchimport_createmodel($name, $timestamp, $catid);

            /** create the pathname for the file*/ 
            $filepath = STRG_PATH."/model/".$modval['id']."_".$name."/".$timestamp."/";
            $modval['path'] = $filepath;

            /** create the pathname for the type*/ 
            $typepath = STRG_PATH."/model/".$modval['id']."_".$name."/";

            $models_assoc[$name] = $modval;

            updatetypepath('model',$modval['id'],$typepath);

            if(!file_exists($filepath) && !is_dir($filepath))
            {
                mkdir($filepath, 0755, true);
            }
        }
    }

    // iterate over existing logs, check whether it exists, else create a new 
    // one and save the id in an associative array
    foreach($logs as $name)
    {
        $logval = checklogexist($name);
        if($logval['id'] != 0)
        {
            $logs_assoc[$name] = $logval;
        }
        else
        {
            $logval = array();
            if(array_key_exists($name, $models_assoc))
            {
                $logval['id']= batchimport_createlog($name, $timestamp, $catid, $models_assoc[$name]['id']);

                /** create the pathname for the file*/ 
                $filepath = STRG_PATH."/log/".$logval['id']."_".$name."/".$timestamp."/";
                $logval['path'] = $filepath;

                /** create the pathname for the type*/ 
                $typepath = STRG_PATH."/log/".$logval['id']."_".$name."/";

                $logs_assoc[$name] = $logval; 

                updatetypepath('log',$logval['id'],$typepath);

                if(!file_exists($filepath) && !is_dir($filepath))
                {
                    mkdir($filepath, 0755, true);
                }
            }
            else
            {
                $logval['id']= batchimport_createlog($name, $timestamp, $catid, '0');

                /** create the pathname for the file*/ 
                $filepath = STRG_PATH."/log/".$logval['id']."_".$name."/".$timestamp."/";
                $logval['path'] = $filepath;

                /** create the pathname for the type*/ 
                $typepath = STRG_PATH."/log/".$logval['id']."_".$name."/";

                $logs_assoc[$name] = $logval; 

                updatetypepath('log',$logval['id'],$typepath);

                if(!file_exists($filepath) && !is_dir($filepath))
                {
                    mkdir($filepath, 0755, true);
                }
            }
        }
    }

    $_SESSION['models_assoc'] = $models_assoc;
    $_SESSION['logs_assoc'] = $logs_assoc;

    if(DEBUG)
    {
        echo "<pre>";
        print_r($models_assoc);
        echo "</pre>";
        echo "<pre>";
        print_r($logs_assoc);
        echo "</pre>";
    }
}
function batchimport_step3()
{
    $conid = db_connect();

    $files = $_SESSION['files'];
    unset($_SESSION['files']);

    $models_assoc = $_SESSION['models_assoc'];
    $logs_assoc = $_SESSION['logs_assoc'];

    $timestamp = $_SESSION['timestamp'];
    unset($_SESSION['timestamp']);
    unset($_SESSION['models_assoc']);
    unset($_SESSION['logs_assoc']);

    /** replace the given chars with their equivalents */
    $replacements = array( 'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss', ' ' => '_' );

    /** use the FILESIZE from config.inc.php */
    $max_file_size = FILESIZE;

    /** init submitted file-conuter to zero */
    $count = 0;

    /** set creator to current logged in user */
    $creator = $_SESSION['user'];

    // Loop $files to execute all files
    foreach ($files['filename'] as $f => $filename) 
    {     
        /** if the file is to large, then go to the next file */
        if ($files['size'][$f] > $max_file_size) 
        {
            $message[] = "$filename is too large!";
            continue; // Skip large files
        }
        else // No error found! Move uploaded files 
        {
            // extract extension
            $ext = $files['extension'][$f];

            /** clean up the filename */
            $filename = strtr( $filename , $replacements );
            $filename = preg_replace('/[^-a-zA-Z0-9_.]/', '',$filename);
            
            // get filesize
            $size = $files['size'][$f];

            // set filetype for download
            $mimetype = $files['mimetype'][$f];

            /** filepath from given parameters */ 
            if($files['filetype'][$f] == 'log')
            {
                $id = $logs_assoc[$files['typename'][$f]]['id'];
                $filepath = $logs_assoc[$files['typename'][$f]]['path'];
                $type = 'log';
            }
            elseif($files['filetype'][$f] == 'model')
            {
                $id = $models_assoc[$files['typename'][$f]]['id'];
                $filepath = $models_assoc[$files['typename'][$f]]['path'];
                $type = 'model';
            }
            
            $uniqid =  uniqid('f', TRUE);

            // batchimporting files aren't checked for validation
            $valid = '2';

            /** create entry in the files table */
            $sql = "INSERT INTO
                                ".TBL_PREFIX."files
                                (fileName, path, type, foreignID, ext, fileType, uploader, timestamp, uniqid, size, valid, deletable)
                           VALUES
                                ('$filename','$filepath','$type','$id','$ext','$mimetype','$creator','$timestamp','$uniqid','$size','$valid','1')"; 

            if($res = $conid->prepare($sql)){
                $res->execute();
                $res->store_result();
            }
            else
            {
                die('error: ' .$conid->error);
            }

            $target = $filepath.$filename;

            if(rename($files['tmp_path'][$f], $target))
            {
                $count++; // Number of successfully uploaded file
            }
        }
    }
    echo "uploaded ".$count." files!";
}

?>
