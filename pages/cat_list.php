<?php
require_once 'config.inc.php';
require_once 'functions.inc.php';

?>

<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">
      <?php if(isadmin()) :?>
        <button type="button" class="btn btn-default btn-sm pull-right" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=catnew"; ?>'">New category</button>

        <button type="button" class="btn btn-default btn-sm pull-right" onclick="location.href='<?php echo $_SERVER['PHP_SELF']."?show=catedit"; ?>'">Edit categories</button> 
      <?php endif; ?>
        <h3 class="panel-title"><h3>Category list</h3></h3>
    </div>
    <!--<div class="panel-body">
      <p>These are the categories...<p>
    </di<>-->

    <!-- List group -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name of Category</th>
                <th class="text-center">Models</th>
                <th class="text-center">Logs</th>
            </tr>
        </thead>
        <tbody>
            <?php getcategories(); ?>
        </tbody>
    </table>
</div>

