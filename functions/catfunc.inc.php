<?php

function getcategories()
{
    $conid = db_connect();
    $sql = "SELECT
                catName
            FROM 
                ".TBL_PREFIX."categories";

    if( $res = $conid->query($sql) ){

        while( $row = $res->fetch_assoc())
        {
            echo "<a href=\"".$_SERVER['PHP_SELF']."?getmodels=".$row['catID']."\" class=\"list-group-item\">".$row['catName']."</a>";
        }
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

?>
