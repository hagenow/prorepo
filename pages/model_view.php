<?php
require_once 'config.inc.php';
require_once 'functions.inc.php';

$modvalues = array(); 
$modvalues = viewmodel($_GET['modelID']);

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

<div class="panel panel-success">
  <div class="panel-heading">
    <?php if(isset($_SESSION['angemeldet'])) : ?>
        <div class="btn-group pull-right">
    <?php if(isset($_SESSION['angemeldet']) && isset($_SESSION['user']) && $_SESSION['user'] == $modvalues['creator'] || isadmin()) : ?>
        <button type="button" class="btn btn-default btn-sm" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=modupload&modelID=".$_GET['modelID']; ?>'">
           <span class="glyphicon glyphicon-circle-arrow-up"></span> Upload new files
        </button>
        <button type="button" class="btn btn-default btn-sm" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=modedit&modelID=".$_GET['modelID']; ?>'">
           <span class="glyphicon glyphicon-wrench"></span> Edit model
        </button>
    <?php endif; ?>
    <?php if(isset($_SESSION['groupID']) && isset($_SESSION['updateflag']) && in_array($_GET['modelID'], $_SESSION['grpoldmodels'])) : ?>
        <button type="button" class="btn btn-default btn-sm" id="addmodel2group_donothing" value="<?php echo $_GET['modelID']."|".$date; ?>">
           <span class="glyphicon glyphicon-flash"></span> Already added, please remove first!
        </button>
    <?php elseif(isset($_SESSION['groupID'])) : ?>
        <button type="button" class="btn btn-default btn-sm" id="addmodel2group" value="<?php echo $_GET['modelID']."|".$date; ?>">
           <span class="glyphicon glyphicon-plus"></span> Add to group
        </button>
    <?php endif; ?>
        </div>
    <?php endif; ?>
  <h3 class="panel-title"><h3><?php echo $modvalues['name']; ?></h3></div>
  <div class="panel-body">
    <h4>Comment:</h4>
    <blockquote>
    <p><?php echo $modvalues['comment']; ?></p>
    </blockquote>
    <small>Added: <?php echo $modvalues['timestamp']. " (".$modvalues['creator'].")" ?></small>
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
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']."?show=modview&modelID=".$_GET['modelID']; ?>" name="timestampchooser" id="timestampchooser">
            <select name="timestamp" onchange="this.form.submit()" class="form-control">
                <option></option>
                <?php getversions('model',$_GET['modelID']); ?>
            </select>
        </form>
    </div>
  </div>
</div>

<div class="panel panel-success">
  <div class="panel-heading">
  <h3 class="panel-title">PNML</h3>
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
        </tbody>
    </table>
</div>

<div class="panel panel-success">
  <div class="panel-heading">
  <h3 class="panel-title">TPN</h3>
  </div>
    <!-- List group -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Filename</th>
                <th class="text-center">Size</th>
                <th class="text-center">Upload date</th>
                <th class="text-center">Uploader</th>
            </tr>
        </thead>
        <tbody>
            <?php viewfiles("model", $_GET['modelID'], "tpn", $date); ?>
        </tbody>
    </table>
</div>

<div class="panel panel-success">
  <div class="panel-heading">
  <h3 class="panel-title">XML</h3>
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
