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

    /** must set via POST from search form */ 
    $modelID = $_POST['modelid'];

    $sql = "INSERT INTO
                ".TBL_PREFIX."logs
                (logName, timestamp, comment, catID, modelID, creator)
                VALUES
                ('$logName','$timestamp','$comment','$catid', '$modelID',
                (SELECT userID from ".TBL_PREFIX."users WHERE login ='$creator'))";

    if($res = $conid->prepare($sql)){
        $res->execute();
        $res->store_result();
    }
    else
    {
        echo $conid->error;
    }

    $typeinfo = array('name' => $logName, 'timestamp' => $timestamp, 'id' => mysqli_insert_id($conid));

    return $typeinfo;
}
