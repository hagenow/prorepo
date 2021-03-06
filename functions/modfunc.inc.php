<?php
/** Creates a new mod */
function createmodel()
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

    // check private flag
    if(isset($_POST['private']) && $_POST['private'] == TRUE)
        $private = 1;
    else
        $private = 0;

    $sql = "INSERT INTO
                ".TBL_PREFIX."models
                (modelName, timestamp, lastupdate, comment, catID, creator, deletable, private)
                VALUES
                ('$modelName','$timestamp','$timestamp','$comment','$catid','$creator', '1','$private')";

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
    if($catid == '')
    {
        header( 'location: index.php?show=404' );
    }
    $catid = cleaninput($catid);
    $conid = db_connect();

    if(!isadmin())
    {
        $sql = "SELECT *
                FROM ".TBL_PREFIX."models
                WHERE catID = '$catid' AND private = '0'";
    }
    else
    {
        $sql = "SELECT *
                FROM ".TBL_PREFIX."models
                WHERE catID = '$catid'";
    }

    if( $res = $conid->query($sql) ){
        if($conid->affected_rows > 0)
        {

            while( $row = $res->fetch_assoc() )
            {
                $html = "";
                $html .= "<tr>";
                $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=modview&modelID=".$row['modelID']."\">".$row['modelName']."</a></td>";
                $html .= "<td class=\"text-center\">".date("d.m.Y - H:i:s", strtotime($row['timestamp']))."</td>";
                $html .= "<td class=\"text-center\"><a href=\"".$_SERVER['PHP_SELF']."?show=usershow&name=".$row['creator']."\">".$row['creator']."</td>";
                if($row['deletable'] == "1" && isadmin())
                    $html .= "<td class=\"text-center\"><button type=\"submit\" class=\"btn btn-link\" name=\"deletemodel\" value=\"".$row['modelID']."\"><span class=\"glyphicon glyphicon-remove\"></span></button></td>";
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

function viewmodel($modelid)
{
    if($modelid == '')
    {
        header( 'location: index.php?show=404' );
    }
    $modelid = cleaninput($modelid);
    $conid = db_connect();

    $modvalues = array();

    $sql = "SELECT modelID, modelName, timestamp, lastupdate, comment, catID, path, deletable, creator, private
            FROM ".TBL_PREFIX."models
            WHERE modelID = '$modelid'";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();
    $res->bind_result($modvalues['id'],$modvalues['name'],$modvalues['timestamp'],$modvalues['lastupdate'],$modvalues['comment'],$modvalues['catID'],$modvalues['path'],$modvalues['deletable'],$modvalues['creator'],$modvalues['private']);
    $res->fetch();


    if($res->affected_rows == 1)
    {
        $res->fetch();
        $conid->close();
        return $modvalues;
    }
    else
        header( 'location: index.php?show=404' );

}

function editmodel($modelid)
{
    $modelid = cleaninput($modelid);
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
    
    // check private flag
    if(isset($_POST['private']) && $_POST['private'] == TRUE)
    {
        echo "This model is now in private mode. If it is included in a public group, it is now hidden!<br>Administrators and you are able to see and use this model!<br>";
        $private = 1;
    }
    else
    {
        echo "This log is no longer in private mode.<br>";
        $private = 0;
    }

    $sql = "UPDATE ".TBL_PREFIX."models
            SET lastupdate = '".$_POST['timestamp']."', comment = '$comment', catID = '$catid', private = '$private'
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
    $modelid = cleaninput($modelid);
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
    $modid = cleaninput($modid);
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
        $conid->close();
        return $id;
    }
    else
    {
        echo $conid->error;
    }
    $conid->close();
}

// delete model, if the model is marked as deletable
function removemodel($modid,$basepath)
{
    $conid = db_connect();

    $modid = cleaninput($modid);

    $sql = "DELETE FROM ".TBL_PREFIX."models
            WHERE modelID = '$modid'";

    if($res = $conid->prepare($sql))
    {
        $res->execute();
        $conid->close();
        deletefiles('model',$modid,$basepath);
        return true;
    }
    else
    {
        echo $conid->error;
        return false;
    }
    $conid->close();
}

/** Batchimporting models */
function batchimport_createmodel($name, $timestamp, $catid)
{
    $conid = db_connect();

    $timestamp = cleaninput($_POST['timestamp']);

    $catid = cleaninput($_POST['catid']);
    $creator = cleaninput($_SESSION['user']);

    $sql = "INSERT INTO
                ".TBL_PREFIX."models
                (modelName, timestamp, lastupdate, catID, creator, deletable)
                VALUES
                ('$name','$timestamp','$timestamp','$catid','$creator', '1')";

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
// check if model exists, if yes, return id if no, return 0
function checkmodelexist($name)
{
    $conid = db_connect();

    $sql = "SELECT modelID,path
            FROM ".TBL_PREFIX."models
            WHERE modelName = '$name'";

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
    return 0;
}
?>
