<?php
require 'includes/authcheck.inc.php';

if((!isset($_POST['cid']) || !$_POST['cid'] ) && ( !isset($_POST['cname']) || !$_POST['cname']) ){ 
    $cid = "";
    $cname = "";
}
else
{
    $cid = $_POST['cid'];
    $cname = $_POST['cname'];
}

/** Wurde das Formular abgeschickt? */
if(!isset($_POST['submit_model']) || !$_POST['submit_model']) { 
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
    
    <form class="form-horizontal" name="modelupload" id="modelupload" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=newmod" enctype="multipart/form-data">
    <fieldset>
    
    <!-- Form Name -->
    <legend>New model</legend>
    
    <!-- Text input-->
    <div class="form-group">
      <label class="control-label col-sm-3" for="modelName">Modelname</label>
      <div class="col-sm-6">
        <input id="modelName" name="modelName" type="text" placeholder="" class="form-control" required="">
        
      </div>
    </div>
    
    <!-- hidden field for setting category id -->
    <input type="hidden" name="catid" value="<?php echo $cid ?>">
    
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
      <label class="control-label col-sm-3" for="file_png">Choose PNG file</label>
      <div class="col-sm-6">
        <input id="file_png" name="file_png" class="input-file" type="file" accept="image/png">
      </div>
    </div>
    
    <!-- File Button --> 
    <div class="form-group">
      <label class="control-label col-sm-3" for="file_pdf">Choose PDF file</label>
      <div class="col-sm-6">
        <input id="file_pdf" name="file_pdf" class="input-file" type="file" accept="application/pdf">
      </div>
    </div>
    
    <!-- File Button --> 
    <div class="form-group">
      <label class="control-label col-sm-3" for="file_pnml">Choose PNML file</label>
      <div class="col-sm-6">
        <input id="file_pnml" name="file_pnml" class="input-file" type="file" accept=".pnml">
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
    
    <!-- File Button --> 
    <div class="form-group">
      <label class="control-label col-sm-3" for="file_xml">Choose XML file</label>
      <div class="col-sm-6">
        <input id="file_xml" name="file_xml" class="input-file" type="file" accept="application/xml">
      </div>
    </div>
    
    <!-- Textarea -->
    <div class="form-group">
      <label class="control-label col-sm-3" for="comment">Comment</label>
      <div class="col-sm-6">                     
        <textarea id="comment" name="comment" class="form-control" rows="4" placeholder="Write something about this model!"></textarea>
      </div>
    </div>
    
    <!-- hidden field for marking up as model -->
    <input type="hidden" name="type" value="model">
    
    <!-- hidden field for marking up as model -->
    <input type="hidden" name="date" value="<?php echo date("YmdHis"); ?>">
    
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
        
        echo "<pre>" .print_r( $_POST, true ). "</pre>";
        echo "<pre>" .print_r( $_FILES, true ). "</pre>";
        
        uploadfiles($_FILES, $_POST);
    }
} 
?>
