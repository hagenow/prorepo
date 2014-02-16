<?php

require 'authcheck.inc.php'; 
require_once '../config.inc.php';
require_once '../functions.inc.php';

$modelid = cleaninput($_POST['modelID']);

$values = viewmodel($modelid);

if(isset($_POST['modelID']) && ($values['creator'] == $_SESSION['user'] || isadmin()) && $values['deletable'] == "1")
   removemodel($modelid);
?>
