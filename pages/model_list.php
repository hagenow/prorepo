<?php
require_once 'config.inc.php';
require_once 'functions.inc.php';
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
                <th>Creation date</th>
                <th>Creator</th>
            </tr>
        </thead>
        <tbody>
            <?php getmodels($_GET['catID']); ?>
        </tbody>
    </table>
</div>

