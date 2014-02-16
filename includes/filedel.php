<?php

require 'authcheck.inc.php'; 
require_once '../config.inc.php';
require_once '../functions.inc.php';

$uniqid = cleaninput($_GET['uniqid']);

$values = viewfile($uniqid);

$fullpath = $values['path'].$values['fileName'];

if(isset $_GET['uniqid'] && ($values['uploader'] == $_SESSION['user'] || isadmin()) && $values['deletable'] == "1")
   deletefile($uniqid,$fullpath);
?>
