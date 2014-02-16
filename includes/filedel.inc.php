<?php

require 'authcheck.inc.php'; 
require_once '../config.inc.php';
require_once '../functions.inc.php';

$uniqid = cleaninput($_POST['uniqid']);

$values = viewfile($uniqid);

$fullpath = "../".$values['path'].$values['fileName'];

if(isset($_POST['uniqid']) && ($values['uploader'] == $_SESSION['user'] || isadmin()) && $values['deletable'] == "1")
   deletefile($uniqid,$fullpath);
?>
