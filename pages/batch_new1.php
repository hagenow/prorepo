<?php require 'includes/authcheck.inc.php'; 
/** wurden Category ID und Name bereits ermittelt? Falls nein, dann setze $cid
 * und $cname auf einen leeren String - ist das obsolet? */ 

if((!isset($_POST['cid']) || !$_POST['cid'] ) && ( !isset($_POST['cname']) || !$_POST['cname'])) {
    $cid = "";
    $cname = "";
}
else {
    /** transfer sumbitted values to _SESSION and set a semaphore for exclusive
     * access to the submitted variables */
    $_SESSION['cid'] = $_POST['cid'];
    $_SESSION['cname'] = $_POST['cname'];
    $_SESSION['batch_semaphore'] = true;
}
if(isset($_SESSION['log_semaphore'])) {
        unset($_SESSION['cid']);
        unset($_SESSION['cname']);
        unset($_SESSION['modelid']);
        unset($_SESSION['modelname']);
        unset($_SESSION['log_semaphore']);
}
if(isset($_SESSION['mod_semaphore'])) {
        unset($_SESSION['cid']);
        unset($_SESSION['cname']);
        unset($_SESSION['modelid']);
        unset($_SESSION['modelname']);
        unset($_SESSION['mod_semaphore']);
}
/** Wurde das Formular abgeschickt? */ 
if(!isset($_POST['submit_batch']) || !$_POST['submit_batch']) { ?>
    <?php if(!isset($_POST['cname']) || !$_POST['cname']) { ?>
        <legend>Choose a category</legend>
        <!-- Search input-->
        <form class="form-horizontal" method="post" name"cat" id="cat" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=batch1">
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
    
    <form class="form-horizontal" name="batchupload" id="batchupload" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=batch1" enctype="multipart/form-data">
    <fieldset>
    
    <!-- Form Name -->
    <legend>Upload file for batch import</legend>
    
    <!-- hidden field for setting category id -->
    <input type="hidden" name="catid" value="<?php if(isset($_SESSION['cid'])) echo $_SESSION['cid']; ?>">
    
    <!-- Search input-->
    <div class="form-group">
      <label class="control-label col-sm-3" for="category">Category</label>
      <div class="col-sm-6">
      <input id="category" name="category" type="text" placeholder="<?php if(isset($_SESSION['cname'])) echo $_SESSION['cname']; ?>" value="<?php if(isset($_SESSION['cname'])) echo 
$_SESSION['cname']; ?>" class="form-control search-query" disabled>
      <input type="hidden" name="catname" value="<?php if(isset($_SESSION['cname'])) echo $_SESSION['cname']; ?>">
      </div>
    </div>
    
    <!-- File Button -->
    <div class="form-group">
      <label class="control-label col-sm-3" for="file_zip">Choose files<br><h6>(.zip)</h6></label>
      <div class="col-sm-6">
        <input id="files" name="files[]" class="input-file" type="file" accept=".zip">
      </div>
    </div>
    
    <!-- hidden field for marking up as model -->
    <input type="hidden" name="timestamp" value="<?php echo date("YmdHis"); ?>">
    
    <!-- Action submit or reset -->
    <div class="form-group">
      <label class="control-label col-sm-3"></label>
        <div class="col-sm-6">
            <!-- Indicates a successful or positive action -->
            <button type="submit" class="btn btn-success" id="submit_batch" name="submit_batch" value="Submit">Submit</button>
            <!-- Indicates a unsuccesful or negative action -->
            <button type="reset" class="btn btn-danger" id="reset_batch" name="reset_batch" value="Reset">Reset</button>
        </div>
    </div>
    
    </fieldset>
    </form> <?php } else {
    if (isset( $_POST['submit_batch'] ))
    {
        unset($_SESSION['cid']);
        unset($_SESSION['cname']);
        unset($_SESSION['batch_semaphore']);
        
        $targetdir = TMP.uniqid();

        if(extractZip($_FILES['files']['tmp_name'][0],$targetdir))
        {
            $result = array();
            $result = find_all_files($targetdir);
            
            echo "<pre>" .print_r( $result, true ). "</pre>";
            batchimport_step1($result,$targetdir);
            
        }

        echo "---";

        if(DEBUG)
        {
            echo "<pre>" .print_r( $_SESSION, true ). "</pre>";
            echo "<pre>" .print_r( $_POST, true ). "</pre>";
            echo "<pre>" .print_r( $_FILES, true ). "</pre>";
        }
        
    }
} 
?>
