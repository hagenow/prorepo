<?php

require 'authcheck.inc.php'; 
require_once '../config.inc.php';
require_once '../functions.inc.php';

$logid = cleaninput($_POST['logID']);

$values = viewlog($logid);

if(isset($_POST['logID']) && ($values['creator'] == $_SESSION['user'] || isadmin()) && $values['deletable'] == "1")
   removelog($logid,$values['path']);
?>
