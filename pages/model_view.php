<?php
require_once 'config.inc.php';
require_once 'functions.inc.php';

$modvalues = array(); 
$modvalues = viewmodel($_GET['modelID']);
?>

<div class="panel panel-success">
  <div class="panel-heading">
    <?php if(isset($_SESSION['angemeldet']) || $_SESSION['angemeldet']) : ?>
        <div class="btn-group pull-right">
        <button type="button" class="btn btn-default btn-sm" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=modupload&modelID=".$_GET['modelID']; ?>'">
           <span class="glyphicon glyphicon-circle-arrow-up"></span> Upload new files
        </button>
        <button type="button" class="btn btn-default btn-sm" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=modedit&modelID=".$_GET['modelID']; ?>'">
           <span class="glyphicon glyphicon-wrench"></span> Edit model
        </button>
        <button type="button" class="btn btn-default btn-sm" id="addmodel2group" value="<?php echo $_GET['modelID']; ?>">
           <span class="glyphicon glyphicon-plus"></span> Add to group
        </button>
        </div>
    <?php endif; ?>
  <h3 class="panel-title"><h3><?php echo $modvalues['name']. "</h3> Added: " .$modvalues['timestamp']. " ( by ".$modvalues['creator']." )" ?></h3>
  </h3>
  </div>
  <div class="panel-body">
    Comment: <br>
    <?php echo $modvalues['comment']; ?>
  </div>
</div>

<div class="panel panel-success">
  <div class="panel-heading">
  <h3 class="panel-title">View versions</h3>
  </div>
  <div class="panel-body">
    <?php getversions('model',$_GET['modelID']); ?>
  </div>
</div>

<div class="panel panel-success">
  <div class="panel-heading">
  <h3 class="panel-title">PNML & XML files</h3>
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
            <?php viewfiles("model", $_GET['modelID'], "pnml", $date); ?>
            <?php viewfiles("model", $_GET['modelID'], "xml", $date); ?>
        </tbody>
    </table>
</div>

<div class="panel panel-success">
  <div class="panel-heading">
  <h3 class="panel-title">PDF</h3>
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
            <?php viewfiles("model", $_GET['modelID'], "pdf", $date); ?>
        </tbody>
    </table>
</div>
<div class="panel panel-success">
  <div class="panel-heading">
  <h3 class="panel-title">PNG</h3>
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
            <?php viewfiles("model", $_GET['modelID'], "png", $date); ?>
        </tbody>
    </table>
</div>
<div class="panel panel-success">
  <div class="panel-heading">
  <h3 class="panel-title">JPG</h3>
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
            <?php viewfiles("model", $_GET['modelID'], "jpg", $date); ?>
        </tbody>
    </table>
</div>
<div class="panel panel-success">
  <div class="panel-heading">
  <h3 class="panel-title">SVG</h3>
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
            <?php viewfiles("model", $_GET['modelID'], "svg", $date); ?>
        </tbody>
    </table>
</div>
<div class="panel panel-success">
  <div class="panel-heading">
  <h3 class="panel-title">EPS</h3>
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
            <?php viewfiles("model", $_GET['modelID'], "eps", $date); ?>
        </tbody>
    </table>
</div>
<hr>
<div class="panel panel-info">
  <div class="panel-heading">
  <h3 class="panel-title">Connected logs</h3>
  </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Logname</th>
            </tr>
        </thead>
        <tbody>
            <?php connectedlogs($_GET['modelID']); ?>
        </tbody>
    </table>
</div>
