<?php
require_once 'config.inc.php';
require_once 'functions.inc.php';

$logvalues = array(); 
$logvalues = viewlog($_GET['logID']);

// if not set via menu
if(isset($_GET['timestamp']))
{
    $date = $_GET['timestamp'];
    $date = date("Y-m-d H:i:s", strtotime($date));
}
elseif(isset($_POST['timestamp']))
{
    $date = $_POST['timestamp'];
    $date = date("Y-m-d H:i:s", strtotime($date));
}
else
{
    $date = date("Y-m-d H:i:s");
}
?>

<div class="panel panel-info">
  <div class="panel-heading">
    <?php if(isset($_SESSION['angemeldet']) || $_SESSION['angemeldet']) : ?>
        <div class="btn-group pull-right">
        <button type="button" class="btn btn-default btn-sm" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=logupload&logID=".$_GET['logID']; ?>'">
           <span class="glyphicon glyphicon-circle-arrow-up"></span> Upload new files
        </button>
        <button type="button" class="btn btn-default btn-sm" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=logedit&logID=".$_GET['logID']; ?>'">
           <span class="glyphicon glyphicon-wrench"></span> Edit log
        </button>
    <?php if(isset($_SESSION['groupID'])) : ?>
        <button type="button" class="btn btn-default btn-sm" id="addlog2group" value="<?php echo $_GET['logID']."|".$date; ?>">
           <span class="glyphicon glyphicon-plus"></span> Add to group
        </button>
    <?php endif; ?>
        </div>
    <?php endif; ?>
  <h3 class="panel-title"><h3><?php echo $logvalues['name']; ?></h3></div>
  <div class="panel-body">
    <h4>Comment:</h4>
    <blockquote>
    <p><?php echo $logvalues['comment']; ?></p>
    </blockquote>
    <small>Added: <?php echo $logvalues['timestamp']. " (".$logvalues['creator'].")" ?></small>
  </div>
</div>

<div class="panel panel-success">
  <div class="panel-heading">
  <h3 class="panel-title">View versions</h3>
  </div>
  <div class="panel-body row">
    <div class="col-xs-5">
        Show files with selected timestamp and below.
    </div>
    <div class="col-xs-5">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']."?show=logview&logID=".$_GET['logID']; ?>" name="timestampchooser" id="timestampchooser">
            <select name="timestamp" onchange="this.form.submit()" class="form-control">
                <option></option>
                <?php getversions('log',$_GET['logID']); ?>
            </select>
        </form>
    </div>
  </div>
</div>

<div class="panel panel-info">
  <div class="panel-heading">
  <h3 class="panel-title">MXML</h3>
  </div>
    <!-- List group -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Filename</th>
                <th class="text-center">Validation</th>
                <th class="text-center">Size</th>
                <th class="text-center">Upload date</th>
                <th class="text-center">Uploader</th>
            </tr>
        </thead>
        <tbody>
            <?php viewfiles("log", $_GET['logID'], "mxml", $date); ?>
        </tbody>
    </table>
</div>

<div class="panel panel-info">
  <div class="panel-heading">
  <h3 class="panel-title">XES</h3>
  </div>
    <!-- List group -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Filename</th>
                <th class="text-center">Validation</th>
                <th class="text-center">Size</th>
                <th class="text-center">Upload date</th>
                <th class="text-center">Uploader</th>
            </tr>
        </thead>
        <tbody>
            <?php viewfiles("log", $_GET['logID'], "xes", $date); ?>
        </tbody>
    </table>
</div>

<div class="panel panel-info">
  <div class="panel-heading">
  <h3 class="panel-title">CSV</h3>
  </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Filename</th>
                <th></th>
                <th class="text-center">Size</th>
                <th class="text-center">Upload date</th>
                <th class="text-center">Uploader</th>
            </tr>
        </thead>
        <tbody>
            <?php viewfiles("log", $_GET['logID'], "csv", $date); ?>
        </tbody>
    </table>
</div>
