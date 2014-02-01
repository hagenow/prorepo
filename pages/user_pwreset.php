<?php if(isset($_GET['action']) && isset($_GET['action']) && $_GET['action'] == "resetpw" && resetpwfromid($_GET['id'])) : ?>
You resetted your password. You can now login!
<?php elseif(isset($_GET['action']) && $_GET['action'] == "sendreq") : ?>
Please check your mails for further actions.
<?php sendpwreq($_POST['ulogin']); ?>
<?php endif; ?>



<?php if(isset($_GET['id']) && !(isset($_GET['action']) && $_GET['action'] == "resetpw")) : ?>
<form class="form-horizontal" name="update" id="update" method="post" action="<?php echo $_SERVER['PHP_SELF']."?show=userpwreset&id=".$_GET['id']."&action=resetpw"; ?>">
    <fieldset>
    <legend>Update password</legend>
    <div class="form-group">
        <label class="control-label col-sm-3" for="password">Password</label>
        <div class="col-sm-8">
            <input id="password" name="password" type="password" placeholder="" class="form-control">
            <span id="result"></span>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3"></label>
        <div class="col-sm-8">
            <button type="submit" id="submit_pw" name="submit_pw" value="Submit "class="btn btn-success">Set password</button>
        </div>
    </div>
    </fieldset>
</form>
<?php elseif(!(isset($_GET['action']) && $_GET['action'] == "sendreq") && !(isset($_GET['action']) && $_GET['action'] == "resetpw")) : ?> 
<form class="form-horizontal" name="update" id="update" method="post" action="<?php echo $_SERVER['PHP_SELF']."?show=userpwreset&action=sendreq"; ?>">
    <fieldset>
    <legend>Enter your login to get a new password</legend>
    <div class="form-group">
        <label class="control-label col-sm-3" for="login">Login</label>
        <div class="col-sm-8">
        <input id="ulogin" name="ulogin" type="text" placeholder="<?php echo $_SESSION['user']; ?>" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3"></label>
        <div class="col-sm-8">
            <button type="submit" id="submit_pw" name="submit_pw" value="Submit "class="btn btn-success">Request new password</button>
        </div>
    </div>
    </fieldset>
</form>
<?php endif; ?>
