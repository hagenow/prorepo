<?php 

require 'authcheck.inc.php'; 
require_once '../config.inc.php';
require_once '../functions.inc.php';

if(isset($_POST['modelID']))
    addmodel2group();

if(isset($_POST['logID']))
    addlog2group();
?>
