<?php require 'includes/authcheck.inc.php'; ?>

<?php if((!isset($_POST['submit_group']) || !$_POST['submit_group']) && !isset($_SESSION['groupID']) ) : ?>
    <!-- Form Name -->
    <legend>New group</legend>
<form class="form-horizontal" name="group" id="group" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=groupnew">
    <fieldset>
    <div class="form-group">
        <label class="control-label col-sm-3" for="login">Groupname</label>
        <div class="col-sm-6">
            <input id="groupName" name="groupName" type="text" placeholder="Groupname" class="form-control" required="">
        </div>
    </div>

    <!-- Textarea -->
    <div class="form-group">
      <label class="control-label col-sm-3" for="groupTags">Tags</label>
      <div class="col-sm-6">
        <textarea id="groupTags" name="groupTags" class="form-control" rows="4" placeholder="Comma-separated list of tags"></textarea>
      </div>
    </div>

<?php if(isadmin()) : ?>
    <!-- Private Mode -->
    <div class="form-group">
      <label class="control-label col-sm-3" for="comment">Private Mode</label>
      <div class="col-sm-6">
        <label class="checkbox-inline" for="privates">
          <input type="checkbox" name="private" id="private" value="1">
          Activate private mode
        </label>
      </div>
    </div>
<?php endif; ?>

    <!-- timestamp -->
    <input type="hidden" name="timestamp" value="<?php echo date("YmdHis"); ?>">

    <div class="form-group">
        <label class="control-label col-sm-3"></label>
        <div class="col-sm-6">
            <button type="submit" id="submit_group" name="submit_group" value="Submit "class="btn btn-success">Create Group</button>
        </div>
    </div>

    </fieldset>
</form>
<?php endif; ?>

<?php 
if(isset($_SESSION['groupID']) && $_SESSION['groupID'])
    require_once 'pages/group_current.php';

if(isset($_POST['submit_group']) && $_POST['submit_group'] && isset($_SESSION['groupID']) && $_SESSION['groupID'])
    require_once 'pages/group_current.php';

if(isset($_POST['submit_group']) && $_POST['submit_group'])
{
    creategroup();
    header('Location: '.$_SERVER['PHP_SELF'].'?show=groupcurrent');
}

?>
