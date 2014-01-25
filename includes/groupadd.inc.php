<?php 

require 'authcheck.inc.php'; 
require_once '../config.inc.php';
require_once '../functions.inc.php';

if(isset($_GET['modelID']))
    addmodel2group();

if(isset($_GET['logID']))
    addlog2group();
?>
