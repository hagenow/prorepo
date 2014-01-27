<?php require 'includes/authcheck.inc.php'; ?>

<?php if((!isset($_POST['submit_group']) || !$_POST['submit_group']) && !isset($_SESSION['groupID']) ) : ?>
<form class="form-horizontal" name="group" id="group" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=groupnew">
    <fieldset>
    <div class="form-group">
        <label class="control-label col-sm-3" for="login">Groupname</label>
        <div class="col-sm-8">
            <input id="groupName" name="groupName" type="text" placeholder="Groupname" class="form-control" required="">
        </div>
    </div>

    <!-- timestamp -->
    <input type="hidden" name="timestamp" value="<?php echo date("YmdHis"); ?>">

    <div class="form-group">
        <label class="control-label col-sm-3"></label>
        <div class="col-sm-8">
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
    creategroup();

?>
