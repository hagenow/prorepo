<?php
require_once 'config.inc.php';
require_once 'functions.inc.php';

$modvalues = array(); 
$modvalues = viewmodel($_GET['modelID']);
?>

<div class="panel panel-default">
  <div class="panel-heading">
  <h3 class="panel-title"><?php echo $modvalues['modelName']. " @ " .$modvalues['timestamp']. " by: ".$modvalues['creator']; ?></h3>
  </div>
  <div class="panel-body">
    <?php echo $modvalues['comment']; ?>
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">
  <h3 class="panel-title">View files</h3>
  </div>
  <div class="panel-body">
    <?php viewfiles('mod',$_GET['modelID']); ?>
  </div>
</div>
