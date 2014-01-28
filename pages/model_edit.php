<?php
require 'includes/authcheck.inc.php';

if((!isset($_POST['cid']) || !$_POST['cid'] ) && ( !isset($_POST['cname']) || !$_POST['cname'])) {
    $cid = "";
    $cname = "";
}
else {
    /** transfer sumbitted values to _SESSION and set a semaphore for exclusive
     * access to the submitted variables */
    $_SESSION['cid'] = $_POST['cid'];
    $_SESSION['cname'] = $_POST['cname'];
    $modvalues['catID'] = $_SESSION['cid'];
    $modvalues['catName'] = $_SESSION['cname'];
    $_SESSION['mod_semaphore'] = true;
}

if(isset($_SESSION['log_semaphore']))
{
        unset($_SESSION['cid']);
        unset($_SESSION['cname']);
        unset($_SESSION['modelid']);
        unset($_SESSION['modelname']);
        unset($_SESSION['log_semaphore']);
}

/* Daten auf Grund der übermittelten ID aus dem System auslesen */
$modvalues = array();
$modvalues = viewmodel($_GET['modelID']);
$modvalues['catName'] = getcatname($modvalues['catID']);
$catname = getcatname($modvalues['catID']);

/** Wurde das Formular abgeschickt? */
if(!isset($_POST['submit_model']) || !$_POST['submit_model']) { 
?>
    <?php if(!isset($_SESSION['cname']) || !$_SESSION['cname']) { ?>
        <legend>Choose a category</legend>
        <!-- Search input-->
        <form class="form-horizontal" method="post" name"cat" id="cat" action="<?php echo $_SERVER['PHP_SELF']."?show=modedit&modelID=".$_GET['modelID']; ?>">
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
    
    <form class="form-horizontal" name="modelupload" id="modelupload" method="post" action="<?php echo $_SERVER['PHP_SELF']."?show=modedit&modelID=".$_GET['modelID']; ?>" enctype="multipart/form-data">
    <fieldset>
    
    <!-- Form Name -->
    <legend>New model</legend>

    <!-- hidden field for model id -->
    <input type="hidden" name="modid" value="<?php echo $modvalues['id']; ?>">
    
    <!-- Text input-->
    <div class="form-group">
      <label class="control-label col-sm-3" for="modelName">Modelname</label>
      <div class="col-sm-6">
          <input id="name" name="name" type="text" placeholder="<?php echo $modvalues['name']?>" value="<?php echo $modvalues['name']?>" class="form-control" disabled>
      </div>
    </div>
    
    <!-- hidden field for setting category id -->
    <input type="hidden" name="catid" value="<?php if(isset($_SESSION['cid'])) { echo $_SESSION['cid']; } else { echo $modvalues['catID']; } ?>">
    
    <!-- Search input-->
    <div class="form-group">
      <label class="control-label col-sm-3" for="category">Category</label>
      <div class="col-sm-6">
      <input id="category" name="category" type="text" placeholder="<?php if(isset($_SESSION['cname'])) { echo $_SESSION['cname'];} else {echo $modvalues['catName']; } ?>" value="<?php if(isset($_SESSION['cname'])) {echo $_SESSION['cname'];} else {echo $modvalues['catName'];} ?>" class="form-control search-query" disabled>
      <input type="hidden" name="catname" value="<?php if(isset($_SESSION['cname'])) { echo $_SESSION['cname'];} else { echo $modvalues['catName']; } ?>">
      </div>
    </div>
    
    <!-- Textarea -->
    <div class="form-group">
      <label class="control-label col-sm-3" for="comment">Comment</label>
      <div class="col-sm-6">                     
      <textarea id="comment" name="comment" class="form-control" rows="4" placeholder="<?php echo $modvalues['comment']?>" value="<?php echo $modvalues['comment']?>"></textarea>
      </div>
    </div>

    <!-- hidden field for old comment-->
    <input type="hidden" name="oldcomment" value="<?php echo $modvalues['comment']?>">
    
    <!-- hidden field for marking up as model -->
    <input type="hidden" name="type" value="model">
    
    <!-- hidden field for marking up as model -->
    <input type="hidden" name="timestamp" value="<?php echo date("YmdHis"); ?>">
    
    <!-- Action submit or reset -->
    <div class="form-group">
      <label class="control-label col-sm-3"></label>
        <div class="col-sm-6">
            <!-- Indicates a successful or positive action -->
            <button type="submit" class="btn btn-success" id="submit_model" name="submit_model" value="Submit">Submit</button>
            <!-- Indicates a unsuccesful or negative action -->
            <button type="reset" class="btn btn-danger" id="reset_model" name="reset_model" value="Reset">Reset</button>
        </div>
    </div>
    
    </fieldset>
    </form>

<?php } 
else {
    if (isset( $_POST['submit_model'] ))
    {
        /** if(!isset($_POST['filetype'])) die("Der Dateityp wurde nicht 
         * angegeben!"); */
        
        unset($_SESSION['cid']);
        unset($_SESSION['cname']);
        unset($_SESSION['mod_semaphore']);

        if(DEBUG)
        {
            echo "<pre>" .print_r( $_SESSION, true ). "</pre>";
            echo "<pre>" .print_r( $_POST, true ). "</pre>";
            echo "<pre>" .print_r( $_FILES, true ). "</pre>";
        }

        if(editmodel($_POST['modid']))
        {
            echo "Successfully updated the model!";
        }
        else
        {
            echo "There was an error while update the model!";
        }
    }
} 
?>
