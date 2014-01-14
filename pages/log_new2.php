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

/** Wurde das Formular abgeschickt? */
if(!isset($_POST['submit_log']) || !$_POST['submit_log']) {
?>
<?php if(!isset($_SESSION['cname']) || !$_SESSION['cname']) { ?>
<legend>Choose a category</legend>
<!-- Search input-->
<form class="form-horizontal" method="post" name"cat" id="cat" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=newlog2">
<div class="form-group">
  <label class="control-label col-sm-3" for="search_cat">Search Category</label>
  <div class="col-sm-6">
    <input id="search_cat" name="search_cat" type="text" placeholder="Name of category" class="form-control search-query" autocomplete="off">
    <!-- Show Results -->
    <h5 id="results-cat-text">Showing results for: <b id="catsearch-string">Category</b></h5>
    <ul id="results-cat"></ul>

  </div>
</div>
</form>
<?php } ?>

<?php if(!isset($_SESSION['modelname']) || !$_SESSION['modelname']) { ?>
<legend>Choose a model</legend>
<!-- Search input-->
<form class="form-horizontal" method="post" name"mod" id="mod" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=newlog2">
<div class="form-group">
  <label class="control-label col-sm-3" for="search_mod">Search models</label>
  <div class="col-sm-6">
    <input id="search_mod" name="search_mod" type="text" placeholder="Name of model" class="form-control search-query" autocomplete="off">
    <!-- Show Results -->
    <h5 id="results-mod-text">Showing results for: <b id="modsearch-string">Model</b></h5>
    <ul id="results-mod"></ul>

  </div>
</div>
</form>
<?php } ?>

<form class="form-horizontal" name="logupload" id="logupload" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=newlog2" enctype="multipart/form-data">
<fieldset>

<!-- Form Name -->
<legend>New log</legend>

<!-- Text input-->
<div class="form-group">
  <label class="control-label col-sm-3" for="logName">Logname</label>
  <div class="col-sm-6">
    <input id="logName" name="logName" type="text" placeholder="" class="form-control" required="">
    
  </div>
</div>

<!-- hidden field for setting category id -->
<input type="hidden" name="catid" value="<?php if(isset($_SESSION['cid'])) echo $_SESSION['cid']; ?>">


<!-- Search input-->
<div class="form-group">
  <label class="control-label col-sm-3" for="category">Category</label>
  <div class="col-sm-6">
  <input id="category" name="category" type="text" placeholder="<?php if(isset($_SESSION['cname'])) echo $_SESSION['cname']; ?>" value="<?php if(isset($_SESSION['cname'])) echo $_SESSION['cname']; ?>" class="form-control search-query" disabled>
  <input type="hidden" name="catname" value="<?php if(isset($_SESSION['cname'])) echo $_SESSION['cname']; ?>">
  </div>
</div>

<!-- hidden field for setting model id -->
<input type="hidden" name="modelid" value="<?php if(isset($_SESSION['modelid'])) echo $_SESSION['modelid']; ?>">

<!-- Search input-->
<div class="form-group">
  <label class="control-label col-sm-3" for="category">Model</label>
  <div class="col-sm-6">
  <input id="modelname" name="category" type="text" placeholder="<?php if(isset($_SESSION['modelname'])) echo $_SESSION['modelname']; ?>" value="<?php if(isset($_SESSION['modelname'])) echo $_SESSION['modelname']; ?>" class="form-control search-query" disabled>
  <input type="hidden" name="modelname" value="<?php if(isset($_SESSION['modelname'])) echo $_SESSION['modelname']; ?>">
  </div>
</div>

<!-- File Button --> 
<div class="form-group">
  <label class="control-label col-sm-3" for="file_xes">Choose files<br>(*.xes, *.mxml, *.csv)</label>
  <div class="col-sm-6">
    <input id="files" name="files[]" class="input-file" multiple="multiple" type="file" accept=".xes,.mxml,.csv">
  </div>
</div>

<!-- Multiple Checkboxes (inline) -->
<div class="form-group">
  <label class="control-label col-sm-3" for="checkboxes">Validate files</label>
  <div class="col-sm-6">
    <label class="checkbox-inline" for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="validate">
      XES
    </label>
    <label class="checkbox-inline" for="checkboxes-1">
      <input type="checkbox" name="checkboxes" id="checkboxes-1" value="validate">
      MXML
    </label>
  </div>
</div>

<!-- Textarea -->
<div class="form-group">
  <label class="control-label col-sm-3" for="comment">Comment</label>
  <div class="col-sm-6">                     
    <textarea id="comment" name="comment" class="form-control" rows="4" placeholder="Write something about this log!"></textarea>
  </div>
</div>

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
        unset($_SESSION['modelid']);
        unset($_SESSION['modelname']);
        unset($_SESSION['log_semaphore']);

        if(DEBUG)
        {
            echo "<pre>" .print_r( $_SESSION, true ). "</pre>";
            echo "<pre>" .print_r( $_POST, true ). "</pre>";
            echo "<pre>" .print_r( $_FILES, true ). "</pre>";
        }

        uploadfiles_new();
    }
}
?>
