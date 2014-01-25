<?php
require_once 'config.inc.php';
require_once 'functions.inc.php';

$logvalues = array(); 
$logvalues = viewlog($_GET['logID']);
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
        <button type="button" class="btn btn-default btn-sm" id="addlog2group" value="<?php echo $_GET['logID']; ?>">
           <span class="glyphicon glyphicon-plus"></span> Add to group
        </button>
        </div>
    <?php endif; ?>
  <h3 class="panel-title"><h3><?php echo $logvalues['name']. "</h3> Added: " .$logvalues['timestamp']. " ( by ".$logvalues['creator']." )" ?></h3>
  </div>
  <div class="panel-body">
    Comment: <br>
    <?php echo $logvalues['comment']; ?>
  </div>
</div>

<div class="panel panel-info">
  <div class="panel-heading">
  <h3 class="panel-title">View versions</h3>
  </div>
  <div class="panel-body">
    <?php getversions('log',$_GET['logID']); ?>
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
