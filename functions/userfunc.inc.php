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
                repo_users
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
                repo_users
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
                    repo_users
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
                repo_users
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
                repo_usergroups
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
                repo_users
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
                repo_users
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
                repo_users
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
                repo_users
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

    $login = $_POST['login'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $affiliation = $_POST['affiliation'];

    /** TODO checkmail - mxrecord und aufbau 
     * */

    /** TODO cleaninput */

    /** encrypt password */
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO
                ".TBL_PREFIX."users
                (login, password, firstname, lastname, email, affiliation, registerdate)
                VALUES
                ('".$conid->real_escape_string($login)."','".$conid->real_escape_string($password)."',
                    '".$conid->real_escape_string($firstname)."','".$conid->real_escape_string($lastname)."',
                    '".$conid->real_escape_string($email)."','".$conid->real_escape_string($affiliation)."',
                    CURDATE())";

    echo $sql;

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();

    $ergb = $res->affected_rows;
    var_dump( $ergb );

    return ($res->affected_rows == 1) ? true : false;

}

/** bereinige Nutzereingaben */
function cleaninput()
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
                repo_users
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
