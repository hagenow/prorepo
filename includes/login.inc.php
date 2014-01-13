<?php
// prüfen auf das abgeschickte formular
if(isset( $_POST['login'] ))
{
    /** Eingabe bereinigen */
    $input = cleanlogininput();
    $login = checkadminlogin( $input['user'], $input['pass']);
    /** Prüfen ob die Anmeldung korrekt war */
    if(!checkblocked($input['user']))
    {
        if($login)
        {
            $update = updateuser($input['user']);
            if($update)
            {
                header('location: index.php?show=login');
                exit;
            }
            else
            {
                $error = 'Bei der Anmeldung ist ein Problem aufgetreten!';
            }
        }
        else
        {
            $error = 'Die Anmeldung war fehlerhaft!';
        }
    }
    else
    {
        $error = 'Ihr Konto ist deaktiviert! Bitte kontaktieren Sie den Sysadmin!';
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

        <!-- form in form - registration -->
        <form class="navbar-form navbar-right" role="form">
                <div class="form-group">
                    <button type="submit" class="btn btn-success" data-toggle="modal" data-target="#register">Sign up</button>
                </div>
        </form>
    
    </form>
<?php } else {?>
<ul class="nav navbar-nav navbar-right">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hallo <?php echo $_SESSION['user']; ?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
        <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?show=usersettings">Edit settings</a></li>
          <li class="divider"></li>
          <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?show=logout" targe="_self">Logout</a></li>
        </ul>
      </li>
    </ul>
<?php }?>
