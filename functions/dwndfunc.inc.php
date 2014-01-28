<?php 
function getfile($uniqid) 
{
    $conid = db_connect();

    $sql = "SELECT fileName, path, fileType
            FROM ".TBL_PREFIX."files
            WHERE `uniqid` = '$uniqid'";

    $res = $conid->query($sql);
    if( $res = $conid->query($sql) ) {
        while( $row = $res->fetch_assoc()) {

            $file = $row['fileName'];
            $type = $row['fileType'];
            $path = $row['path'];
            
            header("Content-Type: $type");
            header("Content-Disposition: attachment; filename=\"$file\"");
            header("Content-length: " . filesize($file)); 

            readfile($path.$file);
        }
    }
    
}

function getzip($file,$path)
{
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"$file\"");
    header("Content-length: " . filesize($path.$file)); 
    header("Content-Transfer-Encoding: binary");

    ob_clean();
    flush();

    readfile($path.$file);
    
    // after download, delete this file
    unlink($path.$file);
}
?>
