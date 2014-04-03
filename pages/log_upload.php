<?php
require 'includes/authcheck.inc.php';

if(!isset($_POST['submit_log']) || !$_POST['submit_log']) 
{ 
    $_SESSION['log_semaphore'] = true;
}

if(isset($_SESSION['log_semaphore']))
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
$catname = getcatname($logvalues['catID']);

            echo "<pre>" .print_r( $logvalues, true ). "</pre>";

/* Wurde das Formular abgeschickt? */
if(!isset($_POST['submit_log']) || !$_POST['submit_log']) { 
?>
    <form class="form-horizontal" name="logupload" id="logupload" method="post" action="<?php echo $_SERVER['PHP_SELF']."?show=logupload&logID=".$_GET['logID']; ?>" enctype="multipart/form-data">
    <fieldset>
    
    <!-- Form Name -->
    <legend>Upload to existing log</legend>

    <!-- hidden field for log id -->
    <input type="hidden" name="logid" value="<?php echo $logvalues['id']; ?>">
    
    <!-- Text input-->
    <div class="form-group">
      <label class="control-label col-sm-3" for="modelName">Modelname</label>
      <div class="col-sm-6">
          <input id="name" name="name" type="text" placeholder="<?php echo $logvalues['name']?>" value="<?php echo $logvalues['name']?>" class="form-control" disabled>
      </div>
    </div>
    
    <!-- hidden field for setting category id -->
    <input type="hidden" name="catid" value="<?php echo $logvalues['catID'];  ?>">
    
    <!-- Search input-->
    <div class="form-group">
      <label class="control-label col-sm-3" for="category">Category</label>
      <div class="col-sm-6">
      <input id="category" name="category" type="text" placeholder="<?php echo $logvalues['catName']; ?>" value="<?php echo $logvalues['catName']; ?>" class="form-control search-query" disabled>
      <input type="hidden" name="catname" value="<?php echo $logvalues['catName']; ?>">
      </div>
    </div>
    
    <!-- File Button --> 
    <div class="form-group">
      <label class="control-label col-sm-3" for="file_xes">Choose files<br><h6>(.xes,.mxml,.csv,.txt)</h6></label>
      <div class="col-sm-6">
        <input id="files" name="files[]" class="input-file" multiple="multiple" type="file" accept=".xes,.mxml,.csv,.txt">
      </div>
    </div>
    
    <!-- Multiple Checkboxes (inline) -->
    <div class="form-group">
      <label class="control-label col-sm-3" for="checkboxes">Validate files</label>
      <div class="col-sm-6">
        <label class="checkbox-inline" for="checkboxes-0">
          <input type="checkbox" name="validate-xes" id="checkboxes-0" value="validate">
          XES
        </label>
        <label class="checkbox-inline" for="checkboxes-1">
          <input type="checkbox" name="validate-mxml" id="checkboxes-1" value="validate">
          MXML
        </label>
      </div>
    </div>
    
    <!-- hidden field for old comment-->
    <input type="hidden" name="oldcomment" value="<?php echo $logvalues['comment']?>">
    
    <!-- hidden field for marking up as model -->
    <input type="hidden" name="type" value="log">
    
    <!-- hidden field for marking up as model -->
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
        unset($_SESSION['log_semaphore']);

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
