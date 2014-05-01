<?php 

function libxml_display_error($error) 
{ 
    $return = "<br/>\n"; 
    switch ($error->level) 
    { 
    case LIBXML_ERR_WARNING: 
    $return .= "<b>Warning $error->code</b>: "; 
    break; 
    case LIBXML_ERR_ERROR: 
    $return .= "<b>Error $error->code</b>: "; 
    break; 
    case LIBXML_ERR_FATAL: 
    $return .= "<b>Fatal Error $error->code</b>: "; 
    break; 
    } 

    $return .= trim($error->message); 
    if ($error->file) 
    { 
        $return .= " in <b>$error->file</b>"; 
    } 
    $return .= " on line <b>$error->line</b>\n"; 
    
    return $return; 
} 

function libxml_display_errors() 
{ 
    $errors = libxml_get_errors(); 
    foreach ($errors as $error) 
    { 
        print libxml_display_error($error); 
    } 
    libxml_clear_errors(); 
} 


function validatePNML($file)
{
    // file to be loaded must have extension pnml, but will checked before this 
    // function is called
    //

    // create objeckt $parser, which holds content from $file
    $parser = simplexml_load_file($file);

    // check if property net exists in loaded XML file
    if(!property_exists($parser, 'net'))
    { 
        return false;
    } 
    else 
    { 
        // if net exists, check emptyness
        if($parser->net[0]['id'] == '')
        {
            return false;
    	} 
    }
    // otherwise run shellscript and returns true, if validates can be found in 
    // a line of the html file - otherwise return false!
    $result_shell = shell_exec(PNMLSchema." $file 2>&1");
    // $result_shell = shell_exec(PNMLSchema." ".$file);

    // load result from HTML file under PNMLReport (look at config.inc.php)
    $html = file_get_contents(PNMLReport);

    if(strpos($html, 'validates') !== false)
    { 

        return (unlink(PNMLReport) && true);
    }
    else 
    { 
        return (unlink(PNMLReport) && false);
    }
}

function validateMXML($file)
{
    // Enable user error handling 
    libxml_use_internal_errors(true); 
    
    $xml = new DOMDocument(); 
    $xml->load($file); 
    
    if (!$xml->schemaValidate(MXMLSchema)) 
    { 
        // echo '<b>Errors Found!</b>'; 
        // libxml_display_errors(); 
        return false;
    } 
    else 
    { 
        return true;
    }
}

function validateXES($file)
{
    // Enable user error handling 
    libxml_use_internal_errors(true); 
    
    $xml = new DOMDocument(); 
    $xml->load($file); 
    
    if (!$xml->schemaValidate(XESSchema)) 
    { 
        // echo '<b>Errors Found!</b>'; 
        // libxml_display_errors(); 
        return false;
    } 
    else 
    { 
        return true;
    }
}

?>
