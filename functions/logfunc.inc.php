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
    
    // check private flag
    if(isset($_POST['private']) && $_POST['private'] == TRUE)
        $private = 1;
    else
        $private = 0;

    /** must set via POST from search form */ 
    $modelID = $_POST['modelid'];

    $sql = "INSERT INTO
                ".TBL_PREFIX."logs
                (logName, timestamp, lastupdate, comment, catID, modelID, creator, deletable, private)
                VALUES
                ('$logName','$timestamp','$timestamp','$comment','$catid', '$modelID','$creator', '1','$private')";

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
    if($catid == '')
    {
        header( 'location: index.php?show=404' );
    }
    $catid = cleaninput($catid);
    $conid = db_connect();

    if(!isadmin())
    {
        $sql = "SELECT *
                FROM ".TBL_PREFIX."logs
                WHERE catID = '$catid' AND private = '0'";
    }
    else
    {
        $sql = "SELECT *
                FROM ".TBL_PREFIX."logs
                WHERE catID = '$catid'";
    }

    if( $res = $conid->query($sql) ){

        if($conid->affected_rows > 0)
        {
            while( $row = $res->fetch_assoc() )
            {
                $html = "";
                $html .= "<tr>";
                $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=logview&logID=".$row['logID']."\">".$row['logName']."</a></td>";
                $html .= "<td class=\"text-center\">".date("d.m.Y - H:i:s", strtotime($row['timestamp']))."</td>";
                $html .= "<td class=\"text-center\"><a href=\"".$_SERVER['PHP_SELF']."?show=usershow&name=".$row['creator']."\">".$row['creator']."</td>";
                if($row['deletable'] == "1" && isadmin())
                    $html .= "<td class=\"text-center\"><button type=\"submit\" class=\"btn btn-link\" name=\"deletelog\" value=\"".$row['logID']."\"><span class=\"glyphicon glyphicon-remove\"></span></button></td>";
                elseif($row['deletable'] == "0" && isadmin())
                    $html .= "<td class=\"text-center\"></td>";
                $html .= "</tr>";

                echo $html;
            }
        }
        else
            header( 'location: index.php?show=404' );
    }
    $conid->close();
}

function viewlog($logid)
{
    if($logid == '')
    {
        header( 'location: index.php?show=404' );
    }

    $logid = cleaninput($logid);
    $conid = db_connect();

    $logvalues = array();

    $sql = "SELECT logID, logName, timestamp, lastupdate, comment, catID, modelID, path, deletable, creator, private
            FROM ".TBL_PREFIX."logs
            WHERE logID = '$logid'";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();
    $res->bind_result($logvalues['id'],$logvalues['name'],$logvalues['timestamp'],$logvalues['lastupdate'],$logvalues['comment'],$logvalues['catID'],$logvalues['modelID'],$logvalues['path'],$logvalues['deletable'],$logvalues['creator'],$logvalues['private']);
    $res->fetch();


    if($res->affected_rows == 1)
    {
        $res->fetch();
        $conid->close();
        return $logvalues;
    }
    else
        header( 'location: index.php?show=404' );
}
function editlog($logid)
{
    $logid = cleaninput($logid);
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
    // check private flag
    if(isset($_POST['private']) && $_POST['private'] == TRUE)
    {
        echo "This log is now in private mode. If it is included in a public group, it is now hidden!<br>Administrators and you are able to see and use this log!<br>";
        $private = 1;
    }
    else
    {
        echo "This log is no longer in private mode.<br>";
        $private = 0;
    }

    $sql = "UPDATE ".TBL_PREFIX."logs
            SET lastupdate = '".$_POST['timestamp']."', comment = '$comment', catID = '$catid', modelID = '$modid', private = '$private'
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
    $logid = cleaninput($logid);
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
    $modelID = cleaninput($modelID);
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

// delete log if it's marked as deletable
function removelog($logid,$basepath)
{
    $logid = cleaninput($logid);
    $conid = db_connect();

    $logid = cleaninput($logid);

    $sql = "DELETE FROM ".TBL_PREFIX."logs
            WHERE logID = '$logid'";

    if($res = $conid->prepare($sql))
    {
        $res->execute();
        $conid->close();
        deletefiles('log',$logid,$basepath);
        return true;
    }
    else
    {
        echo $conid->error;
        return false;
    }
    $conid->close();
}

/** Batchimporting logs */
function batchimport_createlog($name, $timestamp, $catid, $modelid)
{
    $catid = cleaninput($catid);
    $modelid = cleaninput($modelid);
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

    $sql = "SELECT logID,path
            FROM ".TBL_PREFIX."logs
            WHERE logName = '$name'";

    $values = array();

    if($res = $conid->prepare($sql))
    {
        $res->execute();
        $res->store_result();
        $res->bind_result($id,$path);
        $res->fetch();
        $values['id'] = $id;
        $values['path'] = $path;
        return $values; 
    }
    else
    {
        echo $conid->error;
    }

    $conid->close();
}
?>
