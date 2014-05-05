<?php
require 'includes/authcheck.inc.php';

/* Daten auf Grund der übermittelten ID aus dem System auslesen */
$grpvalues = array();
$grpvalues = viewgroup($_GET['groupID']);

if(!isset($_POST['submit_group']) || !$_POST['submit_group']) { 
?>
    
    <form class="form-horizontal" name="groupedit" id="groupedit" method="post" action="<?php echo $_SERVER['PHP_SELF']."?show=groupedit&groupID=".$_GET['groupID']; ?>" enctype="multipart/form-data">
    <fieldset>
    
    <!-- Form Name -->
    <legend>New group</legend>

    <!-- hidden field for group id -->
    <input type="hidden" name="grpid" value="<?php echo $grpvalues['id']; ?>">
    
    <!-- Text input-->
    <div class="form-group">
      <label class="control-label col-sm-3" for="groupName">Groupname</label>
      <div class="col-sm-6">
          <input id="name" name="name" type="text" placeholder="<?php echo $grpvalues['name']?>" value="<?php echo $grpvalues['name']?>" class="form-control" disabled>
      </div>
    </div>

    <!-- Textarea -->
    <div class="form-group">
      <label class="control-label col-sm-3" for="tags">Tags</label>
      <div class="col-sm-6">                     
      <textarea id="tags" name="tags" class="form-control" rows="4" value="<?php echo $grpvalues['tags']?>"></textarea>
      </div>
    </div>

<?php if(isadmin()) : ?>
    <!-- Private Mode -->
    <div class="form-group">
      <label class="control-label col-sm-3" for="comment">Private Mode</label>
      <div class="col-sm-6">
        <label class="checkbox-inline" for="privates">
        <input type="checkbox" name="private" id="private" value="1" <?php if($modvalues['private'] == TRUE) echo "checked=\"checked\""; ?>>
          Activate private mode
        </label>
      </div>
    </div>
<?php endif; ?>

    <!-- hidden field for marking up as model -->
    <input type="hidden" name="timestamp" value="<?php echo date("YmdHis"); ?>">

    <!-- hidden field for old tags-->
    <input type="hidden" name="oldtags" value="<?php echo $grpvalues['tags']?>">
    
    <!-- Action submit or reset -->
    <div class="form-group">
      <label class="control-label col-sm-3"></label>
        <div class="col-sm-6">
            <!-- Indicates a successful or positive action -->
            <button type="submit" class="btn btn-success" id="submit_group" name="submit_group" value="Submit">Submit</button>
            <!-- Indicates a unsuccesful or negative action -->
            <button type="reset" class="btn btn-danger" id="reset_group" name="reset_group" value="Reset">Reset</button>
        </div>
    </div>
    
    </fieldset>
    </form>

<?php } 
else {
    if (isset( $_POST['submit_group'] ))
    {
        if(DEBUG)
        {
            echo "<pre>" .print_r( $_SESSION, true ). "</pre>";
            echo "<pre>" .print_r( $_POST, true ). "</pre>";
            echo "<pre>" .print_r( $_FILES, true ). "</pre>";
        }

        echo $_POST['grpid'];
        if(editgroup($_POST['grpid']))
        {
            echo "Successfully updated the group!";
        }
        else
        {
            echo "There was an error while update the group!";
        }
    }
} 
?>
