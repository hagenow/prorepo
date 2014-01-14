<?php
/** Creates a new log */
function createlog()
{
    $conid = db_connect();

    $logName = cleaninput($_POST['logName']);
    $timestamp = $_POST['timestamp'];
    $comment = cleaninput($_POST['comment']);
    $catid = $_POST['catid'];
    $creator = $_SESSION['user'];
    
    $modelID = $_POST['modelid'];

    $sql = "INSERT INTO
                ".TBL_PREFIX."logs
                (logName, timestamp, comment, catID, modelID, creator)
                VALUES
                ('".$logName."','".$timestamp."','".$comment."','".$catid."', '".$modelID."'
                    (SELECT userID
                     FROM ".TBL_PREFIX."users
                     WHERE login ='".$creator."'))";

    $res = $conid->prepare($sql);
    $res->execute();

    $typeinfo = ('logName' => $logName, 'timestamp' => $timestamp, 'modelID' => mysqli_insert_id($conid));

    return $typeinfo;
}
