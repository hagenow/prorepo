<?php
/** Prüft den Login im Userbereich, übergibt Parameter an andere 
 * check-Funktionen
 * */
function checkuserlogin($user, $pass)
{
    $conid = db_connect();

    $user = $conid->real_escape_string($user);
    $pass = $conid->real_escape_string($pass);

    if(checkuser($user,$conid))
    {
        $conid->close();
        return checkpass($user,$pass);
    }
    else
    {
        $conid->close();
        return false;
    }
} 

/** Prüft ob der Nutzer existiert
 * */
function checkuser($user)
{
    $conid = db_connect();

    $sql = "SELECT
                login
             FROM
                ".TBL_PREFIX."users
             WHERE
                login = '".$user."'";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();
    
    $conid->close();
    return ($res->affected_rows == 1) ?  true : false;
}

/** checkblocked($user, $count)
 * Falls ein User gesperrt ist, verweigere den Login.
 * */
function checkblocked($user)
{
    $conid = db_connect();

    $sql = "SELECT
                blocked
             FROM
                ".TBL_PREFIX."users
             WHERE
                login = '".$user."'";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();
    $res->bind_result($blocked);
    $res->fetch();

    $conid->close();

    return $blocked;
}

/** Prüft das übergebene Passwort
 * */
function checkpass($user, $pass)
{
    $conid = db_connect();

        /** leere Variablen deklarieren */
        $failedlogins = '';
        $pass_from_db = '';

        /** SQL Statement, welches das password  ausließt */
        $sql1 = "SELECT
                    password
                 FROM
                    ".TBL_PREFIX."users
                 WHERE 
                    login = '".$user."'";

        /** Verbindungen mit obigem SQL Statement ausführen */
        $res = $conid->prepare( $sql1 );
        /** Statement ausführen */
        $res->execute();
        /** Ergebnisse abspeichern */
        $res->store_result();
        /** Ergebnisse an Variablen binden */
        $res->bind_result($pass_from_db);

        $pass_from_db = $conid->real_escape_string($pass_from_db);

        $failuredlogins = checkfailedlogins($user,$conid);

        if($res->affected_rows == 1 )
        {
            $res->fetch();

            /** prüfe fehlerhafte logins */
            if($failuredlogins < 10)
            {
                /** prüfe übergebenes PW mit dem aus der DB - der salt wird 
                 * ausgelesen aus $pass_from_db 
                 * wenn was fehlschlägt, kann man hier die abwärtskompatible 
                 * Library bekommen: 
                 * https://github.com/ircmaxell/password_compat */
                $pw_correct = password_verify($pass, $pass_from_db);
                $res->free_result();
                if($pw_correct)
                {
                    $conid->close();
                    return true;
                }
                else
                {
                    $failuredlogins = $failuredlogins + 1;
                    $count = updatefailedlogins($user,$failuredlogins,$conid);
                    if($count == 10) blockuser($user,$conid);
                    $conid->close();
                    return false;
                }
            }
            else
            {
                $conid->close();
                return false;
            }
        }
        else
        {  
            $conid->close();
            return false;
        }

}

/** prüft die assozierte Gruppe des Users 
 * */
function checkgroup($user)
{
    $conid = db_connect();

    $sqlusergroupid = "SELECT
                usergroup
             FROM
                ".TBL_PREFIX."users
             WHERE
                login = '".$user."'";

    $res = $conid->prepare($sqlusergroupid);
    $res->execute();
    $res->store_result();
    $res->bind_result($usergroupid);
    $res->fetch();

    echo $usergroupid;

    $sqlusergroup = "SELECT
                groupname
             FROM
                ".TBL_PREFIX."usergroups
             WHERE
                usergroupID = '".$usergroupid."'";

    $res2 = $conid->prepare($sqlusergroup);
    $res2->execute();
    $res2->store_result();
    $res2->bind_result($usergroup);

    echo $usergroup;
    
    if($res2->affected_rows == 1)
    {
        $res2->fetch();
        $conid->close();
        $_SESSION['usergroup'] = $usergroup;
    }
}

/** is user an administrator
 * */
function isadmin()
{
    return ($_SESSION['usergroup'] == "admin") ? true : false;
}

/** lese die Anzahl der fehlerhaften Logins aus
 * */
function checkfailedlogins($user)
{
    $conid = db_connect();

    $sql = "SELECT
                failedlogins
             FROM
                ".TBL_PREFIX."users
             WHERE
                login = '".$user."'";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();
    $res->bind_result($failedlogins);
    
    $res->fetch();
    
    $conid->close();

    return $failedlogins;
}

/** updatefailedlogins($user, $count, $conid)
 * Setzt den failedlogin-Counter auf 0 wenn Login erfolgreich, ansonsten zählt 
 * er eins hoch!
 * */
function updatefailedlogins($user, $count)
{
    $conid = db_connect();

    $sql = "UPDATE
                ".TBL_PREFIX."users
             SET
                failedlogins = '".$count."'
             WHERE
                login = '".$user."'";

    $res = $conid->prepare($sql);
    $res->execute();
    $conid->close();

    return $count;
}

/** blockuser($user, $count)
 * Bei mehr als einer erlaubten Anzahl fehlerhafter Logins wird der Benutzer 
 * gesperrt.
 * */
function blockuser($user)
{
    $conid = db_connect();

    $sql = "UPDATE
                ".TBL_PREFIX."users
             SET
                blocked = 1
             WHERE
                login = '".$user."'
             LIMIT
                 1";


    $res = $conid->prepare($sql);
    $res->execute();
    $conid->close();
    return false;
}

/** updateuser($user, $conid)
 * Bei erfolgreichem Login setze Logintime und letzte IP
 * */
function updateuser($user)
{
    $conid = db_connect();

    $sql = "UPDATE
                ".TBL_PREFIX."users
            SET
                failedlogins = 0,
                blocked = 0,
                lastlogin = NOW(),
                IP = '" .$conid->real_escape_string( $_SERVER['REMOTE_ADDR'] ). "'
            WHERE
                login = '".$user."'
            LIMIT
                1";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();

    if($res->affected_rows == 1)
    {
        $_SESSION['angemeldet']   = true;
        $_SESSION['user'] = $user;
        $_SESSION['anmeldung']    = md5( $_SERVER['REQUEST_TIME'] );
        return true;
    }

}

/** user registration */
function registeruser()
{
    $conid = db_connect();

    $login = cleaninput($_POST['login']);
    $firstname = cleaninput($_POST['firstname']);
    $lastname = cleaninput($_POST['lastname']);
    $affiliation = cleaninput($_POST['affiliation']);

    // create uniqid for mail-validation
    $uniqid = uniqid('u',TRUE);

    // Check mailaddress for correct structure
    $email = checkmail( $_POST['email'] );

    // MX-Check
    if(!domain_exists($email))
        return false;

    /** encrypt password */
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO
                ".TBL_PREFIX."users
                (login, password, firstname, lastname, email, affiliation, registerdate,verifyid, usergroup)
                VALUES
                ('".$conid->real_escape_string($login)."','".$conid->real_escape_string($password)."',
                    '".$conid->real_escape_string($firstname)."','".$conid->real_escape_string($lastname)."',
                    '".$conid->real_escape_string($email)."','".$conid->real_escape_string($affiliation)."',
                    CURDATE(), '$uniqid', '2')";

    if($res = $conid->prepare($sql))
    {
        $res->execute();
        if($res->affected_rows == 1)
        {
            $conid->close();
            return sendregmail($login,$uniqid,$email);
        }
        else
        {
            return false; 
        }
    }
    else
    {
        echo $conid->error; 
        return false;
    }

}

/** update user details */
function updateuserdata()
{
    $conid = db_connect();

    $firstname = htmlspecialchars($_POST['firstname'], ENT_QUOTES, 'UTF-8');
    $lastname = htmlspecialchars($_POST['lastname'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $affiliation = htmlspecialchars($_POST['affiliation'], ENT_QUOTES, 'UTF-8');

    /** encrypt password */
    if(isset($_POST['password'])&& (strlen($_POST['password']) >= 1 && $_POST['password'] !== ' ') )
    {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql_wpw = "UPDATE
                        ".TBL_PREFIX."users
                     SET
                         password = '".$password."',
                         firstname = '".$firstname."',
                         lastname = '".$lastname."', 
                         email = '".$email."', 
                         affiliation = '".$affiliation."'
                     WHERE
                         login = '".$_SESSION['user']."'";
        $res = $conid->prepare($sql_wpw);
    }
    else
    {
        $sql_wopw = "UPDATE
                        ".TBL_PREFIX."users
                     SET
                         firstname = '".$firstname."',
                         lastname = '".$lastname."', 
                         email = '".$email."', 
                         affiliation = '".$affiliation."'
                     WHERE
                     login = '".$_SESSION['user']."'";
        $res = $conid->prepare($sql_wopw);
    }

    $res->execute();
    $res->store_result();

    return ($res->affected_rows == 1) ? true : false;
}

/** get user details */
function getuserdata()
{
    $conid = db_connect();

    $userdata = array();

    $sql = "SELECT 
                firstname, lastname, email, affiliation
            FROM
                ".TBL_PREFIX."users
            WHERE
                login = '".$_SESSION['user']."'";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();
    $res->bind_result($userdata['firstname'],$userdata['lastname'],$userdata['email'],$userdata['affiliation']);
    $res->fetch();

    if($res->affected_rows == 1)
    {
        $res->fetch();
        return $userdata;
    }
    
}

function showuserdata($name)
{
    $conid = db_connect();

    $userdata = array();

    $name = cleaninput($name);

    $sql = "SELECT 
                firstname, lastname, email, affiliation
            FROM
                ".TBL_PREFIX."users
            WHERE
                login = '".$_SESSION['user']."'";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();
    $res->bind_result($userdata['firstname'],$userdata['lastname'],$userdata['email'],$userdata['affiliation']);
    $res->fetch();

    if($res->affected_rows == 1)
    {
        $res->fetch();
        return $userdata;
    }
    
}

/** bereinige Nutzereingaben */
function cleanlogininput()
{
    $conid = db_connect();

    $input['user'] = $_POST['user'];
    $input['pass'] = $_POST['pass'];

    $conid->real_escape_string( $input['user']);
    $conid->real_escape_string( $input['pass']);

    // slashes entfernen
   	$input['user'] = stripslashes( $input['user'] );
   	$input['pass'] = stripslashes( $input['pass'] );
    
    /** Trimmen - entfernt Leerzeichen, Zeilenvorschub, Tabulator, binäres 
     * Leerzeichen, \, / ", ', ,, ., usw.
     * */
    $input['user'] = trim( $input['user'], " \n\r\0\x0B\t.,\='-\\\/\"!?§$%&(){}[]´`@" );
    $input['pass'] = trim( $input['pass'], " \n\r\0\x0B\t" );

    // In Kleinschrift umwandeln
    // $input['user'] = strtolower( $input['user'] );

    // db-Verbindung schließen
    $conid->close();

    // Eingabe zurückgeben
    return $input;
}

/** checksession($conid)
 * Prüft die Session und überträgt die bisherigen Sessiondaten in eine neue 
 * Session
 * */
function checksession()
{
    $conid = db_connect();
    // Alte Session löschen und Sessiondaten in neue Session transferieren
    session_regenerate_id( true );

    if ($_SESSION['login'] !== true) return false;

    $sql = "SELECT 
                IP, lastlogin
            FROM
                ".TBL_PREFIX."users
            WHERE
                login = '" .$conid->real_escape_string( $_SESSION['user'] ). "'
            ";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();

    $conid->close();
}

/** Beendet die laufende Session und leitet wieder auf den Login um
 * */
function resetsession()
{
    session_destroy();
    header( 'location: index.php?show=notloggedin' );
    exit;
}

function getuseruploads($type)
{
    $conid = db_connect();

    $sql = "SELECT *
            FROM ".TBL_PREFIX.$type."s
            WHERE creator ='".$_SESSION['user']."'
            GROUP BY timestamp
            DESC";

    if( $res = $conid->query($sql) )
    {
        while( $row = $res->fetch_assoc() )
        {
            $date = date("d.m.Y - H:i:s", strtotime($row['timestamp']));

            $html = "";
            $html .= "<tr>";
            if($type == "model")
                $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=modview&modelID=".$row['modelID']."\">".$row['modelName']."</a></td>";
            if($type == "log")
                $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=logview&logID=".$row['logID']."\">".$row['logName']."</a></td>";
            if($type == "group")
            {
                $html .= "<td><a href=\"".$_SERVER['PHP_SELF']."?show=groupview&groupID=".$row['groupID']."\">".$row['groupName']."</a></td>";
                if($row['state'] == "0") 
                {
                    $html .= "<td class=\"text-center\"><span class=\"label label-danger\">closed<span></td>";
                } 
                else 
                { 
                    $html .= "<td class=\"text-center\"><span class=\"label label-success\">open</span></td>";
                }
            }
            $html .= "<td class=\"text-center\">".$date."</td>";
            $html .= "</tr>";

            echo $html;
        }
    }
    $conid->close();
}

function verify($id)
{
    $conid = db_connect();

    $sql = "UPDATE ".TBL_PREFIX."users
            SET valid = '1'
            WHERE verifyid = '$id'";

    if($res = $conid->prepare($sql))
    {
        $res->execute();
        $res->store_result();
        $conid->close();
        return true;
    }
    else
    {
        echo $conid->error."<br>";
        $conid->close();
        return false;
    }
}

function resetpwfromid($id)
{
    $conid = db_connect();

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "UPDATE ".TBL_PREFIX."users
            SET password = '$password'
            WHERE verifyid = '$id'";

    if($res = $conid->prepare($sql))
    {
        $res->execute();
        $res->store_result();
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

function sendpwreq($login)
{
    $conid = db_connect();

    $login = cleaninput($login);
    
    // create uniqid for password reset
    $uniqid = uniqid('p',TRUE);

    $sqlmail = "SELECT email
            FROM ".TBL_PREFIX."users
            WHERE login = '$login'";

    $sqlverifyid = "UPDATE ".TBL_PREFIX."users
                    SET verifyid = '$uniqid'
                    WHERE login = '$login'";

    if($res1 = $conid->prepare($sqlverifyid))
    {
        $res1->execute();
        $res1->store_result();

        if($res2 = $conid->prepare($sqlmail))
        {
            $res2->execute();
            $res2->store_result();
            $res2->bind_result($email);
            $res2->fetch();
            $conid->close();
            // sendmail here
            sendmailpw($email,$login,$uniqid);
        }
        else
        {
            echo $conid->error;
            $conid->close();
            return false;
        }
    }
    else
    {
        echo $conid->error;
        $conid->close();
        return false;
    }
}
?>
