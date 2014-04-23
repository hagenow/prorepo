<?php
require_once 'config.inc.php';
require_once 'functions.inc.php';

$groupvalues = array(); 
$groupvalues = viewgroup($_GET['groupID']);

// if not set via menu
if(!isset($_POST['timestamp']) || !$_POST['timestamp'])
{
    $date = date("Y-m-d H:i:s");
}
else
{
    $date = $_POST['timestamp'];
}

$state = $groupvalues['state'];
$state = $state^1;

?>

<div class="panel panel-warning">
  <div class="panel-heading">
    <?php if(isset($_SESSION['angemeldet']) && isset($_SESSION['user']) && $_SESSION['user'] == $groupvalues['creator'] && $groupvalues['state'] == "1" || isadmin()) : ?>
        <?php
        ?>
        <div class="btn-group pull-right">
        <button type="button" class="btn btn-default btn-sm" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=groupedit&groupID=".$_GET['groupID']; ?>'">
           <span class="glyphicon glyphicon-wrench"></span> Edit group
        </button>
        <button type="button" class="btn btn-default btn-sm" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=groupcurrent&groupID=".$_GET['groupID']."&action=load"; ?>'">
           <span class="glyphicon glyphicon-plus"></span> Add new models or logs
        </button>
        <button type="button" class="btn btn-default btn-sm" id="switchgrpstate" value="<?php echo $groupvalues['id']."|".$state; ?>">
           <span class="glyphicon glyphicon-off"></span> Switch state
        </button>
        </div>
    <?php else : ?>
      <?php if(isset($_SESSION['angemeldet'])) :?>
        <div class="btn-group pull-right">
        <button type="button" class="btn btn-default btn-sm" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=admincontact&groupID=".$_GET['groupID']; ?>'">
           <span class="glyphicon glyphicon-wrench"></span> Closed group, contact the admin for re-opening!
        </button>
        </div>
    <?php endif; ?>
    <?php endif; ?>
  <h3 class="panel-title"><h3><?php echo $groupvalues['name']; ?></h3></div>
  <div class="panel-body">
    <h4>Tags:</h4>
    <blockquote>
    <p><?php  echo $groupvalues['tags']; ?></p>
    </blockquote>
    <h4>Short-URL:</h4>
    <blockquote>
    <p><a href="http://<?php echo $_SERVER['SERVER_NAME'].PATHINFO."g/".$groupvalues['guid']; ?>">http://<?php echo $_SERVER['SERVER_NAME'].PATHINFO."g/".$groupvalues['guid']; ?></a></p>
    </blockquote>
    <small>Added: <?php echo $groupvalues['timestamp']. " (".$groupvalues['creator'].")" ?></small>
  </div>
</div>

<div class="panel panel-success">
  <div class="panel-heading">
  <h3 class="panel-title">Linked models</h3>
  </div>
    <!-- List group -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Modelname</th>
                <th class="text-center">Date</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php linkedtypes($_GET['groupID'],"model",$groupvalues['creator'],$groupvalues['state']); ?>
        </tbody>
    </table>
</div>

<div class="panel panel-info">
  <div class="panel-heading">
  <h3 class="panel-title">Linked Logs</h3>
  </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Logname</th>
                <th class="text-center">Date</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php linkedtypes($_GET['groupID'],"log",$groupvalues['creator'],$groupvalues['state']); ?>
        </tbody>
    </table>
</div>

        <button type="button" class="btn btn-primary pull-right" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=groupview&groupID=".$_GET['groupID']."&action=save"; ?>'">
           <span class="glyphicon glyphicon-download"></span> Download group as ZIP-File
        </button>

<?php if(isset($_GET['action']) && $_GET['action'] == "save" && !isset($_GET['groupID'])) : ?>
<div class="alert alert-warning">
    Illegal function call!
</div>
<?php endif; ?>

<?php if(isset($_GET['action']) && $_GET['action'] == "save" && isset($_GET['groupID'])) : ?>

Creating ZIP-file and serve download... <br/>
<?php 
    $zip = array();
    $zip = createzip($_GET['groupID']); 

    $z = new ZipArchive();
    $z->open("".TMP."/group_".$_GET['groupID'].".zip", ZIPARCHIVE::CREATE);
    foreach($zip as $folder)
    {
        folderToZip($folder, $z);
    }
    $z->close();

    getzip("group_".$_GET['groupID'].".zip", "".TMP."");
?>

<?php endif; ?>
