<form class="form-horizontal" name="register" id="register" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=register">
    <fieldset>

    <div class="form-group">
        <label class="control-label col-sm-3" for="login">Login</label>
        <div class="col-sm-8">
            <input id="login" name="login" type="text" placeholder="Loginname" class="form-control" required="">
        </div>
    </div>
    
    <div class="form-group">
        <label class="control-label col-sm-3" for="password">Password</label>
        <div class="col-sm-8">
            <input id="password" name="password" type="password" placeholder="" class="form-control" required="">
            <span id="result"></span>
        </div>
    </div>
    
    <div class="form-group">
        <label class="control-label col-sm-3" for="firstname">First name</label>
        <div class="col-sm-8">
            <input id="firstname" name="firstname" type="text" placeholder="" class="form-control">
        </div>
    </div>
    
    <div class="form-group">
        <label class="control-label col-sm-3" for="lastname">Last name</label>
        <div class="col-sm-8">
            <input id="lastname" name="lastname" type="text" placeholder="" class="form-control">
        </div>
    </div>
    
    <div class="form-group">
        <label class="control-label col-sm-3" for="email">E-mail</label>
        <div class="col-sm-8">
            <input id="email" name="email" type="text" placeholder="user@domain.ext" class="form-control">
        </div>
    </div>
    
    <div class="form-group">
        <label class="control-label col-sm-3" for="affiliation">Affiliation</label>
        <div class="col-sm-8">
            <input id="affiliation" name="affiliation" type="text" placeholder="e.g. university, company name" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3"></label>
        <div class="col-sm-8">
            <button type="submit" id="submit_user" name="submit_user" value="Submit "class="btn btn-success">Register</button>
            <button type="reset" id="close_modal" name="close_modal" value"Reset and Close" class="btn btn-danger" data-dismiss="modal">Reset &amp; Close</button>
        </div>
    </div>
    <!-- place captcha here -->
    </fieldset>
</form>
