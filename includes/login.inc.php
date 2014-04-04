<?php
// prüfen auf das abgeschickte formular
if(isset( $_POST['login'] ))
{
    /** Eingabe bereinigen */
    $input = cleanlogininput();
    $login = checkuserlogin( $input['user'], $input['pass']);
    /** Prüfen ob die Anmeldung korrekt war */
    if(!checkblocked($input['user']))
    {
        if(checkapproved($input['user']))
        {
            if($login)
            {
                checkgroup($input['user']);
                $update = updateuser($input['user']);
                if($update)
                {
                    header('location: index.php?show=login');
                    exit;
                }
                else
                {
                    $error = "There was an error while login, go back and try again!";
                }
            }
            else
            {
                $error = "You entered wrong userdata, go back and try again!<br/>";
                $error .= "If you want to reset your password, click <a href=\"".$_SERVER['PHP_SELF']."?show=userpwreset\"><strong>here</strong></a>";
            }
        }
        else
        {
            $error = "Your account is not approved yet - please stay tuned!";
        }
    }
    else
    {
        $error = "Your account get blocked due to a amount of failed logins!<br/>";
        $error .= "Click <a href=\"".$_SERVER['PHP_SELF']."?show=admincontact\"><strong>here</strong></a> to unblock your account.";
    }
}


?>
<?php  
if(!isset($_SESSION['angemeldet']) || !$_SESSION['angemeldet'])
{?>
    <form class="navbar-form navbar-right" role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
              <input type="text" placeholder="Username" class="form-control" name="user" id="user" required autofocus>
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control" name="pass" id="pass" required>
            </div>
            <button type="submit" class="btn btn-primary" id="login" name="login">Sign in</button>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#register">Sign up</button>
        <!-- form in form - registration -->
    </form>
<?php } else {?>
<ul class="nav navbar-nav navbar-right">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Signed in as <?php echo $_SESSION['user']; ?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
        <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?show=usersettings">Edit settings</a></li>
          <li class="divider"></li>
          <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?show=logout" targe="_self">Logout</a></li>
        </ul>
      </li>
    </ul>
<?php }?>
