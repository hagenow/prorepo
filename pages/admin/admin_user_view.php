<?php if(isset($_GET['userID']) && isset($_POST['delete_user']) && isadmin()) : ?>
<?php 
$id = cleaninput( $_GET['userID']);
if(adm_deleteuser($id))
    echo "User deleted!";
else
    echo "There was an error with the submitted ID!";
?>
<?php endif; ?>
<?php if(isset($_GET['userID']) && isset($_POST['update_user']) && isadmin()) : ?>
<?php 
$id = cleaninput( $_GET['userID']);
adm_updateuserdata($id); 
?>
<?php endif; ?>
<?php if(isset($_GET['userID']) && !isset($_POST['delete_user']) && !isset($_POST['update_user']) &&isadmin()) : ?>
<?php 
$userdata = array();
$id = cleaninput( $_GET['userID']);
$userdata = viewuser($id);

if($userdata['usergroup'] == 1)
    $groupname = "Administrator";
elseif($userdata['usergroup'] == 2)
    $groupname = "Member";
elseif($userdata['usergroup'] == 3)
    $groupname = "Blocked";
else
    $groupname = "";

?>
    <form class="form-horizontal" name="edit" id="edit" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=adminuser&action=view&userID=<?php echo $id; ?>">
    <fieldset>
    <legend>Edit profile</legend>
    <div class="form-group">
        <label class="control-label col-sm-3" for="login">Login</label>
        <div class="col-sm-8">
        <input id="login" name="login" type="text" placeholder="<?php echo $userdata['login']; ?>" class="form-control" disabled>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="login">Current group</label>
        <div class="col-sm-8">
        <input id="oldgroup" name="oldgroup" type="text" placeholder="<?php echo $groupname; ?>" class="form-control" disabled>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3" for="login">New group</label>
        <div class="col-sm-8">
        <select name="usergroup" id="usergroup" class="form-control">
        <option value="1"<?php if($userdata['usergroup'] == 1) echo " selected"; ?>>Administrator</option>
            <option value="2"<?php if($userdata['usergroup'] == 2) echo " selected"; ?>>Member</option>
        </select>
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
            <button type="submit" id="update_user" name="update_user" value="update" class="btn btn-success">Update user</button>
            <button type="submit" id="delete_user" name="delete_user" value="Delete" class="btn btn-danger">Delete user</button>
        </div>
    </div>
    <!-- place captcha here -->
    </fieldset>
</form>
<?php endif; ?>

<?php if(!isset($_GET['userID']) && isadmin()) : ?>
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">
        <h3 class="panel-title"><h3>View users</h3></h3>
    </div>
    <div class="panel-body">
        <p>You can also edit and delete a users! Just click on the username!</p>
    </div>

    <!-- List group -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="text-center">Login</th>
                <th class="text-center">First- & lastname</th>
                <th class="text-center">eMail</th>
                <th class="text-center">Affiliation</th>
                <th class="text-center">Last login</th>
            </tr>
        </thead>
        <tbody>
            <?php viewusers(); ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
