<?php
require 'includes/authcheck.inc.php';

if((!isset($_POST['cid']) || !$_POST['cid'] ) && ( !isset($_POST['cname']) || !$_POST['cname'])) {
    $cid = "";
    $cname = "";
}
else
{
    $cid = $_POST['cid'];
    $cname = $_POST['cname'];
}

/** Wurde das Formular abgeschickt? */
if(!isset($_POST['submit_log']) || !$_POST['submit_log']) {
?>
<?php if(!isset($_POST['cname']) || !$_POST['cname']) { ?>
<legend>Choose a category</legend>
<!-- Search input-->
<form class="form-horizontal" method="post" name"cat" id="cat" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=newmod">
<div class="form-group">
  <label class="control-label col-sm-3" for="search_cat">Search Category</label>
  <div class="col-sm-6">
    <input id="search_cat" name="search_cat" type="text" placeholder="Name of category" class="form-control search-query" autocomplete="off">
    <!-- Show Results -->
    <h4 id="results-text">Showing results for: <b id="catsearch-string">Category</b></h4>
    <ul id="results"></ul>

  </div>
</div>
</form>
<?php } ?>

<form class="form-horizontal" name="logupload" id="logupload" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=newlog" enctype="multipart/form-data">
<fieldset>

<!-- Form Name -->
<legend>New log</legend>

<!-- Text input-->
<div class="form-group">
  <label class="control-label col-sm-3" for="modelName">Logname</label>
  <div class="col-sm-6">
    <input id="modelName" name="modelName" type="text" placeholder="" class="form-control" required="">
    
  </div>
</div>

<!-- hidden field for setting category id -->
<input type="hidden" name="catid" value="<?php echo $cid; ?>">

<!-- Search input-->
<div class="form-group">
  <label class="control-label col-sm-3" for="category">Category</label>
  <div class="col-sm-6">
  <input id="category" name="category" type="text" placeholder="<?php echo $cname; ?>" value="<?php echo $cname; ?>" class="form-control search-query" disabled>
  <input type="hidden" name="catname" value="<?php echo $cname; ?>">
  </div>
</div>

<!-- File Button --> 
<div class="form-group">
  <label class="control-label col-sm-3" for="file_xes">Choose XES file</label>
  <div class="col-sm-6">
    <input id="file_xes" name="file_xes" class="input-file" type="file" accept=".xes">
  </div>
</div>

<!-- File Button --> 
<div class="form-group">
  <label class="control-label col-sm-3" for="file_mxml">Choose MXML file</label>
  <div class="col-sm-6">
    <input id="file_mxml" name="file_mxml" class="input-file" type="file" accept=".mxml">
  </div>
</div>

<!-- File Button --> 
<div class="form-group">
  <label class="control-label col-sm-3" for="file_csv">Choose CSV file</label>
  <div class="col-sm-6">
    <input id="file_csv" name="file_csv" class="input-file" type="file" accept=".csv">
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
<input type="hidden" name="date" value="<?php echo date("YmdHis"); ?>">

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

        echo "<pre>" .print_r( $_POST, true ). "</pre>";
        echo "<pre>" .print_r( $_FILES, true ). "</pre>";

        uploadfiles($_FILES, $_POST);
    }
}
?>

