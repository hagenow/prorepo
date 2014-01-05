<?php

function db_connect()
{
    $conid = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

    if(!$conid)
    {
        die('Verbindung konnte nicht hergestellt werden ('.mysqli_connect_errno().') : ' . mysqli_connect_error());
    } 
    else
    {
        return $conid;
    }
}

/** Prüft den Login im Userbereich, übergibt Parameter an andere 
 * check-Funktionen
 * */
function checkuserlogin($user, $pass, $conid)
{
    /** übergebene Variablen bereinigen */
    $user = stripslashes($user);
    $pass = stripslashes($pass);
    $user = mysqli_real_escape_string($conid,$user);
    $pass = mysqli_real_escape_string($conid,$pass);

    if(checkuser($user,$conid))
    {
        if(checkblocked($user,$conid) == 0)
        {
            return checkpass($user,$pass,$conid);
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
} 

/** Ähnlich wie checkuserlogin mit dem Zusatz der Gruppenprüfung auf 
 * Mitgliedschaft in der admin-Gruppe
 * */
function checkadminlogin($user, $pass, $conid)
{
    /** übergebene Variablen bereinigen */
    $user = stripslashes($user);
    $pass = stripslashes($pass);
    $user = mysqli_real_escape_string($conid,$user);
    $pass = mysqli_real_escape_string($conid,$pass);

    $usergroup = checkgroup($user,$conid);

    if(checkuser($user,$conid))
    {
        if(checkblocked($user,$conid) == 0)
        {
            if(checkadmin($usergroup,$conid))
            {
                return checkpass($user,$pass,$conid);
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
} 

/** Prüft ob der Nutzer existiert
 * */
function checkuser($user, $conid)
{
    $sql = "SELECT
                login
             FROM
                repo_users
             WHERE
                login = '".$user."'";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();
    
    return ($res->affected_rows == 1) ?  true : false;
}

/** checkblocked($user, $count)
 * Falls ein User gesperrt ist, verweigere den Login.
 * */
function checkblocked($user, $conid)
{
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

    return $blocked;
}

/** Prüft das übergebene Passwort
 * */
function checkpass($user, $pass, $conid)
{
        /** leere Variablen deklarieren */
        $salt = '';
        $failedlogins = '';
        $pass_from_db = '';

        /** SQL Statement, welches den salt ausließt */
        $sql1 = "SELECT
                    salt
                 FROM
                    repo_users
                 WHERE 
                    login = '".$user."'";

        /** Verbindungen mit obigem SQL Statement vorbereiten */
        $res = $conid->prepare( $sql1 );
        /** Verbundung ausführen */
        $res->execute();
        /** Ergebnisse abspeichern */
        $res->store_result();
        /** Ergebnisse an Variablen binden */
        $res->bind_result($salt);

        /** wenn nur eine Ergebniszeile aus der DB zurückgeliefert wird,
         * dann hole das Ergebnis, prüfe die Anzahl der fehlgeschlagenen Logins 
         * und definiere $salt, danach gebe das $res wieder frei.
         * */
        if($res->affected_rows == 1 )
        {
            $res->fetch();
            if(checkfailedlogins($user,$conid) < 10)
            {
                /** encodiere PW mit crypt-Funktion, salt beinhaltet $6$, somit wird 
                 * SHA512 benutzt 
                 */
                $pw_enc = crypt($pass, $salt);

                $res->free_result();
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }

        $sql2 = "SELECT
                    password
                 FROM
                    repo_users
                 WHERE
                    login = '".$user."'";

        $res = $conid->prepare($sql2);
        /** Verbundung ausführen */
        $res->execute();
        /** Ergebnisse abspeichern */
        $res->store_result();
        /** Ergebnisse an Variablen binden */
        $res->bind_result($pass_from_db);
        
        /** wenn nur eine Ergebniszeile aus der DB zurückgeliefert wird,
         * dann hole dir das Ergebnis und binde es an $pass_from_db,
         * vergleiche das berechnete PW und das aus der DB und gebe
         * true oder false zurück, danach gebe das $res wieder frei.
         * */
        if($res->affected_rows == 1)
        {
            $res->fetch();
            if($pass_from_db === $pw_enc)
            {
                $res->free_result();
                return true;
            }
            else
            {
                $failedlogins = checkfailedlogins($user,$conid) + 1;
                $count = updatefailedlogins($user,$failedlogins,$conid);
                if($count == 10) blockuser($user,$conid);
                return false;
            }
        }
        else
        {
            $res->free_result();
            return false;
        }

}

/** prüft die assozierte Gruppe des Users 
 * */
function checkgroup($user, $conid)
{
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
        return $usergroup;
    }
}

/** prüft die Gruppenzugehörigkeit für den Administrationslogin
 * */
function checkadmin($usergroup,$conid)
{
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
        return ($groupname == "admin") ? true : false;
    }
}

/** lese die Anzahl der fehlerhaften Logins aus
 * */
function checkfailedlogins($user, $conid)
{
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

    return $failedlogins;
}

/** updatefailedlogins($user, $count, $conid)
 * Setzt den failedlogin-Counter auf 0 wenn Login erfolgreich, ansonsten zählt 
 * er eins hoch!
 * */
function updatefailedlogins($user, $count, $conid)
{
    $sql = "UPDATE
                repo_users
             SET
                failedlogins = '".$count."'
             WHERE
                login = '".$user."'";

    $res = $conid->query($sql);
    return $count;
}

/** blockuser($user, $count)
 * Bei mehr als einer erlaubten Anzahl fehlerhafter Logins wird der Benutzer 
 * gesperrt.
 * */
function blockuser($user, $conid)
{
    $sql = "UPDATE
                repo_users
             SET
                blocked = 1
             WHERE
                login = '".$user."'";

    $res = $conid->query($sql);
}
?>
