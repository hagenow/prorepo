<?php
/** Creates a new log */
function createlog()
{
    $conid = db_connect();

    $logName = cleaninput($_POST['logName']);
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

    /** must set via POST from search form */ 
    $modelID = $_POST['modelid'];

    $sql = "INSERT INTO
                ".TBL_PREFIX."logs
                (logName, timestamp, lastupdate, comment, catID, modelID, creator, deletable)
                VALUES
                ('$logName','$timestamp','$timestamp','$comment','$catid', '$modelID','$creator', '1')";

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

function getlogs($catid)
{
    $conid = db_connect();

    $sql = "SELECT *
            FROM ".TBL_PREFIX."logs
            WHERE catID = '$catid'";

    if( $res = $conid->query($sql) ){

        while( $row = $res->fetch_assoc() )
        {
            $html = "";
            $html .= "<tr>";
            $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=logview&logID=".$row['logID']."\">".$row['logName']."</a></td>";
            $html .= "<td class=\"text-center\">".date("d.m.Y - H:i:s", strtotime($row['timestamp']))."</td>";
            $html .= "<td class=\"text-center\"><a href=\"".$_SERVER['PHP_SELF']."?show=usershow&name=".$row['creator']."\">".$row['creator']."</td>";
            $html .= "</tr>";

            echo $html;
        }
    }
    $conid->close();
}

function viewlog($logid)
{
    $conid = db_connect();

    $logvalues = array();

    $sql = "SELECT logID, logName, timestamp, lastupdate, comment, catID, modelID, path, deletable, creator
            FROM ".TBL_PREFIX."logs
            WHERE logID = '$logid'";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();
    $res->bind_result($logvalues['id'],$logvalues['name'],$logvalues['timestamp'],$logvalues['lastupdate'],$logvalues['comment'],$logvalues['catID'],$logvalues['modelID'],$logvalues['path'],$logvalues['deletable'],$logvalues['creator']);
    $res->fetch();


    if($res->affected_rows == 1)
    {
        $res->fetch();
        $conid->close();
        return $logvalues;
    }
}
function editlog($logid)
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
    if(isset($_POST['modid']))
    {
        $modid = $_POST['modid'];
    }

    $sql = "UPDATE ".TBL_PREFIX."logs
            SET lastupdate = '".$_POST['timestamp']."', comment = '$comment', catID = '$catid', modelID = '$modid'
            WHERE logID = '$logid'";

    if($res = $conid->prepare($sql))
    {
        $res->execute();

        $conid->close();
        return ($res->affected_rows==1) ? true : false;
    }
    else
    {
        echo $conid->error;
    }
}

function updatelog($logid)
{
    $conid = db_connect();

    $sql = "UPDATE ".TBL_PREFIX."logs
            SET lastupdate = '".$_POST['timestamp']."'
            WHERE logID = '$logid'";

    $res = $conid->prepare($sql);
    $res->execute();

    $conid->close();
    return ($res->affected_rows==1) ? true : false;
}

function connectedlogs($modelID)
{
    $conid = db_connect();

    $sql = "SELECT logID, logName, lastupdate, creator
            FROM ".TBL_PREFIX."logs
            WHERE modelID = '$modelID'";

    if( $res = $conid->query($sql) ){

        while( $row = $res->fetch_assoc() )
        {
            $html = "";
            $html .= "<tr>";
            $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=logview&&logID=".$row['logID']."\">".$row['logName']."</a></td>";
            $html .= "<td class=\"text-center\"><a href=\"".$_SERVER['PHP_SELF']."?show=usershow&name=".$row['creator']."\">".$row['creator']."</td>";
            $html .= "</tr>";
    
            echo $html;
        }
    }
    $conid->close();
}

// delete log
function removelog($logid)
{
    $conid = db_connect();

    $logid = cleaninput($logid);

    $sql = "DELETE FROM ".TBL_PREFIX."logs
            WHERE logID = '$logid'";

    if($res = $conid->prepare($sql))
    {
        $res->execute();
        return true;
    }
    else
    {
        echo $conid->error;
    }
    $conid->close();
}

/** Batchimporting logs */
function batchimport_createlog($name, $timestamp, $catid, $modelid)
{
    $conid = db_connect();

    $timestamp = $_POST['timestamp'];

    $catid = $_POST['catid'];
    $creator = $_SESSION['user'];

    $sql = "INSERT INTO
                ".TBL_PREFIX."logs
                (logName, timestamp, lastupdate, catID, creator, deletable, modelID)
                VALUES
                ('$name','$timestamp','$timestamp','$catid','$creator', '1', $modelid)";

    if($res = $conid->prepare($sql)){
        $res->execute();
        $res->store_result();
        $id = $conid->insert_id;
    }
    else
    {
        echo $conid->error;
    }

    $conid->close();
    return $id;
}

function checklogexist($name)
{
    $conid = db_connect();

    $sql = "SELECT logID
            FROM ".TBL_PREFIX."logs
            WHERE logName = '$name'";

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
    return 0;
}
?>
