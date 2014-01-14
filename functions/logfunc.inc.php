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

    var_dump($logName, $timestamp, $comment, $catid, $creator, $modelID);

    $sql = "INSERT INTO
                ".TBL_PREFIX."logs
                (logName, timestamp, comment, catID, modelID, creator)
                VALUES
                ('$logName','$timestamp','$comment','$catid', '$modelID','$creator')";
                // ('".$logName."','".$timestamp."','".$comment."','".$catid."', '".$modelID."','".$creator."')";

    if($res = $conid->prepare($sql)){
        $res->execute();
        $res->store_result();
    }
    else
    {
        echo $conid->error;
    }

    // return($res->affected_rows == 1) ? true : false;

    $typeinfo = array('name' => $logName, 'timestamp' => $timestamp, 'id' => mysqli_insert_id($conid));

    return $typeinfo;
}
