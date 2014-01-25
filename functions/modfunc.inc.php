<?php
/** Creates a new mod */
function createmodel()
{
    $conid = db_connect();

    $modelName = cleaninput($_POST['modelName']);
    $timestamp = $_POST['timestamp'];

    /* special treatment for comments */
    $comment = $_POST['comment'];
    // $comment = filter_var($comment ,FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    // falls es in die Datenbank kommen soll, wird es noch mal escaped
    $conid->real_escape_string( $comment );
    // slashes entfernen, falls noch welche vorhanden oder anders codiert
    $comment = stripslashes( $comment );
    // TAGs entfernen
    $comment = strip_tags($comment);
    // Zeilenumbrüche hinzufügen
    $comment = nl2br($comment);

    $catid = $_POST['catid'];
    $creator = $_SESSION['user'];

    $sql = "INSERT INTO
                ".TBL_PREFIX."models
                (modelName, timestamp, lastupdate, comment, catID, creator)
                VALUES
                ('$modelName','$timestamp','$timestamp','$comment','$catid','$creator')";

    if($res = $conid->prepare($sql)){
        $res->execute();
        $res->store_result();
        $id = $conid->insert_id;
    }
    else
    {
        echo $conid->error;
    }

    $typeinfo = array('name' => $modelName, 'timestamp' => $timestamp, 'id' => $id);

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

        while( $row = $res->fetch_assoc() )
        {
            $date = date("d.m.Y", strtotime($row['timestamp']));
            $html = "";
            $html .= "<tr>";
            $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=modview&modelID=".$row['modelID']."\">".$row['modelName']."</a></td>";
            $html .= "<td>".$date."</td>";
            $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=user&name=".$row['creator']."\">".$row['creator']."</td>";
            $html .= "</tr>";

            echo $html;
        }
    }
    $conid->close();
}

function viewmodel($modelid)
{
    $conid = db_connect();

    $modvalues = array();

    $sql = "SELECT modelID, modelName, timestamp, lastupdate, comment, catID, path, creator
            FROM ".TBL_PREFIX."models
            WHERE modelID = '$modelid'";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();
    $res->bind_result($modvalues['id'],$modvalues['name'],$modvalues['timestamp'],$modvalues['lastupdate'],$modvalues['comment'],$modvalues['catID'],$modvalues['path'],$modvalues['creator']);
    $res->fetch();


    if($res->affected_rows == 1)
    {
        $res->fetch();
        $modvalues['timestamp'] = date("d.m.Y", strtotime($modvalues['timestamp']));
        $modvalues['lastupdate'] = date("d.m.Y - H:i:s", strtotime($modvalues['lastupdate']));
        $conid->close();
        return $modvalues;
    }
}

function editmodel($modelid)
{
    $conid = db_connect();
    $comment = "";
    $catid = "";

    if(isset($_POST['comment']) && strlen($_POST['comment']) > 0)
    {
        /* special treatment for comments */
        $comment = $_POST['comment'];
        // $comment = filter_var($comment ,FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
        // falls es in die Datenbank kommen soll, wird es noch mal escaped
        $conid->real_escape_string( $comment );
        // slashes entfernen, falls noch welche vorhanden oder anders codiert
        $comment = stripslashes( $comment );
        // TAGs entfernen
        $comment = strip_tags($comment);
        // Zeilenumbrüche hinzufügen
        $comment = nl2br($comment);
    }
    else
    {
        $comment = $_POST['oldcomment']; 
    }
    if(isset($_POST['catid']))
    {
        $catid = $_POST['catid'];
    }

    $sql = "UPDATE ".TBL_PREFIX."models
            SET lastupdate = '".$_POST['timestamp']."', comment = '$comment', catID = '$catid'
            WHERE modelID = '$modelid'";

    if($res = $conid->prepare($sql))
    {
        $res->execute();

        $conid->close();
        return ($res->affected_rows==1) ? true : false;
    }
    else
        echo $conid->error;
}

function updatemodel($modelid)
{
    $conid = db_connect();

    $sql = "UPDATE ".TBL_PREFIX."models
            SET lastupdate = '".$_POST['timestamp']."'
            WHERE modelID = '$modelid'";

    $res = $conid->prepare($sql);
    $res->execute();

    $conid->close();
    return ($res->affected_rows==1) ? true : false;
}

function getmodname($modid)
{
    $conid = db_connect();

    $sql = "SELECT modelName
            FROM ".TBL_PREFIX."models
            WHERE modelID = '$modid'";

    if($res = $conid->prepare($sql))
    {
        $res->execute();
        $res->store_result();
        $res->bind_result($id);
        $res->fetch();

        return $id;
    }
    else
    {
        echo $conid->error;
    }
    $conid->close();
}

?>
