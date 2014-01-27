<?php
/* create a groupentry from given parameters
 * TODO: clean tags to an comma-separated list and then insert them as a list
 * */
function creategroup()
{
    unset($_SESSION['models']);
    unset($_SESSION['logs']);

    /* this is not the elegant solutions - an double-array 
        * array(array,array,...) would be the best, but this is now the easiest 
        * way ;-)
        * */
    $models = array();
    $logs = array();

    $_SESSION['grpmodels'] = $models;
    $_SESSION['grplogs'] = $logs;
    $_SESSION['groupName'] = $_POST['groupName'];

    $conid = db_connect();
    
    $guid = guid();

    $sql = "INSERT INTO ".TBL_PREFIX."groups
                    (groupName, timestamp, guid, creator, state)
            VALUES  ('".$_POST['groupName']."', NOW(), '$guid', '".$_SESSION['user']."','1')";

    if($res = $conid->prepare($sql))
    {
        $res->execute();
        $res->store_result();
        $id = $conid->insert_id;
        $_SESSION['groupID'] = $id;
        $conid->close();
        return true;
    }
    else
    {
        echo $conid->error;
        $conid->close();
        return false;
    }
}

function addlog2group()
{
    if(in_array($_GET['logID'], $_SESSION['grplogs']))
    {
        echo "Already added!";
    }
    else
    {
        array_push($_SESSION['grplogs'], $_GET['logID']);
        echo "Added successfully!";
    }
}

function addmodel2group()
{
    if(in_array($_GET['modelID'], $_SESSION['grpmodels']))
    {
        echo "Already added!";
    }
    else
    {
        array_push($_SESSION['grpmodels'], $_GET['modelID']);
        echo "Added successfully!";
    }
}

function getnamesfromgroup($type,$key,$typeid)
{
    $conid = db_connect();

    $sql = "SELECT ".$type."ID,".$type."Name, creator
            FROM ".TBL_PREFIX.$type."s
            WHERE ".$type."ID = '$typeid'";

    if( $res = $conid->query($sql) ){

        while( $row = $res->fetch_assoc() )
        {
            $html = "";
            $html .= "<tr>";
            if($type == "model")
            {
                $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=modview&modelID=".$row['modelID']."\">".$row['modelName']."</a></td>";
            }
            if($type == "log")
            {
                $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=logview&logID=".$row['logID']."\">".$row['logName']."</a></td>";
            }

            $html .= "<td class=\"text-center\"><a href=\"".$_SERVER['PHP_SELF']."?show=user&name=".$row['creator']."\">".$row['creator']."</td>";

            if($type == "model")
            {
                $html .= "<td class=\"text-center\">";
                $html .= "<button type=\"submit\" class=\"btn btn-default btn-sm\" name=\"removemodel\" value=\"".$key."\">";
                $html .= "<span class=\"glyphicon glyphicon-minus\"></span> Remove from group</button>";
                $html .= "</td>";
            }
            if($type == "log")
            {
                $html .= "<td class=\"text-center\">";
                $html .= "<button type=\"submit\" class=\"btn btn-default btn-sm\" name=\"removelog\" value=\"".$key."\">";
                $html .= "<span class=\"glyphicon glyphicon-minus\"></span> Remove from group</button>";
                $html .= "</td>";
            }
            $html .= "</tr>";

            echo $html;
        }
    }
    echo $conid->error;
    $conid->close();
}

function savegroup()
{
    $conid = db_connect();

    $models = $_SESSION['grpmodels'];
    $logs = $_SESSION['grplogs'];
    $groupid = $_SESSION['groupID'];

    $sqlmodels = "INSERT INTO ".TBL_PREFIX."modelgroups
                    (groupID, modelID, timestamp)
                  VALUES
                    ('$groupid', ?, ?)";

    // execute for all modelids in array
    if($res = $conid->prepare($sqlmodels))
    {
        $res->bind_param('is',$modelid,$modeltimestamp);

        foreach($models as $entry)
        {
            $parts = explode("|",$entry);
            $modelid = $parts[0];
            $modeltimestamp = $parts[1];
            $res->execute();
        }
    }
    else
        echo $conid->error;

    $sqllogs = "INSERT INTO ".TBL_PREFIX."loggroups
                    (groupID, logID, timestamp)
                  VALUES
                    ('$groupid', ?, ?)";

    if($res = $conid->prepare($sqllogs))
    {
        $res->bind_param('is',$logid, $logtimestamp);

        // execute for all logids in array
        foreach($logs as $entry)
        {
            $parts = explode("|",$entry);
            $logid = $parts[0];
            $logtimestamp = $parts[1];
            $res->execute();
        }
    }
    else
        echo $conid->error;

    $conid->close();

    echo "Saved all entries!";

    unset($_SESSION['grpmodels']);
    unset($_SESSION['grplogs']);
    unset($_SESSION['groupID']);
    unset($_SESSION['groupName']);

    return true;
}

function viewgroup($groupid)
{
    $conid = db_connect();

    $groupvalues = array();

    $sql = "SELECT groupID, groupName, timestamp, guid, creator, state
            FROM ".TBL_PREFIX."groups
            WHERE groupID = '$groupid'";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();
    $res->bind_result($groupvalues['id'],$groupvalues['name'],$groupvalues['timestamp'],$groupvalues['guid'],$groupvalues['creator'],$groupvalues['state']);
    $res->fetch();


    if($res->affected_rows == 1)
    {
        $res->fetch();
        $groupvalues['timestamp'] = date("d.m.Y", strtotime($groupvalues['timestamp']));
        $conid->close();
        return $groupvalues;
    }
}

function linkedtypes($id,$type)
{
    $conid = db_connect();

    $sql = "SELECT ".$type."ID
            FROM ".TBL_PREFIX.$type."groups
            WHERE groupID = $id";

    if( $res = $conid->query($sql) )
    {

        while( $row = $res->fetch_assoc() )
        {
            if($type == "model")
            {
                $values = viewmodel($row['modelID']);

                $html = "";
                $html .= "<tr>";
                $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=modview&modelID=".$values['id']."&timestamp=".date("YmdHis", strtotime($values['timestamp']))."\">".$values['name']."</a></td>";
                $html .= "<td class=\"text-center\">".date("d.m.Y - H:i:s", strtotime($values['timestamp']))."</td>";
                $html .= "<td class=\"text-center\">";
                $html .= "<button type=\"submit\" class=\"btn btn-default btn-sm\" name=\"removegroupmodel\" value=\"".$values['id']."|".$id."\">";
                $html .= "<span class=\"glyphicon glyphicon-minus\"></span> Remove from group</button>";
                $html .= "</td>";
                $html .= "</tr>";

                echo $html;
            }

            if($type == "log")
            {
                $values = viewlog($row['logID']);

                $html = "";
                $html .= "<tr>";
                $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=logview&logID=".$values['id']."&timestamp=".date("YmdHis", strtotime($values['timestamp']))."\">".$values['name']."</a></td>";
                $html .= "<td class=\"text-center\">".date("d.m.Y - H:i:s", strtotime($values['timestamp']))."</td>";
                $html .= "<td class=\"text-center\">";
                $html .= "<button type=\"submit\" class=\"btn btn-default btn-sm\" name=\"removegrouplog\" value=\"".$values['id']."|".$id."\">";
                $html .= "<span class=\"glyphicon glyphicon-minus\"></span> Remove from group</button>";
                $html .= "</td>";
                $html .= "</tr>";

                echo $html;
            }
        }
    }
    echo $conid->error;
    $conid->close();
}

function deletefromgroup($type,$id)
{
    $conid = db_connect();

    $parts = explode("|",$id);
    $id = $parts[0];
    $groupid = $parts[1];

    $sql = "DELETE FROM ".TBL_PREFIX.$type."groups 
            WHERE groupID = '$groupid'
            AND ".$type."ID = '$id'";

    $res = $conid->query($sql);

    echo $conid->error;
    return ($res->affected_rows == 1) ? true : false;
}

?>
