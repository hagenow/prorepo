<?php
require_once 'config.inc.php';
require_once 'functions.inc.php';

$modvalues = array(); 
$modvalues = viewmodel($_GET['modelID']);
?>

<div class="panel panel-primary">
  <div class="panel-heading">
  <h3 class="panel-title"><?php echo $modvalues['modelName']. " @ " .$modvalues['timestamp']. " by: ".$modvalues['creator']; ?>
    <div class="btn-group pull-right">
    <button type="button" class="btn btn-default btn-xs" onclick="location.href='<?php echo $_SERVER['PHP_SELF']; ?>?show=modupload';">
       <span class="glyphicon glyphicon-circle-arrow-up"></span> Upload new files
    </button>
    <button type="button" class="btn btn-default btn-xs" onclick="location.href='<?php echo $_SERVER['PHP_SELF']; ?>?show=modedit';">
       <span class="glyphicon glyphicon-wrench"></span> Edit model
    </button>
    </div>
  </h3>
  </div>
  <div class="panel-body">
    <?php echo $modvalues['comment']; ?>
  </div>
</div>

<div class="panel panel-primary">
  <div class="panel-heading">
  <h3 class="panel-title">View versions</h3>
  </div>
  <div class="panel-body">
    <?php getversions('model',$_GET['modelID']); ?>
  </div>
</div>
