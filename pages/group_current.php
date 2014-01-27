<?php
echo "<pre>".print_r($_SESSION, true)."</pre>";
?>
<?php if(!isset($_GET['action']) || !$_GET['action']) : ?>

    <h4>In this Group included:</h4>

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
                <th>Modelname</th>
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
           <span class="glyphicon glyphicon-circle-arrow-down"></span> Save group and create permanent link
        </button>

<?php endif; ?>

<?php if(isset($_GET['action']) && $_GET['action'] == "save" && !isset($_SESSION['groupID'])) : ?>
<div class="alert alert-warning">
    Illegal function call - please create a group first!
</div>
<?php endif; ?>

<?php if(isset($_GET['action']) && $_GET['action'] == "save" && isset($_SESSION['groupID'])) : ?>

The group will be saved as:<br />
<?php savegroup(); ?>

<?php endif; ?>
