<?php
/** Creates a new log */
function createmod()
{
    $conid = db_connect();

    $modelName = cleaninput($_POST['modelName']);
    $timestamp = $_POST['timestamp'];
    $comment = cleaninput($_POST['comment']);
    $catid = $_POST['catid'];
    $creator = $_SESSION['user'];

    $sql = "INSERT INTO
                ".TBL_PREFIX."models
                (modelName, timestamp, comment, catID, creator)
                VALUES
                ('$modelName','$timestamp','$comment','$catid',
                (SELECT userID from ".TBL_PREFIX."users WHERE login ='$creator'))";

    if($res = $conid->prepare($sql)){
        $res->execute();
        $res->store_result();
    }
    else
    {
        echo $conid->error;
    }

    $typeinfo = array('name' => $modelName, 'timestamp' => $timestamp, 'id' => mysqli_insert_id($conid));

    return $typeinfo;
}
