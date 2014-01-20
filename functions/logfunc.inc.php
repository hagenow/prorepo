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
                (logName, timestamp, lastupdate, comment, catID, modelID, creator)
                VALUES
                ('$logName','$timestamp','$timestamp','$comment','$catid', '$modelID','$creator')";

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
            $date = date("d.m.Y", strtotime($row['timestamp']));
            $html = "";
            $html .= "<tr>";
            $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=logview&catID=".$_GET['catID']."&logID=".$row['logID']."\">".$row['logName']."</a></td>";
            $html .= "<td>".$date."</td>";
            $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=user&name=".$row['creator']."\">".$row['creator']."</td>";
            $html .= "</tr>";

            echo $html;
        }
    }
    $conid->close();
}

function viewlog($modelid)
{

}
?>
