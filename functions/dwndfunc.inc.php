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

            readfile($path.$file);
        }
    }
    
}
?>
