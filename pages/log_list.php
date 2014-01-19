<?php
require_once 'config.inc.php';
require_once 'functions.inc.php';
?>

<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading"><h4>Loglist</h4></div>
    <!--<div class="panel-body">
      <p>These are the logs...<p>
    </di<>-->

    <!-- List group -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Logname</th>
                <th>Creation date</th>
                <th>Creator</th>
            </tr>
        </thead>
        <tbody>
            <?php getlogs($_GET['catID']); ?>
        </tbody>
    </table>
</div>

