<h4>Category List</h4>

<?php
require_once 'config.inc.php';
require_once 'functions.inc.php';

$conid = db_connect();

getcategories($conid);
?>
