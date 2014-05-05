<?php

function cleansearch($keyword)
{
    $keyword = cleaninput($keyword);
    $keyword = preg_replace('/[^-a-zA-ZäÄöÖüÜß0-9,.]/', '',$keyword);
    return $keyword;
}

function searchmodel($keyword)
{
    $conid = db_connect();

    $keyword = cleansearch($keyword);

    if(!isadmin())
    {
        $sql = "SELECT modelID, modelName, timestamp, creator
                FROM ".TBL_PREFIX."models
                WHERE (modelName LIKE '%$keyword%'
                OR creator LIKE '%$keyword%')
                AND private = '0'";
    }
    else
    {
        $sql = "SELECT modelID, modelName, timestamp, creator
                FROM ".TBL_PREFIX."models
                WHERE (modelName LIKE '%$keyword%'
                   OR creator LIKE '%$keyword%')";
    }

    $html = "";

    if($res = $conid->query($sql))
    {
        if($conid->affected_rows != 0)
        {
            while( $row = $res->fetch_assoc() )
            {
                $id = $row['modelID'];
                $name = $row['modelName'];
                $timestamp = date("H:i:s - d.m.Y", strtotime($row['timestamp']));
                $creator = $row['creator'];

                $html .= "<tr>";
                $html .= "<td><a href=\"". $_SERVER['PHP_SELF']."?show=modview&modelID=".$id."\">".$name."</a></td>";
                $html .= "<td class=\"text-center\">".$timestamp."</td>";
                $html .= "<td class=\"text-center\"><a href=\"".$_SERVER['PHP_SELF']."?show=usershow&name=".$row['creator']."\">".$row['creator']."</td>";
                $html .= "</tr>";

            }
            echo $html;
            $conid->close();
        }
        else
        {
            $html = "";
            $html .= "<tr>";
            $html .= "<td class=\"text-center\" colspan=\"5\">No results!</td>";
            $html .= "</tr>";

            echo $html;
            $conid->close();
        }
    }
    else
    {
        echo $conid->error;
        $conid-close();
    }
}

function searchlog($keyword)
{
    $conid = db_connect();

    $keyword = cleansearch($keyword);

    if(!isadmin())
    {
        $sql = "SELECT logID, logName, timestamp, creator
                FROM ".TBL_PREFIX."logs
                WHERE (logName LIKE '%$keyword%'
                OR creator LIKE '%$keyword%')
                AND private = '0'";
    }
    else
    {
        $sql = "SELECT logID, logName, timestamp, creator
                FROM ".TBL_PREFIX."logs
                WHERE (logName LIKE '%$keyword%'
                OR creator LIKE '%$keyword%')";
    }

    $html = "";

    if($res = $conid->query($sql))
    {
        if($conid->affected_rows != 0)
        {
            while( $row = $res->fetch_assoc() )
            {
                $id = $row['logID'];
                $name = $row['logName'];
                $timestamp = date("H:i:s - d.m.Y", strtotime($row['timestamp']));
                $creator = $row['creator'];

                $html .= "<tr>";
                $html .= "<td><a href=\"". $_SERVER['PHP_SELF']."?show=logview&logID=".$id."\">".$name."</a></td>";
                $html .= "<td class=\"text-center\">".$timestamp."</td>";
                $html .= "<td class=\"text-center\"><a href=\"".$_SERVER['PHP_SELF']."?show=usershow&name=".$row['creator']."\">".$row['creator']."</td>";
                $html .= "</tr>";

            }
            echo $html;
            $conid->close();
        }
        else
        {
            $html = "";
            $html .= "<tr>";
            $html .= "<td class=\"text-center\" colspan=\"5\">No results!</td>";
            $html .= "</tr>";

            echo $html;
            $conid->close();
        }
    }
    else
    {
        echo $conid->error;
        $conid-close();
    }

}

function searchgroup($keyword)
{
    $conid = db_connect();

    $keyword = cleansearch($keyword);

    if(!isadmin())
    {
        $sql = "SELECT groupID, groupName, timestamp, creator, tags
                FROM ".TBL_PREFIX."groups
                WHERE (groupName LIKE '%$keyword%'
                   OR tags LIKE '%$keyword%'
                   OR creator LIKE '%$keyword%')
                AND private = '0'";
    }
    else
    {
        $sql = "SELECT groupID, groupName, timestamp, creator, tags
                FROM ".TBL_PREFIX."groups
                WHERE (groupName LIKE '%$keyword%'
                   OR tags LIKE '%$keyword%'
                   OR creator LIKE '%$keyword%')";
    }

    $html = "";

    if($res = $conid->query($sql))
    {
        if($conid->affected_rows != 0)
        {
            while( $row = $res->fetch_assoc() )
            {
                $id = $row['groupID'];
                $name = $row['groupName'];
                $timestamp = date("H:i:s - d.m.Y", strtotime($row['timestamp']));
                $creator = $row['creator'];
                $tags = $row['tags'];

                $html .= "<tr>";
                $html .= "<td><a href=\"". $_SERVER['PHP_SELF']."?show=groupview&groupID=".$id."\">".$name."</a></td>";
                $html .= "<td class=\"text-center\">".$timestamp."</td>";
                $html .= "<td class=\"text-center\">".$tags."</td>";
                $html .= "<td class=\"text-center\"><a href=\"".$_SERVER['PHP_SELF']."?show=usershow&name=".$row['creator']."\">".$row['creator']."</td>";
                $html .= "</tr>";

            }
            echo $html;
            $conid->close();
        }
        else
        {
            $html = "";
            $html .= "<tr>";
            $html .= "<td class=\"text-center\" colspan=\"5\">No results!</td>";
            $html .= "</tr>";

            echo $html;
            $conid->close();
        }
    }
    else
    {
        echo $conid->error;
        $conid-close();
    }
}

function searchuser($keyword)
{
    $conid = db_connect();

    $keyword = cleansearch($keyword);

    $sql = "SELECT userID, login, firstname, lastname, email, affiliation
            FROM ".TBL_PREFIX."users
            WHERE (firstname LIKE '%$keyword%'
               OR lastname LIKE '%$keyword%'
               OR login LIKE '%$keyword%'
               OR affiliation LIKE '%$keyword%')";

    $html = "";

    if($res = $conid->query($sql))
    {
        if($conid->affected_rows != 0)
        {
            while( $row = $res->fetch_assoc() )
            {
                $id = $row['userID'];
                $login = $row['login'];
                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
                $email = $row['email'];
                $affiliation = $row['affiliation'];

                $html .= "<tr>";
                $html .= "<td><a href=\"". $_SERVER['PHP_SELF']."?show=usershow&name=".$login."\">".$login."</a></td>";
                $html .= "<td class=\"text-center\"><a href=\"". $_SERVER['PHP_SELF']."?show=usershow&name=".$login."\">".$firstname." ".$lastname."</a></td>";
                $html .= "<td class=\"text-center\">".$email."</td>";
                $html .= "<td class=\"text-center\">".$affiliation."</td>";
                $html .= "</tr>";

            }
            echo $html;
            $conid->close();
        }
        else
        {
            $html = "";
            $html .= "<tr>";
            $html .= "<td class=\"text-center\" colspan=\"5\">No results!</td>";
            $html .= "</tr>";

            echo $html;
            $conid->close();
        }
    }
    else
    {
        echo $conid->error;
        $conid-close();
    }
}
?>
