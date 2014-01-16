<?php
require_once 'config.inc.php';
require_once 'functions.inc.php';

$conid = db_connect();
?>

<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Category List</div>
    <!--<div class="panel-body">
      <p>These are the categories...<p>
    </di<>-->

    <!-- List group -->
    <div class="list-group">
<?php getcategories($conid); ?>
    </div>
</div>

