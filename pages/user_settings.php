<?php
require 'includes/authcheck.inc.php';
$userdata = getuserdata();
if(DEBUG)
{
    echo "<pre>";
    print_r($userdata);
    echo "</pre>";
}
?>
<form class="form-horizontal" name="update" id="update" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=updateuser">
    <fieldset>
    <legend>Update profile</legend>
    <div class="form-group">
        <label class="control-label col-sm-3" for="login">Login</label>
        <div class="col-sm-8">
        <input id="login" name="login" type="text" placeholder="<?php echo $_SESSION['user']; ?>" class="form-control" disabled>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="password">Password</label>
        <div class="col-sm-8">
            <input id="password" name="password" type="password" placeholder="" class="form-control">
            <span id="result"></span>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="firstname">First name</label>
        <div class="col-sm-8">
        <input id="firstname" name="firstname" type="text" value="<?php echo $userdata['firstname']; ?>" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="lastname">Last name</label>
        <div class="col-sm-8">
            <input id="lastname" name="lastname" type="text" value="<?php echo $userdata['lastname']; ?>" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="email">E-mail</label>
        <div class="col-sm-8">
            <input id="email" name="email" type="text" value="<?php echo $userdata['email']; ?>" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="affiliation">Affiliation</label>
        <div class="col-sm-8">
        <input id="affiliation" name="affiliation" type="text" value="<?php echo $userdata['affiliation']; ?>" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3"></label>
        <div class="col-sm-8">
            <button type="submit" id="submit_user" name="submit_user" value="update "class="btn btn-success">Update profile</button>
            <button type="reset" value"Reset" class="btn btn-danger">Reset</button>
        </div>
    </div>
    <!-- place captcha here -->
    </fieldset>
</form>

