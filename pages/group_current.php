<?php
require 'includes/authcheck.inc.php'; 
?>

<?php if(!isset($_GET['action']) && !isset($_SESSION['groupID'])) : ?>
<div class="alert alert-warning">
    Illegal function call - please create or edit a group first!
<?php 
    unset($_SESSION['updateflag']);
    unset($_SESSION['grpmodels']);
    unset($_SESSION['grplogs']);
    unset($_SESSION['groupID']);
    unset($_SESSION['grpoldmodels']);
    unset($_SESSION['grpoldlogs']);
?>
</div>
<?php endif; ?>

<?php if((!isset($_GET['action']) || !$_GET['action']) && isset($_SESSION['groupID'])) : ?>
<?php echo "<pre>".print_r($_SESSION,TRUE)."</pre>"; ?>

    <h4>Temporary set of models and logs:</h4>

<div class="panel panel-success">
  <div class="panel-heading">
  <h3 class="panel-title">Models</h3>
  </div>
    <!-- List group -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Modelname</th>
                <th class="text-center">Creator</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach($_SESSION['grpmodels'] as $key => $id)
                {
                    getnamesfromgroup("model", $key, $id); 
                }
            ?>
        </tbody>
    </table>
</div>

<div class="panel panel-info">
  <div class="panel-heading">
  <h3 class="panel-title">Logs</h3>
  </div>
    <!-- List group -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Logname</th>
                <th class="text-center">Creator</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach($_SESSION['grplogs'] as $key => $id)
                {
                    getnamesfromgroup("log", $key, $id); 
                }
            ?>
        </tbody>
    </table>
</div>

        <button type="button" class="btn btn-primary pull-right" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=groupcurrent&action=save"; ?>'">
           <span class="glyphicon glyphicon-circle-arrow-down"></span> Save group!
        </button>

<?php endif; ?>

<?php if(isset($_GET['action']) && $_GET['action'] == "load" && isset($_SESSION['groupID']) && !isset($_SESSION['updateflag'])) : ?>
<div class="alert alert-warning">
    Illegal function call - please create or edit a group first!
<?php 
    unset($_SESSION['updateflag']);
    unset($_SESSION['grpmodels']);
    unset($_SESSION['grplogs']);
    unset($_SESSION['groupID']);
    unset($_SESSION['grpoldmodels']);
    unset($_SESSION['grpoldlogs']);
?>
</div>
<?php endif; ?>

<?php if(isset($_GET['action']) && $_GET['action'] == "load" && isset($_SESSION['groupID']) && isset($_SESSION['updateflag'])) : ?>
<?php header("Location: ".$_SERVER['PHP_SELF']."?show=groupcurrent"); ?>
<?php endif; ?>

<?php if(isset($_GET['action']) && $_GET['action'] == "load" && !isset($_SESSION['groupID']) && isset($_SESSION['angemeldet'])) : ?>
    <?php initgroup($_GET['groupID']); 
          $_SESSION['groupName'] = getgroupname($_GET['groupID']);
    ?>
    <?php header("Location: ".$_SERVER['PHP_SELF']."?show=groupcurrent"); ?>
<?php endif; ?>


<?php if(isset($_GET['action']) && $_GET['action'] == "save" && isset($_SESSION['groupID'])) : ?>
<?php 
    savegroup(); 
?>
Changes saved!
<?php endif; ?>
