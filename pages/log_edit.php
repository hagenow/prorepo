<?php
require 'includes/authcheck.inc.php';

/** wurden Category ID und Name bereits ermittelt? Falls nein, dann setze $cid 
 * und $cname auf einen leeren String - ist das obsolet? */
if((!isset($_POST['cid']) || !$_POST['cid'] ) && ( !isset($_POST['cname']) || !$_POST['cname'])) {
    $cid = "";
    $cname = "";
}
else
{
    /** transfer sumbitted values to _SESSION and set a semaphore for exclusive 
     * access to the submitted variables */
    $_SESSION['cid'] = $_POST['cid'];
    $_SESSION['cname'] = $_POST['cname'];
    $logvalues['catID'] = $_SESSION['cid'];
    $logvalues['catName'] = $_SESSION['cname'];
    $_SESSION['log_semaphore'] = true;
}
if((!isset($_POST['modelid']) || !$_POST['modelid'] ) && ( !isset($_POST['modelname']) || !$_POST['modelname'])) {
    $modelid = "";
    $modelname = "";
}
else
{
    $_SESSION['modelid'] = $_POST['modelid'];
    $_SESSION['modelname'] = $_POST['modelname'];
    $logvalues['modelID'] = $_SESSION['modelid'];
    $logvalues['modelName'] = $_SESSION['modelname'];
    $_SESSION['log_semaphore'] = true;
}

if(isset($_SESSION['mod_semaphore']))
{
        unset($_SESSION['cid']);
        unset($_SESSION['cname']);
        unset($_SESSION['modelid']);
        unset($_SESSION['modelname']);
        unset($_SESSION['mod_semaphore']);
}

/* Daten auf Grund der übermittelten ID aus dem System auslesen */
$logvalues = array();
$logvalues = viewlog($_GET['logID']);
$logvalues['catName'] = getcatname($logvalues['catID']);
$logvalues['modelName'] = getmodname($logvalues['modelID']);

/** Wurde das Formular abgeschickt? */
if(!isset($_POST['submit_log']) || !$_POST['submit_log']) { 
?>
    <?php if(!isset($_SESSION['cname']) || !$_SESSION['cname']) { ?>
        <legend>Category</legend>
        <!-- Search input-->
        <form class="form-horizontal" method="post" name"cat" id="cat" action="<?php echo $_SERVER['PHP_SELF']."?show=logedit&logID=".$_GET['logID']; ?>">
        <div class="form-group">
          <label class="control-label col-sm-3" for="search_cat">Change Category</label>
          <div class="col-sm-6">
          <input id="search_cat" name="search_cat" type="text" placeholder="Select another category" class="form-control search-query" autocomplete="off">
            <!-- Show Results -->
            <h5 id="results-cat-text">Showing results for: <b id="catsearch-string">Category</b></h5>
            <ul id="results-cat"></ul>
            
          </div>
        </div>
        </form>
    <?php } ?>
    
    <?php if(!isset($_SESSION['modelname']) || !$_SESSION['modelname']) { ?>
    <legend>Model</legend>
    <!-- Search input-->
    <form class="form-horizontal" method="post" name"mod" id="mod" action="<?php echo $_SERVER['PHP_SELF']."?show=logedit&logID=".$_GET['logID']; ?>">
    <div class="form-group">
      <label class="control-label col-sm-3" for="search_mod">Change model</label>
      <div class="col-sm-6">
        <input id="search_mod" name="search_mod" type="text" placeholder="Name of model" class="form-control search-query" autocomplete="off">
        <!-- Show Results -->
        <h5 id="results-mod-text">Showing results for: <b id="modsearch-string">Model</b></h5>
        <ul id="results-mod"></ul>
    
      </div>
    </div>
    </form>
    <?php } ?>

    <form class="form-horizontal" name="logupload" id="logupload" method="post" action="<?php echo $_SERVER['PHP_SELF']."?show=logedit&logID=".$_GET['logID']; ?>" enctype="multipart/form-data">
    <fieldset>
    
    <!-- Form Name -->
    <legend>New log</legend>

    <!-- hidden field for log id -->
    <input type="hidden" name="logid" value="<?php echo $logvalues['id']; ?>">
    
    <!-- Text input-->
    <div class="form-group">
      <label class="control-label col-sm-3" for="logName">Logname</label>
      <div class="col-sm-6">
          <input id="name" name="name" type="text" placeholder="<?php echo $logvalues['name']?>" value="<?php echo $logvalues['name']?>" class="form-control" disabled>
      </div>
    </div>
    
    <!-- Text input-->
    <div class="form-group">
      <label class="control-label col-sm-3" for="modelName">Modelname</label>
      <div class="col-sm-6">
      <input id="name" name="name" type="text" placeholder="<?php if(isset($_SESSION['modelname'])) { echo $_SESSION['modelname'];} else { echo $logvalues['modelName']; } ?>" value="<?php if(isset($_SESSION['modelname'])) { echo $_SESSION['modelname'];} else { echo $logvalues['modelName']; } ?>" class="form-control" disabled>
      </div>
    </div>

    <!-- hidden field for setting category id -->
    <input type="hidden" name="modid" value="<?php if(isset($_SESSION['modelid'])) { echo $_SESSION['modelid']; } else { echo $logvalues['modelID']; } ?>">

    <!-- Search input-->
    <div class="form-group">
      <label class="control-label col-sm-3" for="category">Category</label>
      <div class="col-sm-6">
      <input id="category" name="category" type="text" placeholder="<?php if(isset($_SESSION['cname'])) { echo $_SESSION['cname'];} else {echo $logvalues['catName']; } ?>" value="<?php if(isset($_SESSION['cname'])) {echo $_SESSION['cname'];} else {echo $logvalues['catName'];} ?>" class="form-control search-query" disabled>
      <input type="hidden" name="catname" value="<?php if(isset($_SESSION['cname'])) { echo $_SESSION['cname'];} else { echo $logvalues['catName']; } ?>">
      </div>
    </div>
    
    <!-- hidden field for setting category id -->
    <input type="hidden" name="catid" value="<?php if(isset($_SESSION['cid'])) { echo $_SESSION['cid']; } else { echo $logvalues['catID']; } ?>">
    
    <!-- Textarea -->
    <div class="form-group">
      <label class="control-label col-sm-3" for="comment">Comment</label>
      <div class="col-sm-6">                     
      <textarea id="comment" name="comment" class="form-control" rows="4" placeholder="<?php echo $logvalues['comment']?>" value="<?php echo $logvalues['comment']?>"></textarea>
      </div>
    </div>

<?php if(isadmin()) : ?>
    <!-- Private Mode -->
    <div class="form-group">
      <label class="control-label col-sm-3" for="comment">Private Mode</label>
      <div class="col-sm-6">
        <label class="checkbox-inline" for="privates">
            <input type="checkbox" name="private" id="private" value="1" <?php if($logvalues['private'] == TRUE) echo "checked=\"checked\""; ?>>
          Activate private mode
        </label>
      </div>
    </div>
<?php endif; ?>

    <!-- hidden field for old comment-->
    <input type="hidden" name="oldcomment" value="<?php echo $logvalues['comment']?>">
    
    <!-- hidden field for marking up as log -->
    <input type="hidden" name="type" value="log">
    
    <!-- hidden field for marking up as log -->
    <input type="hidden" name="timestamp" value="<?php echo date("YmdHis"); ?>">
    
    <!-- Action submit or reset -->
    <div class="form-group">
      <label class="control-label col-sm-3"></label>
        <div class="col-sm-6">
            <!-- Indicates a successful or positive action -->
            <button type="submit" class="btn btn-success" id="submit_log" name="submit_log" value="Submit">Submit</button>
            <!-- Indicates a unsuccesful or negative action -->
            <button type="reset" class="btn btn-danger" id="reset_log" name="reset_log" value="Reset">Reset</button>
        </div>
    </div>
    
    </fieldset>
    </form>

<?php } 
else {
    if (isset( $_POST['submit_log'] ))
    {
        /** if(!isset($_POST['filetype'])) die("Der Dateityp wurde nicht 
         * angegeben!"); */
        
        unset($_SESSION['cid']);
        unset($_SESSION['cname']);
        unset($_SESSION['modelid']);
        unset($_SESSION['modelname']);
        unset($_SESSION['log_semaphore']);

        if(DEBUG)
        {
            echo "<pre>" .print_r( $_SESSION, true ). "</pre>";
            echo "<pre>" .print_r( $_POST, true ). "</pre>";
            echo "<pre>" .print_r( $_FILES, true ). "</pre>";
        }

        if(editlog($_POST['logid']))
        {
            echo "Successfully updated the log!";
        }
        else
        {
            echo "There was an error while update the log!";
        }
    }
} 
?>
