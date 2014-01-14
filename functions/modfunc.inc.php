<?php
/** Creates a new model */
function createmodel()
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
                ('".$modelName."','".$timestamp."','".$comment."','".$catid."',
                    (SELECT userID
                     FROM ".TBL_PREFIX."users
                     WHERE login ='".$creator."'))";

    $res = $conid->prepare($sql);
    $res->execute();

    $typeinfo = array('name' => $modelName, 'timestamp' => $timestamp, 'id' => mysqli_insert_id($conid));

    return $typeinfo;
}
