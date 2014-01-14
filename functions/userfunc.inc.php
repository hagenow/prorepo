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

/** Ähnlich wie checkuserlogin mit dem Zusatz der Gruppenprüfung auf 
 * Mitgliedschaft in der admin-Gruppe
 * */
function checkadminlogin($user, $pass)
{
    $conid = db_connect();

    $user = $conid->real_escape_string($user);
    $pass = $conid->real_escape_string($pass);

    $usergroup = checkgroup($user,$conid);

    if(checkuser($user,$conid))
    {
       if(checkadmin($usergroup,$conid))
       {
            $conid->close();
           return checkpass($user,$pass,$conid);
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

    $sql = "SELECT
                usergroup
             FROM
                ".TBL_PREFIX."users
             WHERE
                login = '".$user."'";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();
    $res->bind_result($usergroup);
    
    if($res->affected_rows == 1)
    {
        $res->fetch();
        $conid->close();
        return $usergroup;
    }
}

/** prüft die Gruppenzugehörigkeit für den Administrationslogin
 * */
function checkadmin($usergroup)
{
    $conid = db_connect();

    $sql = "SELECT
                groupname
             FROM
                ".TBL_PREFIX."usergroups
             WHERE
                usergroupID = '".$usergroup."'";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();
    $res->bind_result($groupname);
    
    if($res->affected_rows == 1)
    {
        $res->fetch();
        $conid->close();
        return ($groupname == "admin") ? true : false;
    }
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
    $conid = db_conect();

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

    $conid->close();

    $res = $conid->prepare($sql);
    $res->execute();
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

    /** TODO more input cleaning */
    $login = htmlspecialchars($_POST['login'], ENT_QUOTES, 'UTF-8');
    $firstname = htmlspecialchars($_POST['firstname'], ENT_QUOTES, 'UTF-8');
    $lastname = htmlspecialchars($_POST['lastname'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $affiliation = htmlspecialchars($_POST['affiliation'], ENT_QUOTES, 'UTF-8');

    /** TODO checkmail - mxrecord und aufbau 
     * */

    /** encrypt password */
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO
                ".TBL_PREFIX."users
                (login, password, firstname, lastname, email, affiliation, registerdate,verifyid)
                VALUES
                ('".$conid->real_escape_string($login)."','".$conid->real_escape_string($password)."',
                    '".$conid->real_escape_string($firstname)."','".$conid->real_escape_string($lastname)."',
                    '".$conid->real_escape_string($email)."','".$conid->real_escape_string($affiliation)."',
                    CURDATE(), '".uniqid('',TRUE)."')";

    echo $sql;

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();

    /** TODO send mail after registration to verify emailaddress */
    return ($res->affected_rows == 1) ? true : false;
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
    $input['user'] = strtolower( $input['user'] );

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

?>
