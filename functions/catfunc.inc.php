<?php

function getcategories($conid)
{
    $sql = "SELECT
                catName
            FROM 
                repo_categories";

    if( $res = $conid->query($sql) ){

        while( $row = $res->fetch_assoc())
        {
            echo $row['catName']."<br/>";
        }
    }

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

    // In Kleinschrift umwandeln
    $catname = strtolower( $catname );

    // Eingabe zurückgeben
    return $catname;

}

function createcat($conid, $catname, $user)
{
    $sql = "INSERT INTO
                repo_categories (`catName`, `date`, `creator`)
            VALUES
            ('$catname', NOW(), '$user')";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();

    return ($res->affected_rows == 1)? true : false;
}

?>
