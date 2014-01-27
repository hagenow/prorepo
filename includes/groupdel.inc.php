<?php 

require 'authcheck.inc.php'; 
require_once '../config.inc.php';
require_once '../functions.inc.php';

if(isset($_GET['referrer']))
{
    if(isset($_GET['modelID']))
        deletefromgroup("model",$_GET['modelID']);
    if(isset($_GET['logID']))
        deletefromgroup("log",$_GET['logID']);
}
if(isset($_GET['modelID']))
{
    $arr = $_SESSION['grpmodels'];
    $key = $_GET['modelID'];
    unset($arr[$key]);
    $_SESSION['grpmodels'] = $arr;
    echo "removed!";
}
if(isset($_GET['logID']))
{
    $arr = $_SESSION['grplogs'];
    $key = $_GET['logID'];
    unset($arr[$key]);
    $_SESSION['grplogs'] = $arr;
    echo "removed!";
}
?>
