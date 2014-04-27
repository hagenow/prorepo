<?php
require_once 'config.inc.php';
require_once 'functions.inc.php';

$modvalues = array(); 
$modelid = cleaninput($_GET['modelID']);
$modvalues = viewmodel($modelid);

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
    $date = getlatestversions('model',$modelid);
}


?>

<div class="panel panel-success">
  <div class="panel-heading">
    <?php if(isset($_SESSION['angemeldet'])) : ?>
        <div class="btn-group pull-right">
    <?php if(isset($_SESSION['angemeldet']) && isset($_SESSION['user']) && $_SESSION['user'] == $modvalues['creator'] || isadmin()) : ?>
        <button type="button" class="btn btn-default btn-sm" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=modupload&modelID=".$modelid; ?>'">
           <span class="glyphicon glyphicon-circle-arrow-up"></span> Upload new files
        </button>
        <button type="button" class="btn btn-default btn-sm" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=modedit&modelID=".$modelid; ?>'">
           <span class="glyphicon glyphicon-wrench"></span> Edit model
        </button>
    <?php endif; ?>
    <?php if(isset($_SESSION['groupID']) && isset($_SESSION['updateflag']) && isset($_SESSION['grpoldmodels']) && in_array($modelid, $_SESSION['grpoldmodels'])) : ?>
        <button type="button" class="btn btn-default btn-sm" id="addmodel2group_donothing" value="<?php echo $modelid."|".$date; ?>">
           <span class="glyphicon glyphicon-flash"></span> Already added, please remove first!
        </button>
    <?php elseif(isset($_SESSION['groupID'])) : ?>
        <button type="button" class="btn btn-default btn-sm" id="addmodel2group" value="<?php echo $modelid."|".$date; ?>">
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
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']."?show=modview&modelID=".$modelid; ?>" name="timestampchooser" id="timestampchooser">
            <select name="timestamp" onchange="this.form.submit()" class="form-control">
	    <?php
		if(isset($_POST['timestamp']))
		{
			echo "<option>".$_POST['timestamp']."</option>";
			echo "<optgroup disabled=\"disabled\">";
			echo "<option>-------------------</option>";
			echo "</optgroup>";
		}
	    ?>
                <?php getversions('model',$modelid); ?>
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
                <th class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php viewfiles("model", $modelid, "pnml", $date); ?>
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
                <th class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php viewfiles("model", $modelid, "tpn", $date); ?>
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
                <th class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php viewfiles("model", $modelid, "xml", $date); ?>
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
                <th class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php viewfiles("model", $modelid, "pdf", $date); ?>
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
                <th class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php viewfiles("model", $modelid, "png", $date); ?>
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
                <th class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php viewfiles("model", $modelid, "jpg", $date); ?>
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
                <th class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php viewfiles("model", $modelid, "svg", $date); ?>
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
                <th class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php viewfiles("model", $modelid, "eps", $date); ?>
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
            <?php connectedlogs($modelid); ?>
        </tbody>
    </table>
</div>
