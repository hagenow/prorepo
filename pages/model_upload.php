<?php
require 'includes/authcheck.inc.php';

$_SESSION['mod_semaphore'] = true;

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

/* Wurde das Formular abgeschickt? */
if(!isset($_POST['submit_model']) || !$_POST['submit_model']) { 
?>
    <form class="form-horizontal" name="modelupload" id="modelupload" method="post" action="<?php echo $_SERVER['PHP_SELF']."?show=modupload&modelID=".$_GET['modelID']; ?>" enctype="multipart/form-data">
    <fieldset>
    
    <!-- Form Name -->
    <legend>Upload to existing model</legend>

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
    <input type="hidden" name="catid" value="<?php echo $modvalues['catID'];  ?>">
    
    <!-- Search input-->
    <div class="form-group">
      <label class="control-label col-sm-3" for="category">Category</label>
      <div class="col-sm-6">
      <input id="category" name="category" type="text" placeholder="<?php echo $modvalues['catName']; ?>" value="<?php echo $modvalues['catName']; ?>" class="form-control search-query" disabled>
      <input type="hidden" name="catname" value="<?php echo $modvalues['catName']; ?>">
      </div>
    </div>
    
    <!-- File Button -->
    <div class="form-group">
      <label class="control-label col-sm-3" for="file_xes">Choose files<br><h6>(.pnml,.png,.jpg,.pdf,.eps,.svg,.xml)</h6></label>
      <div class="col-sm-6">
        <input id="files" name="files[]" class="input-file" multiple="multiple" type="file" accept=".pnml,.png,.jpg,.pdf,.eps,.svg,.xml">
      </div>
    </div>

    
    <!-- Multiple Checkboxes (inline) -->
    <div class="form-group">
      <label class="control-label col-sm-3" for="checkboxes">Validate PNML file</label>
      <div class="col-sm-6">
        <label class="checkbox-inline" for="checkboxes-0">
          <input type="checkbox" name="checkboxes" id="checkboxes-0" value="validate">
          validate
        </label>
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
        
        uploadfiles_existing();
    }
} 
?>
