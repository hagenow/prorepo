<?php
/* list all users */
function viewusers()
{
    $conid = db_connect();

    $sql = "SELECT *
            FROM ".TBL_PREFIX."users";
    
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
                $lastlogin = $row['lastlogin'];

                $html .= "<tr>";
                $html .= "<td class=\"text-center\"><a href=\"". $_SERVER['PHP_SELF']."?show=adminuser&action=view&userID=".$id."\">".$login."</a></td>";
                $html .= "<td class=\"text-center\"><a href=\"". $_SERVER['PHP_SELF']."?show=adminuser&action=view&userID=".$id."\">".$firstname." ".$lastname."</a></td>";
                $html .= "<td class=\"text-center\"><a href=\"mailto:".$email."\">".$email."</a></td>";
                $html .= "<td class=\"text-center\">".$affiliation."</td>";
                $html .= "<td class=\"text-center\">".$lastlogin."</td>";
                $html .= "</tr>";

            }
            echo $html;
        }
        else
        {
            $html = "";
            $html .= "<tr>";
            $html .= "<td class=\"text-center\" colspan=\"5\">No users can be unblocked!</td>";
            $html .= "</tr>";

            echo $html;
        }
    }
    else
    {
        echo $conid->error;
        $conid-close();
    }
}
function viewuser($id)
{
    $conid = db_connect();

    $userdata = array();

    $sql = "SELECT 
                login,firstname, lastname, email, affiliation,usergroup
            FROM
                ".TBL_PREFIX."users
            WHERE
                userID = '$id'";

    $res = $conid->prepare($sql);
    $res->execute();
    $res->store_result();
    $res->bind_result($userdata['login'],$userdata['firstname'],$userdata['lastname'],$userdata['email'],$userdata['affiliation'],$userdata['usergroup']);
    $res->fetch();

    if($res->affected_rows == 1)
    {
        $res->fetch();
        return $userdata;
    }
    
}

/* update user data */
function adm_updateuserdata($id)
{
    $conid = db_connect();

    $firstname = cleaninput($_POST['firstname']);
    $lastname = cleaninput($_POST['lastname']);
    $email = checkmail($_POST['email']);
    $affiliation = cleaninput($_POST['affiliation']);
    $usergroup = cleaninput($_POST['usergroup']);

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
                         affiliation = '".$affiliation."',
                         usergroup = '".$usergroup."'
                     WHERE
                         userID = '$id'";
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
                         affiliation = '".$affiliation."',
                         usergroup = '".$usergroup."'
                     WHERE
                         userID = '$id'";
        $res = $conid->prepare($sql_wopw);
    }

    $res->execute();
    $res->store_result();
    $conid->close();

    return ($res->affected_rows == 1) ? true : false;
}

/* delete user */
function adm_deleteuser($id)
{
    $conid = db_connect();

    $sql = "DELETE FROM ".TBL_PREFIX."users
            WHERE userID = '$id'";

    $res = $conid->query($sql);

    return ($conid->affected_rows == 1 ) ? true : false;
}


/* get all unvalidated user and return them */
function getunapprovedusers()
{
    $conid = db_connect();

    $sql = "SELECT userID, login, firstname, lastname, email, affiliation
            FROM ".TBL_PREFIX."users
            WHERE approved = '0'"; 

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
                $html .= "<td class=\"text-center\"><input type=\"checkbox\" name=\"userids[]\" value=\"".$id."\" /></td>";
                $html .= "<td class=\"text-center\">".$login."</td>";
                $html .= "<td class=\"text-center\">".$firstname." ".$lastname."</td>";
                $html .= "<td class=\"text-center\">".$email."</td>";
                $html .= "<td class=\"text-center\">".$affiliation."</td>";
                $html .= "</tr>";
            }

            $html .= "<tr>";
            $html .= "<td class=\"text-center\" colspan=\"5\"><input type=\"submit\" id=\"approve\" name=\"approve\" class=\"btn btn-default\" value=\"Approve selected users\"></td>";
            $html .= "</tr>";

            echo $html;
        }
        else
        {
            $html .= "<tr>";
            $html .= "<td class=\"text-center\" colspan=\"5\">No users can be approved!</td>";
            $html .= "</tr>";

            echo $html;
        }
    }
    else
    {
        echo $conid->error;
        $conid-close();
    }
}
/* approve selected users from list */
function approveusers()
{
    $conid = db_connect();

    $sql = "UPDATE ".TBL_PREFIX."users
            SET approved = 1
            WHERE userID = ?";

    if($res = $conid->prepare($sql))
    {
        $res->bind_param('i', $id);

        foreach($_POST['userids'] as $val)
        {
            $id = $val;
            $res->execute();
        } 
        echo "All users were approved!";
        $conid->close();
    }
    else
    {
        echo $conid->error;
        $conid->close();
    }
}

/* get a list of blocked users */
function getblockedusers()
{
    $conid = db_connect();

    $sql = "SELECT userID, login, firstname, lastname, email, affiliation
            FROM ".TBL_PREFIX."users
            WHERE blocked = '1'"; 
    
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
                $html .= "<td class=\"text-center\"><input type=\"checkbox\" name=\"userids[]\" value=\"".$id."\" /></td>";
                $html .= "<td class=\"text-center\">".$login."</td>";
                $html .= "<td class=\"text-center\">".$firstname." ".$lastname."</td>";
                $html .= "<td class=\"text-center\">".$email."</td>";
                $html .= "<td class=\"text-center\">".$affiliation."</td>";
                $html .= "</tr>";

            }
            $html .= "<tr>";
            $html .= "<td class=\"text-center\" colspan=\"5\"><input type=\"submit\" id=\"unblock\" name=\"unblock\" class=\"btn btn-default\" value=\"Unblock selected users\"></td>";
            $html .= "</tr>";

            echo $html;
        }
        else
        {
            $html = "";
            $html .= "<tr>";
            $html .= "<td class=\"text-center\" colspan=\"5\">No users can be unblocked!</td>";
            $html .= "</tr>";

            echo $html;
        }
    }
    else
    {
        echo $conid->error;
        $conid-close();
    }
}
/* unblock users from $_POST */
function unblockusers()
{
    $conid = db_connect();

    $sql = "UPDATE ".TBL_PREFIX."users
            SET blocked = 0
            WHERE userID = ?";

    if($res = $conid->prepare($sql))
    {
        $res->bind_param('i', $id);

        foreach($_POST['userids'] as $val)
        {
            $id = $val;
            $res->execute();
        } 
        echo "All users were unblocked!";
        $conid->close();
    }
    else
    {
        echo $conid->error;
        $conid->close();
    }
}

?>
