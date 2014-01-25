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

    $sql = "INSERT INTO ".TBL_PREFIX."groups
                    (`groupName`, `timestamp`, `state`)
            VALUES  ('".$_POST['groupName']."', NOW(), 0)";

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

function removelogfromgroup()
{
    if(in_array($_GET['logID'], $_SESSION['grplogs']))
    {
        // place code here to remove the element
        echo "Already added!";
    }
    else
    {
        array_push($_SESSION['grplogs'], $_GET['logID']);
        echo "Added successfully!";
    }
}

function removemodelfromgroup()
{
    if(in_array($_GET['modelID'], $_SESSION['grpmodels']))
    {
        // place code here to remove the element
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
                    (groupID, modelID)
                  VALUES
                    ('$groupid', ?)";

    $res = $conid->prepare($sqlmodels);
    $res->bind_param('i',$modelid);

    // execute for all modelids in array
    foreach($models as $id)
    {
        $modelid = $id;
        $res->execute();
    }

    $sqllogs = "INSERT INTO ".TBL_PREFIX."loggroups
                    (groupID, logID)
                  VALUES
                    ('$groupid', ?)";

    $res = $conid->prepare($sqllogs);
    $res->bind_param('i',$logid);

    // execute for all logids in array
    foreach($logs as $logid)
    {
        $res->execute();
    }

    $conid->close();

    echo "Saved all entries!";

    unset($_SESSION['grpmodels']);
    unset($_SESSION['grplogs']);
    unset($_SESSION['groupID']);
    unset($_SESSION['groupName']);

    return true;
}
?>
