<?php
require_once 'config.inc.php';
require_once 'functions.inc.php';

?>

<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading"><h4>Category list</h4></div>
    <!--<div class="panel-body">
      <p>These are the categories...<p>
    </di<>-->

    <!-- List group -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name of Category</th>
                <th>Models</th>
                <th>Logs</th>
            </tr>
        </thead>
        <tbody>
            <?php getcategories(); ?>
        </tbody>
    </table>
</div>

