<?php
/** Creates a new mod */
function createmod()
{
    $conid = db_connect();

    $modelName = cleaninput($_POST['modelName']);
    $timestamp = $_POST['timestamp'];

    /* special treatment for comments */
    $comment = $_POST['comment'];
    $comment = filter_var($comment ,FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    // falls es in die Datenbank kommen soll, wird es noch mal escaped
    $conid->real_escape_string( $comment );
    // slashes entfernen, falls noch welche vorhanden oder anders codiert
    $comment = stripslashes( $comment );
    // Zeilenumbrüche hinzufügen
    $comment = nl2br($comment);

    $catid = $_POST['catid'];
    $creator = $_SESSION['user'];

    $sql = "INSERT INTO
                ".TBL_PREFIX."models
                (modelName, timestamp, comment, catID, creator)
                VALUES
                ('$modelName','$timestamp','$comment','$catid','$creator')";

    if($res = $conid->prepare($sql)){
        $res->execute();
        $res->store_result();
    }
    else
    {
        echo $conid->error;
    }

    $typeinfo = array('name' => $modelName, 'timestamp' => $timestamp, 'id' => mysqli_insert_id($conid));

    $conid->close();
    return $typeinfo;
}

function getmodels($catid)
{
    $conid = db_connect();

    $sql = "SELECT *
            FROM ".TBL_PREFIX."models
            WHERE catID = '$catid'";

    if( $res = $conid->query($sql) ){

        while( $row = $res->fetch_assoc())
        {
            echo "<a href=\"".echo $_SERVER['PHP_SELF']."?show=cat2\"  class=\"list-group-item\">".$row['modelName']."</a>";
        }
    }

}


?>
