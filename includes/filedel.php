<?php

// require 'authcheck.inc.php'; 
require_once '../config.inc.php';
require_once '../functions.inc.php';

// $uniqid = cleaninput($_GET['uniqid']);

$values = viewfile("f5300b04002fc49.12213454");

$fullpath = $values['path'].$values['fileName'];

print_r($values);

if(deletefile("f5300b04002fc49.12213454", $fullpath))
    echo "gelöscht!";
else
    echo "nicht gelöscht!";


// if(isset $_GET['uniqid'] && ($values['uploader'] == $_SESSION['user'] || isadmin()) && $values['deletable'] == "1")
//    return deletefile($uniqid);
?>
