<?php
require_once 'config.inc.php';
require_once 'functions.inc.php';

$logvalues = array(); 
$logid = cleaninput($_GET['logID']);
$logvalues = viewlog($logid);

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
    $date = getlatestversions('log',$logid);
}
?>

<div class="panel panel-info">
  <div class="panel-heading">
    <?php if(isset($_SESSION['angemeldet'])) : ?>
        <div class="btn-group pull-right">
    <?php if(isset($_SESSION['angemeldet']) && isset($_SESSION['user']) && $_SESSION['user'] == $logvalues['creator'] || isadmin()) : ?>
        <button type="button" class="btn btn-default btn-sm" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=logupload&logID=".$logid; ?>'">
           <span class="glyphicon glyphicon-circle-arrow-up"></span> Upload new files
        </button>
        <button type="button" class="btn btn-default btn-sm" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=logedit&logID=".$logid; ?>'">
           <span class="glyphicon glyphicon-wrench"></span> Edit log
        </button>
    <?php endif; ?>
    <?php if(isset($_SESSION['groupID']) && isset($_SESSION['updateflag']) && in_array($logid, $_SESSION['grpoldlogs'])) : ?>
        <button type="button" class="btn btn-default btn-sm" id="addlog2group_donothing" value="<?php echo $logid."|".$date; ?>">
           <span class="glyphicon glyphicon-flash"></span> Already added, please remove first!
        </button>
    <?php elseif(isset($_SESSION['groupID'])) : ?>
        <button type="button" class="btn btn-default btn-sm" id="addlog2group" value="<?php echo $logid."|".$date; ?>">
           <span class="glyphicon glyphicon-plus"></span> Add to group
        </button>
    <?php endif; ?>
        </div>
    <?php endif; ?>
  <h3 class="panel-title"><h3><?php echo $logvalues['name']; ?></h3></div>
  <div class="panel-body">
    <p cass="lead">Comment:</p>
    <p><?php echo $logvalues['comment']; ?></p>
    <small>Added: <?php echo $logvalues['timestamp']. " (".$logvalues['creator'].")" ?></small>
  </div>
</div>

<div class="panel panel-info">
  <div class="panel-heading">
  <h3 class="panel-title">View versions</h3>
  </div>
  <div class="panel-body row">
    <div class="col-xs-5">
        Show files with selected timestamp and below.
    </div>
    <div class="col-xs-5">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']."?show=logview&logID=".$logid; ?>" name="timestampchooser" id="timestampchooser">
            <select name="timestamp" onchange="this.form.submit()" class="form-control">
                <option></option>
                <?php getversions('log',$logid); ?>
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
                <th class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php viewfiles("log", $logid, "mxml", $date); ?>
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
                <th class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php viewfiles("log", $logid, "xes", $date); ?>
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
            <th class="text-center">Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php viewfiles("log", $logid, "csv", $date); ?>
    </tbody>
</table>
</div>

<div class="panel panel-info">
<div class="panel-heading">
<h3 class="panel-title">TXT</h3>
</div>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Filename</th>
            <th></th>
            <th class="text-center">Size</th>
            <th class="text-center">Upload date</th>
            <th class="text-center">Uploader</th>
            <th class="text-center">Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php viewfiles("log", $logid, "txt", $date); ?>
    </tbody>
</table>
</div>
