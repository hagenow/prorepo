<?php

function getcategories()
{
    $conid = db_connect();
    $sql = "SELECT
                catID, catName
            FROM 
                ".TBL_PREFIX."categories";

    if( $res = $conid->query($sql) ){

        while( $row = $res->fetch_assoc() )
        {
            $modelcnt = countmodels($row['catID']);
            $logcnt = countlogs($row['catID']);

            $html = "";
            $html .= "<tr>";
            $html .= "<td>".$row['catName']."</td>";
            $html .= "<td class=\"text-center\"><a href=\"".$_SERVER['PHP_SELF']."?show=modlist&catID=".$row['catID']."\">".$modelcnt." Models</a></td>";
            $html .= "<td class=\"text-center\"><a href=\"".$_SERVER['PHP_SELF']."?show=loglist&catID=".$row['catID']."\">".$logcnt." Logs</a></td>";
            $html .= "</tr>";

            echo $html;
        }
    }
    else
    $conid->close();
}

function adm_getcategories()
{
    $conid = db_connect();
    $sql = "SELECT
                catID, catName
            FROM 
                ".TBL_PREFIX."categories";

    if( $res = $conid->query($sql) ){

        while( $row = $res->fetch_assoc() )
        {
            $modelcnt = countmodels($row['catID']);
            $logcnt = countlogs($row['catID']);

            $html = "";
            $html .= "<tr>";
            $html .= "<td>".$row['catName']."</td>";
            $html .= "<td class=\"text-center\"><a href=\"".$_SERVER['PHP_SELF']."?show=catedit&action=rename&catID=".$row['catID']."\"><span class=\"glyphicon glyphicon-wrench\"></span>
                </a></td>";
            $html .= "<td class=\"text-center\"><a href=\"".$_SERVER['PHP_SELF']."?show=catedit&action=delete&catID=".$row['catID']."\"><span class=\"glyphicon glyphicon-remove\"></a></td>";
            $html .= "</tr>";

            echo $html;
        }
    }
    else
    $conid->close();
}

function countmodels($catID)
{
    $catID = cleaninput($catID);
    $conid = db_connect();
    $sql = "SELECT COUNT(catID)
            FROM ".TBL_PREFIX."models 
            WHERE catID = '$catID'";

    if($res = $conid->prepare($sql))
    {
        $res->execute();
        $res->store_result();
        $res->bind_result($count);
        $res->fetch();

        return $count;
    }
    else
    {
        echo $conid->error;
    }
    $conid->close();
}

function countlogs($catID)
{
    $catID = cleaninput($catID);
    $conid = db_connect();
    $sql = "SELECT COUNT(catID) 
            FROM ".TBL_PREFIX."logs 
            WHERE catID = '$catID'";

    if($res = $conid->prepare($sql))
    {
        $res->execute();
        $res->store_result();
        $res->bind_result($count);
        $res->fetch();

        return $count;
    }
    else
    {
        echo $conid->error;
    }
    $conid->close();
}

function cleancatname()
{
    $conid = db_connect();

    $catname = $_POST['catname'];

    $conid->real_escape_string( $catname );

    // slashes entfernen
    $catname = stripslashes( $catname );
    
    // tags entfernen
    $catname = strip_tags( $catname );

    /** Trimmen - entfernt Leerzeichen, Zeilenvorschub, Tabulator, binäres
     * Leerzeichen, \, / ", ', ,, ., usw.
     * */
    $catname = trim( $catname, "\n\r\0\x0B\t,\='\\\/\"!?§$%&{}´`" );

    // DB Verbindung schließen
    $conid->close();

    // Eingabe zurückgeben
    return $catname;

}

function createcat($catname)
{
    $conid = db_connect();

    $user = $_SESSION['user'];

    $sql = "INSERT INTO
                ".TBL_PREFIX."categories (`catName`, `timestamp`, `creator`)
            VALUES
            ('$catname', NOW(), '$user')";

    if($res = $conid->prepare($sql))
    {
        $res->execute();
        $res->store_result();
        $conid->close();
        return ($res->affected_rows == 1)? true : false;
    }
    else
    {
        echo $conid->error;
    }
    $conid->close();
}

function getcatname($catid)
{
    $catid = cleaninput($catid);
    $conid = db_connect();

    $sql = "SELECT catName
            FROM ".TBL_PREFIX."categories
            WHERE catID = '$catid'";

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

function renamecat($catid,$catname)
{
    $catid = cleaninput($catid);
    $conid = db_connect();

    $sql = "UPDATE ".TBL_PREFIX."categories SET catName = '".$catname."' WHERE catID = ".$catid."";

    $conid->query($sql);
    $conid->close();
}

function deletecat($catid)
{
    $catid = cleaninput($catid);
    $conid = db_connect();

    $sql = "DELETE FROM ".TBL_PREFIX."categories WHERE catID = $catid";

    $conid->query($sql);
    $conid->close();
}
?>
