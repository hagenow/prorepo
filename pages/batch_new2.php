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
if(!isset($_POST['submit_batch2']) || !$_POST['submit_batch2'])
{
    if (isset( $_POST['submit_batch'] ))
    {
        $targetdir = TMP.uniqid();

        $_SESSION['targetdir'] = $targetdir;

        if(extractZip($_FILES['files']['tmp_name'][0],$targetdir))
        {
            $result = array();
            $result = find_all_files($targetdir);
            
            batchimport_step1($result,$targetdir);
            batchimport_step2();

            echo "<pre>";
            print_r($_SESSION);
            echo "</pre>";
        }
    }
?>
    
    <form class="form-horizontal" name="batchupload2" id="batchupload2" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?show=batch2" enctype="multipart/form-data">
    <fieldset>
    
    <!-- Form Name -->
    <legend>Confirm upload of models & logs</legend>
    
    <!-- hidden field for setting category id -->
    <input type="hidden" name="catid" value="<?php if(isset($_SESSION['cid'])) echo $_SESSION['cid']; ?>">
    <input type="hidden" name="catname" value="<?php if(isset($_SESSION['cname'])) echo $_SESSION['cname']; ?>">
    
    <!-- hidden field for marking up as model -->
    <input type="hidden" name="timestamp" value="<?php echo date("YmdHis"); ?>">
    
    <!-- Action submit or reset -->
    <div class="form-group">
      <label class="control-label col-sm-3"></label>
        <div class="col-sm-6">
            <!-- Indicates a successful or positive action -->
            <button type="submit" class="btn btn-success" id="submit_batch2" name="submit_batch2" value="Submit">Submit</button>
            <!-- Indicates a unsuccesful or negative action -->
            <button type="reset" class="btn btn-danger" id="reset_batch2" name="reset_batch2" value="Reset">Reset</button>
        </div>
    </div>
    
    </fieldset>
    </form> 
<?php  
}
else
{
    if (isset( $_POST['submit_batch2']) && !empty($_POST['submit_batch2']))
    {
        batchimport_step3();

        $targetdir = $_SESSION['targetdir'];

        echo "bla";
    
        // delete files after processing
        rrmdir($targetdir);

        unset($_SESSION['files']);
        unset($_SESSION['targetdir']);

    }
    unset($_SESSION['cid']);
    unset($_SESSION['cname']);
    unset($_SESSION['batch_semaphore']);

    echo "---";

    if(DEBUG)
    {
        echo "<pre>" .print_r( $_SESSION, true ). "</pre>";
        echo "<pre>" .print_r( $_POST, true ). "</pre>";
        echo "<pre>" .print_r( $_FILES, true ). "</pre>";
    }
}
?>
