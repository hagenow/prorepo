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
            $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=modlist&catID=".$row['catID']."\">".$modelcnt." Models</a></td>";
            $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=loglist&catID=".$row['catID']."\">".$logcnt." Logs</a></td>";
            $html .= "</tr>";

            echo $html;
        }
    }
    else
    $conid->close();
}

function countmodels($catID)
{
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

function cleancatname($conid)
{
    $catname = $_POST['catname'];

    $conid->real_escape_string( $catname );

    // slashes entfernen
     $catname = stripslashes( $catname );

    /** Trimmen - entfernt Leerzeichen, Zeilenvorschub, Tabulator, binäres
     * Leerzeichen, \, / ", ', ,, ., usw.
     * */
    $catname = trim( $catname, "\n\r\0\x0B\t,\='\\\/\"!?§$%&{}´`" );

    // Eingabe zurückgeben
    return $catname;

}

function createcat($conid, $catname, $user)
{
    $sql = "INSERT INTO
                ".TBL_PREFIX."categories (`catName`, `date`, `creator`)
            VALUES
            ('$catname', NOW(), '$user')";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();

    return ($res->affected_rows == 1)? true : false;
}

function getcatname($catid)
{
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
?>
