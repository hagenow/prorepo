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
    $_SESSION['groupTags'] = $_POST['groupTags'];

    $_SESSION['groupName'] = cleaninput($_SESSION['groupName']);
    $_SESSION['groupTags'] = cleantags($_SESSION['groupTags']);

    // check private flag
    if(isset($_POST['private']) && $_POST['private'] == TRUE)
    {
        echo "This group is now in private mode. Administrators can see this group, when they logged in!<br>";
        $private = 1;
    }
    else
    {
        echo "This log is no longer in private mode. Included private models and logs are already hidden!<br>";
        $private = 0;
    }

    $conid = db_connect();
    
    $guid = guid();

    $sql = "INSERT INTO ".TBL_PREFIX."groups
                    (groupName, timestamp, guid, creator, state, tags, private)
            VALUES  ('".$_SESSION['groupName']."', NOW(), '$guid', '".$_SESSION['user']."','1','".$_SESSION['groupTags']."', '$private')";

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

function initgroup($id)
{
    $id = cleaninput($id);
    $models = array();
    $logs = array();
    $models_edit = array();
    $logs_edit = array();


    $_SESSION['grpmodels'] = $models_edit;
    $_SESSION['grplogs'] = $logs_edit;
    $_SESSION['groupID'] = $id;

    $_SESSION['grpoldmodels'] = $models;
    $_SESSION['grpoldlogs'] = $logs;

    $_SESSION['updateflag'] = true;

    $conid = db_connect();

    $sqllogs = "SELECT logID
                FROM ".TBL_PREFIX."loggroups
                WHERE groupID = $id";
    
    $sqlmodels = "SELECT modelID
                  FROM ".TBL_PREFIX."modelgroups
                  WHERE groupID = $id";

    // read linked logs from group
    if( $res = $conid->query($sqllogs) )
    {
        while( $row = $res->fetch_assoc() )
        {
            $val = $row['logID'];
            array_push($_SESSION['grpoldlogs'], $val); 
        }
    }
    else
    {
        echo $conid-error;
        $conid->close();
    }

    // read linked models from group
    if( $res = $conid->query($sqlmodels) )
    {
        while( $row = $res->fetch_assoc() )
        {
            $val = $row['modelID'];
            array_push($_SESSION['grpoldmodels'], $val); 
        }
    }
    else
    {
        echo $conid-error;
        $conid->close();
    }

    $conid->close();
}

function addlog2group()
{
    if(in_array($_POST['logID'], $_SESSION['grplogs']) && !isset($_SESSION['updateflag']) || 
        in_array($_POST['logID'], $_SESSION['grplogs']) && isset($_SESSION['updateflag'])|| 
        isset($_SESSION['grpoldlogs']) && in_array($_POST['logID'], $_SESSION['grpoldlogs']) && isset($_SESSION['updateflag']))
    {
        echo "Already added!";
    }
    elseif(!in_array($_POST['logID'], $_SESSION['grplogs']) && isset($_SESSION['updateflag']))
    {
        array_push($_SESSION['grplogs'], $_POST['logID']);
        echo "Added successfully!";
    }
    else
    {
        array_push($_SESSION['grplogs'], $_POST['logID']);
        echo "Added successfully!";
    }
}

function addmodel2group()
{
    if(in_array($_POST['modelID'], $_SESSION['grpmodels']) && !isset($_SESSION['updateflag']) || 
        in_array($_POST['modelID'], $_SESSION['grpmodels']) && isset($_SESSION['updateflag']) || 
        isset($_SESSION['grpoldmodels']) && in_array($_POST['modelID'], $_SESSION['grpoldmodels']) && isset($_SESSION['updateflag'])) 
    {
        echo "Already added!";
    }
    elseif(!in_array($_POST['modelID'], $_SESSION['grpmodels']) && isset($_SESSION['updateflag']))
    {
        array_push($_SESSION['grpmodels'], $_POST['modelID']);
        echo "Added successfully!";
    }
    else
    {
        array_push($_SESSION['grpmodels'], $_POST['modelID']);
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

            $html .= "<td class=\"text-center\"><a href=\"".$_SERVER['PHP_SELF']."?show=usershow&name=".$row['creator']."\">".$row['creator']."</td>";
            if($type == "model")
            {
                $html .= "<td class=\"text-center\">";
                $html .= "<button type=\"submit\" class=\"btn btn-default btn-xs\" name=\"removemodel\" value=\"".$key."\">";
                $html .= "<span class=\"glyphicon glyphicon-minus\"></span> Remove from group</button>";
                $html .= "</td>";
            }
            if($type == "log")
            {
                $html .= "<td class=\"text-center\">";
                $html .= "<button type=\"submit\" class=\"btn btn-default btn-xs\" name=\"removelog\" value=\"".$key."\">";
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
    
    // update files which type is log, that they can't be deleted furthermore!
    $sqlmodfiles = "UPDATE ".TBL_PREFIX."files
                    SET deletable = 0
                    WHERE type = 'model' AND foreignID = ? AND timestamp <= ?";


    // update model entry, that it can't be deleted furthermore!
    $sqlmodentry = "UPDATE ".TBL_PREFIX."models
                    SET deletable = 0
                    WHERE modelID = ?";


    // execute for all modelids in array
    if($res = $conid->prepare($sqlmodels))
    {
        $res2 = $conid->prepare($sqlmodfiles);
        $res3 = $conid->prepare($sqlmodentry);

        $res->bind_param('is',$modelid,$modeltimestamp);
        $res2->bind_param('is',$modelid,$modeltimestamp);
        $res3->bind_param('i',$modelid);

        foreach($models as $entry)
        {
            $parts = explode("|",$entry);
            $modelid = $parts[0];
            $modelid2 = $parts[0];
            $modelid3 = $parts[0];
            $modeltimestamp = $parts[1];
            $modeltimestamp2 = $parts[1];
            $res->execute();
            $res2->execute();
            $res3->execute();
        }
    }
    else
        echo $conid->error;



    $sqllogs = "INSERT INTO ".TBL_PREFIX."loggroups
                    (groupID, logID, timestamp)
                  VALUES
                    ('$groupid', ?, ?)";

    // update files which type is log, that they can't be deleted furthermore!
    $sqllogfiles = "UPDATE ".TBL_PREFIX."files
                    SET deletable = 0
                    WHERE type = 'log' AND foreignID = ? AND timestamp <= ?";


    // update log entry, that it can't be deleted furthermore!
    $sqllogentry = "UPDATE ".TBL_PREFIX."logs
                    SET deletable = 0
                    WHERE logID = ?";

    $res2 = $conid->prepare($sqllogfiles);
    $res3 = $conid->prepare($sqllogentry);

    if($res = $conid->prepare($sqllogs))
    {
        $res->bind_param('is',$logid, $logtimestamp);
        $res2->bind_param('is',$logid,$logtimestamp);
        $res3->bind_param('i',$logid);

        // execute for all logids in array
        foreach($logs as $entry)
        {
            $parts = explode("|",$entry);
            $logid = $parts[0];
            $logtimestamp = $parts[1];
            $res->execute();
            $res2->execute();
            $res3->execute();
        }
    }
    else
        echo $conid->error;

    $conid->close();

    unset($_SESSION['grpmodels']);
    unset($_SESSION['grplogs']);
    if(isset($_SESSION['updateflag']))
    {
        unset($_SESSION['grpoldmodels']);
        unset($_SESSION['grpoldlogs']);
        unset($_SESSION['updateflag']);
    }
    unset($_SESSION['groupID']);
    unset($_SESSION['groupName']);

    return true;
}

function viewgroup($groupid)
{
    if($groupid == '')
    {
        header( 'location: index.php?show=404' );
    }

    $groupid = cleaninput($groupid);
    $conid = db_connect();

    $groupvalues = array();

    if(isadmin())
    {
        $sql = "SELECT groupID, groupName, timestamp, guid, creator, state, tags, private
                FROM ".TBL_PREFIX."groups
                WHERE groupID = '$groupid'";
    }
    else
    {
        $sql = "SELECT groupID, groupName, timestamp, guid, creator, state, tags, private
                FROM ".TBL_PREFIX."groups
                WHERE groupID = '$groupid'
                AND private = '0'";
    }

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();
    $res->bind_result($groupvalues['id'],$groupvalues['name'],$groupvalues['timestamp'],$groupvalues['guid'],$groupvalues['creator'],$groupvalues['state'],$groupvalues['tags'],$groupvalues['private']);
    $res->fetch();


    if($res->affected_rows == 1)
    {
        $res->fetch();
        $groupvalues['timestamp'] = date("d.m.Y", strtotime($groupvalues['timestamp']));
        $conid->close();
        return $groupvalues;
    }
    else
        header( 'location: index.php?show=404' );
}

function linkedtypes($id,$type,$creator,$state)
{
    $id = cleaninput($id);
    $conid = db_connect();

    $sql = "SELECT ".$type."ID,timestamp
            FROM ".TBL_PREFIX.$type."groups
            WHERE groupID = $id";

    if( $res = $conid->query($sql) )
    {

        while( $row = $res->fetch_assoc() )
        {
            if($type == "model")
            {
                $values = viewmodel($row['modelID']);
                if($values['private'] == TRUE && isadmin())
                {
                    // do nothing
                }
                elseif($values['private'] == FALSE)
                {
                    // do nothing
                }
                else
                {
                    continue;
                }

                $html = "";
                $html .= "<tr>";
                $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=modview&modelID=".$values['id']."&timestamp=".date("YmdHis", strtotime($row['timestamp']))."\">".$values['name']."</a></td>";
                $html .= "<td class=\"text-center\">".date("d.m.Y - H:i:s", strtotime($row['timestamp']))."</td>";
                $html .= "<td class=\"text-center\">";
                if(isset($_SESSION['angemeldet']) && isset($_SESSION['user']) && $_SESSION['user'] == $creator && $state == 1 || isadmin())
                {
                    $html .= "<button type=\"submit\" class=\"btn btn-default btn-xs\" name=\"removegroupmodel\" value=\"".$values['id']."|".$id."\">";
                    $html .= "<span class=\"glyphicon glyphicon-minus\"></span> Remove from group</button>";
                }
                    $html .= "</td>";
                $html .= "</tr>";

                echo $html;
            }

            if($type == "log")
            {
                $values = viewlog($row['logID']);
                if($values['private'] == TRUE && isadmin())
                {
                    // do nothing
                }
                elseif($values['private'] == FALSE)
                {
                    // do nothing
                }
                else
                {
                    continue;
                }

                $html = "";
                $html .= "<tr>";
                $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=logview&logID=".$values['id']."&timestamp=".date("YmdHis", strtotime($row['timestamp']))."\">".$values['name']."</a></td>";
                $html .= "<td class=\"text-center\">".date("d.m.Y - H:i:s", strtotime($row['timestamp']))."</td>";
                $html .= "<td class=\"text-center\">";
                if(isset($_SESSION['angemeldet']) && isset($_SESSION['user']) && $_SESSION['user'] == $creator && $state == 1 || isadmin())
                {
                    $html .= "<button type=\"submit\" class=\"btn btn-default btn-xs\" name=\"removegrouplog\" value=\"".$values['id']."|".$id."\">";
                    $html .= "<span class=\"glyphicon glyphicon-minus\"></span> Remove from group</button>";
                }
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
    $id = cleaninput($parts[0]);
    $groupid = cleaninput($parts[1]);

    $sql = "DELETE FROM ".TBL_PREFIX.$type."groups 
            WHERE groupID = '$groupid'
            AND ".$type."ID = '$id'";

    $res = $conid->query($sql);

    return ($conid->affected_rows == 1) ? true : false;
}

function editgroup($id)
{
    $id = cleaninput($id);
    $conid = db_connect();

    $tags = cleantags($_POST['tags']);

    // check private flag
    if(isset($_POST['private']) && $_POST['private'] == TRUE)
        $private = 1;
    else
        $private = 0;

    $sql = "UPDATE ".TBL_PREFIX."groups
            SET timestamp = '".$_POST['timestamp']."', tags = '$tags', private = '$private'
            WHERE groupID = '$id'";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();

    $conid->close();

    return ($res->affected_rows == 1) ? true : false;
}

function createzip($id)
{
    $id = cleaninput($id);
    $conid = db_connect();

    $type = "";
    $typeid = "";
    $date = "";

    $sqllogs = "SELECT logID,timestamp
                FROM ".TBL_PREFIX."loggroups
                WHERE groupID = $id";
    
    $sqlmodels = "SELECT modelID,timestamp
                  FROM ".TBL_PREFIX."modelgroups
                  WHERE groupID = $id";


    $zip = array();
    $tmp = array();

    // read linked logs from group
    if( $res = $conid->query($sqllogs) )
    {
        while( $row = $res->fetch_assoc() )
        {
            $val = $row['logID']."|".$row['timestamp']."|log";
            array_push($tmp, $val); 
        }
    }
    else
    {
        echo $conid-error;
        $conid->close();
    }

    // read linked models from group
    if( $res = $conid->query($sqlmodels) )
    {
        while( $row = $res->fetch_assoc() )
        {
            $val = $row['modelID']."|".$row['timestamp']."|model";
            array_push($tmp, $val); 
        }
    }
    else
    {
        echo $conid-error;
        $conid->close();
    }

    // for each entry in tmp, explode 
    foreach($tmp as $t)
    {
        $parts = explode("|",$t);
        $typeid = $parts[0];
        $date = $parts[1];
        $type = $parts[2];
        $sqlfiles = "SELECT DISTINCT(path)
                     FROM ".TBL_PREFIX."files
                     WHERE type = '$type' AND foreignID = '$typeid' AND timestamp <= '$date'
                     ORDER BY timestamp
                     DESC";
        $res = $conid->query($sqlfiles);
        while( $row = $res->fetch_assoc() )
        {
            // $path_filename = $row['path']."|".$row['fileName'];
            array_push($zip, $row['path']);
        }
        $res->free();
    }
    return $zip;

    $conid->close();
}

function getgroupid($guid)
{
    $guid = cleaninput($guid);
    $conid = db_connect();

    $sql = "SELECT groupID
            FROM ".TBL_PREFIX."groups
            WHERE guid = '".$guid."'";

    if($res = $conid->prepare($sql))
    {
        $res->execute();
        $res->store_result();
        $res->bind_result($groupID);
        $res->fetch();
        $conid->close();
        if(empty($groupID))
            return 0;

        return $groupID;
    }
    else
    {
        echo $conid->error;
        $conid->close();
    }
}

// open or close a group
function switchgrpstate($st)
{
    $id = "";
    $state = "";

    $parts = explode("|",$st);
    $id = cleaninput($parts[0]);
    $state = cleaninput($parts[1]);

    if($state == "0")
        $statename = "<span class=\"label label-danger\">closed</span>";
    if($state == "1")
        $statename = "<span class=\"label label-success\">open</span>";

    $conid = db_connect();

    $sql = "UPDATE ".TBL_PREFIX."groups
            SET state = '$state'
            WHERE groupID = '$id'";

    $res = $conid->query($sql);
    if($res)
    {
        $conid->close();
        echo "State: ".$statename;
    }
    else
    {
        echo $conid->error;
        $conid->close();
        echo "error!";
    }

}

// checks if a type (model or log) is already in a group
function checkdependencies($typeid,$type)
{
    $typeid = cleaninput($typeid);
    $conid = db_connect();

    $sql = "SELECT ".$type."ID
            FROM ".TBL_PREFIX.$type."groups
            WHERE ".$type."ID = $typeid";

    $res = $conid->query($sql);

    // if there is no dependency
    if($conid->affected_rows == 0)
        return true;
    else
        return false;

}

// checks if a type (model or log) is in another group
function checkfiledependencies_desc($typeid,$type,$timestamp)
{
    $typeid = cleaninput($typeid);
    $conid = db_connect();

    $sql = "SELECT ".$type."ID
            FROM ".TBL_PREFIX.$type."groups
            WHERE ".$type."ID = $typeid AND timestamp < '".$timestamp."";

    $res = $conid->query($sql);

    // if there is no dependency
    if($conid->affected_rows == 0)
        return true;
    else
        return false;

}

// checks if a type (model or log) is in another group
function checkfiledependencies_equal($typeid,$type,$timestamp)
{
    $typeid = cleaninput($typeid);
    $conid = db_connect();

    $sql = "SELECT ".$type."ID
            FROM ".TBL_PREFIX.$type."groups
            WHERE ".$type."ID = $typeid AND timestamp = '".$timestamp."";

    $res = $conid->query($sql);

    // if there is no dependency
    if($conid->affected_rows == 0)
        return true;
    else
        return false;

}

function getlinkedtypeids($grpid,$type)
{
    $grpid = cleaninput($grpid);
    $conid = db_connect();

    $sql = "SELECT ".$type."ID,timestamp
            FROM ".TBL_PREFIX.$type."groups
            WHERE groupID = $grpid";

    $values = array();

    if( $res = $conid->query($sql) )
    {

        while( $row = $res->fetch_assoc() )
        {
            array_push($values, $row);
        }
    }
    return $values;
}

// make a type deletable, if there are no dependencies
function makedeletable($typeid,$type)
{
    $conid = db_connect();
    // update files that they can be deleted furthermore!
    $sqlfiles = "UPDATE ".TBL_PREFIX."files
                    SET deletable = 1
                    WHERE type = '".$type."' AND foreignID = ".$typeid."";
    
    // update type entry, that it can be deleted!
    $sqlentry = "UPDATE ".TBL_PREFIX.$type."s
                    SET deletable = 1
                    WHERE ".$type."ID = ".$typeid."";

    $res1 = $conid->query($sqlfiles);
    $res2 = $conid->query($sqlentry);
}

// only make files deletable, if there are no dependencies
function makedeletablefiles_desc($typeid,$timestamp,$type)
{
    $conid = db_connect();
    // update files that they can be deleted furthermore!
    $sqlfiles = "UPDATE ".TBL_PREFIX."files
                    SET deletable = 1
                    WHERE type = '".$type."' AND foreignID = ".$typeid." AND timestamp < '".$timestamp."'";
    
    $res1 = $conid->query($sqlfiles);

    $res1->close();
}

// only make files deletable, if there are no dependencies
function makedeletablefiles_equal($typeid,$timestamp,$type)
{
    $conid = db_connect();
    // update files that they can be deleted furthermore!
    $sqlfiles = "UPDATE ".TBL_PREFIX."files
                    SET deletable = 1
                    WHERE type = '".$type."' AND foreignID = ".$typeid." AND timestamp = '".$timestamp."'";
    
    $res1 = $conid->query($sqlfiles);

    $res1->close();
}

// delete group
function removegroup($grpid)
{
    $grpid = cleaninput($grpid);
    $conid = db_connect();


    $sqlgrp = "DELETE FROM ".TBL_PREFIX."groups
            WHERE groupID = '$grpid'";

    $sqlmod = "DELETE FROM ".TBL_PREFIX."modelgroups
            WHERE groupID = '$grpid'";

    $sqllog = "DELETE FROM ".TBL_PREFIX."loggroups
            WHERE groupID = '$grpid'";

    // get linked models and logs in arrays
    $models = getlinkedtypeids($grpid,'model'); 
    $logs = getlinkedtypeids($grpid,'log'); 

    if($res = $conid->prepare($sqlgrp))
    {
        if($res2 = $conid->prepare($sqlmod))
            $res2->execute();
        if($res3 = $conid->prepare($sqllog))
            $res3->execute();
        $res->execute();
        $conid->close();
    }
    else
    {
        echo $conid->error;
        return false;
    }
   
    
    // check the linked typeids
    // if yes: free them, so the user can delete them
    // if not: keep them undeletable
    foreach($models as $m)
    {
        // check if a model has group dependencies
        if(checkdependencies($m['modelID'],'model'))
            makedeletable($m['modelID'],'model');
        else
        {
            // check if a model has group dependencies with given timestamp 
            // exactly
            if(checkfiledependencies_equal($m['modelID'],$m['timestamp'],'model'))
                makedeletablefiles_equal($m['modelID'],$m['timestamp'],'model');

            // check if a model has group dependencies with given timestamp 
            // descending
            if(checkfiledependencies_desc($m['modelID'],$m['timestamp'],'model'))
                makedeletablefiles_desc($m['modelID'],$m['timestamp'],'model');
        }
    }
    foreach($logs as $l)
    {
        // check if a log has group dependencies
        if(checkdependencies($l['logID'],'log'))
            makedeletable($l['logID'],'log');
        else 
        {
            // check if a log has group dependencies with given timestamp 
            // exactly
            if(checkfiledependencies_equal($l['logID'],$l['timestamp'],'log'))
                makedeletablefiles_equal($l['logID'],$l['timestamp'],'log');

            // check if a log has group dependencies with given timestamp 
            // descending
            if(checkfiledependencies_desc($l['logID'],$l['timestamp'],'log'))
                makedeletablefiles_desc($l['logID'],$l['timestamp'],'log');
        }
    }
    return true;
    $conid->close();
}

// get group name
function getgroupname($grpid)
{
    $conid = db_connect();

    $grpid = cleaninput($grpid);

    $sql = "SELECT groupName 
            FROM ".TBL_PREFIX."groups
            WHERE groupID = '$grpid'";

    if($res = $conid->prepare($sql))
    {
        $res->execute();
        $res->store_result();
        $res->bind_result($name);
        $res->fetch();
        return $name;
    }
    else
    {
        echo $conid->error;
    }
    $conid->close();
}
?>
