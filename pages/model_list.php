<?php
require_once 'config.inc.php';
require_once 'functions.inc.php';

$catid = cleaninput($_GET['catID']);
?>

<div class="panel panel-success">
    <!-- Default panel contents -->
    <div class="panel-heading"><h4>Modellist</h4></div>
    <!--<div class="panel-body">
      <p>These are the models...<p>
    </di<>-->

    <!-- List group -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Modelname</th>
                <th class="text-center">Creation date</th>
                <th class="text-center">Creator</th>
<?php if(isadmin()) : ?>
                <th class="text-center">Delete</th>
<?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php getmodels($catid); ?>
        </tbody>
    </table>
</div>

